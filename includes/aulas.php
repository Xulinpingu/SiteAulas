<?php

/**
 * Acesso a dados das aulas: card (card_aulas + tags) e conteúdo
 * (conteudos + subtitulos, ligados ao card por conteudos.id_card).
 * Requer $pdo (config/connDB.php).
 */

const LIMITE_TITULO = 50;
const LIMITE_DESCRICAO = 100;
const LIMITE_TAG = 50;
const LIMITE_REFERENCIA = 255;
const LIMITE_SUBTITULO = 150;
const LIMITE_CONTEUDO_BYTES = 60000;
const LIMITE_SECOES = 60;


/* ---------- Leitura ---------- */

/** Todos os cards, na ordem da trilha. Retorna [$aulas, $tags_por_aula]. */
function listar_aulas(PDO $pdo): array
{
    $aulas = $pdo->query(
        "SELECT id_card, title, num_aula, descricao
         FROM card_aulas
         ORDER BY num_aula ASC, id_card ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    $linhas = $pdo->query(
        "SELECT rel.id_card, t.tag
         FROM aula_tag rel
         INNER JOIN tag t ON t.id_tag = rel.id_tag
         ORDER BY t.tag ASC"
    )->fetchAll(PDO::FETCH_ASSOC);

    $tags_por_aula = [];

    foreach ($linhas as $linha) {
        $tags_por_aula[$linha['id_card']][] = $linha['tag'];
    }

    return [$aulas, $tags_por_aula];
}

/** Uma aula completa (card, tags, referência e seções) ou null se não existir. */
function buscar_aula(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare(
        "SELECT id_card, title, num_aula, descricao
         FROM card_aulas
         WHERE id_card = :id"
    );
    $stmt->execute([':id' => $id]);
    $aula = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$aula) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT t.tag
         FROM aula_tag rel
         INNER JOIN tag t ON t.id_tag = rel.id_tag
         WHERE rel.id_card = :id
         ORDER BY t.tag ASC"
    );
    $stmt->execute([':id' => $id]);
    $aula['tags'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $aula['referencia'] = '';
    $aula['secoes'] = [];

    $stmt = $pdo->prepare(
        "SELECT id_conteudo, referencia
         FROM conteudos
         WHERE id_card = :id"
    );
    $stmt->execute([':id' => $id]);
    $conteudo = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($conteudo) {
        $aula['referencia'] = $conteudo['referencia'];

        $stmt = $pdo->prepare(
            "SELECT titulo, conteudo
             FROM subtitulos
             WHERE id_conteudo = :id
             ORDER BY ordem ASC"
        );
        $stmt->execute([':id' => $conteudo['id_conteudo']]);
        $aula['secoes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Quantas perguntas de prova esta aula tem (para o link do quiz)
    $stmt = $pdo->prepare(
        "SELECT COUNT(*)
         FROM perguntas p
         INNER JOIN conteudos c ON c.id_conteudo = p.id_conteudo
         WHERE c.id_card = :id"
    );
    $stmt->execute([':id' => $id]);
    $aula['total_perguntas'] = (int) $stmt->fetchColumn();

    // Aulas vizinhas na trilha (botões "aula anterior" e "próxima aula")
    $stmt = $pdo->prepare(
        "SELECT id_card, title, num_aula
         FROM card_aulas
         WHERE num_aula > :num
         ORDER BY num_aula ASC, id_card ASC
         LIMIT 1"
    );
    $stmt->execute([':num' => $aula['num_aula']]);
    $aula['proxima'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    $stmt = $pdo->prepare(
        "SELECT id_card, title, num_aula
         FROM card_aulas
         WHERE num_aula < :num
         ORDER BY num_aula DESC, id_card DESC
         LIMIT 1"
    );
    $stmt->execute([':num' => $aula['num_aula']]);
    $aula['anterior'] = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    return $aula;
}

/** Dados básicos de um card (ou null se não existir). */
function buscar_card(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare("SELECT id_card, title, num_aula, descricao FROM card_aulas WHERE id_card = :id");
    $stmt->execute([':id' => $id]);

    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

/** Sugestão para o campo "Número da aula" (maior número atual + 1). */
function proximo_numero_aula(PDO $pdo): int
{
    return (int) $pdo->query("SELECT COALESCE(MAX(num_aula), 0) + 1 FROM card_aulas")->fetchColumn();
}


/* ---------- Validação ---------- */

/**
 * Valida o POST do formulário de aula.
 * Retorna [$dados, null] se estiver tudo certo ou [[], 'mensagem de erro'].
 */
function validar_dados_aula(array $post): array
{
    $titulo = texto_limpo($post['titulo'] ?? '');
    $descricao = texto_limpo($post['descricao'] ?? '');
    $referencia = texto_limpo($post['referencia'] ?? '');
    $num_aula = filter_var($post['num_aula'] ?? null, FILTER_VALIDATE_INT);

    if ($titulo === '' || $descricao === '' || $num_aula === false || $num_aula === null || $num_aula < 1) {
        return [[], 'Preencha título, descrição e número da aula corretamente.'];
    }

    if (mb_strlen($titulo, 'UTF-8') > LIMITE_TITULO) {
        return [[], 'O título deve ter no máximo ' . LIMITE_TITULO . ' caracteres.'];
    }

    if (mb_strlen($descricao, 'UTF-8') > LIMITE_DESCRICAO) {
        return [[], 'A descrição deve ter no máximo ' . LIMITE_DESCRICAO . ' caracteres.'];
    }

    if ($referencia !== '') {
        $esquema = strtolower((string) parse_url($referencia, PHP_URL_SCHEME));

        if (
            mb_strlen($referencia, 'UTF-8') > LIMITE_REFERENCIA ||
            !filter_var($referencia, FILTER_VALIDATE_URL) ||
            !in_array($esquema, ['http', 'https'], true)
        ) {
            return [[], 'Informe um link de referência válido (http ou https).'];
        }
    }

    // Tags (sem repetir, ignorando maiúsculas/minúsculas)
    $tags_unicas = [];
    $tags_brutas = is_array($post['tags'] ?? null) ? $post['tags'] : [];

    foreach ($tags_brutas as $nome) {
        $nome = texto_limpo($nome);

        if ($nome !== '' && mb_strlen($nome, 'UTF-8') <= LIMITE_TAG) {
            $tags_unicas[mb_strtolower($nome, 'UTF-8')] = $nome;
        }
    }

    // Seções do conteúdo (subtitulos[] e conteudos[] chegam em paralelo, na ordem da tela)
    $subtitulos = is_array($post['subtitulos'] ?? null) ? array_values($post['subtitulos']) : [];
    $conteudos = is_array($post['conteudos'] ?? null) ? array_values($post['conteudos']) : [];
    $total = max(count($subtitulos), count($conteudos));

    if ($total > LIMITE_SECOES) {
        return [[], 'Use no máximo ' . LIMITE_SECOES . ' seções por aula.'];
    }

    $secoes = [];

    for ($i = 0; $i < $total; $i++) {
        $subtitulo = texto_limpo($subtitulos[$i] ?? '');
        $texto = is_string($conteudos[$i] ?? null)
            ? trim(str_replace("\r\n", "\n", $conteudos[$i]))
            : '';

        if ($subtitulo === '' && $texto === '') {
            continue; // seção vazia é ignorada
        }

        $numero = $i + 1;

        if ($subtitulo === '' || $texto === '') {
            return [[], "A seção {$numero} precisa de subtítulo e de conteúdo."];
        }

        if (mb_strlen($subtitulo, 'UTF-8') > LIMITE_SUBTITULO) {
            return [[], "O subtítulo da seção {$numero} deve ter no máximo " . LIMITE_SUBTITULO . ' caracteres.'];
        }

        if (strlen($texto) > LIMITE_CONTEUDO_BYTES) {
            return [[], "O conteúdo da seção {$numero} é grande demais. Divida em mais seções."];
        }

        $secoes[] = ['titulo' => $subtitulo, 'conteudo' => $texto];
    }

    return [[
        'titulo' => $titulo,
        'descricao' => $descricao,
        'num_aula' => $num_aula,
        'referencia' => $referencia,
        'tags' => array_values($tags_unicas),
        'secoes' => $secoes,
    ], null];
}

/** Devolve ao formulário (como JSON) o que o usuário digitou, para não perder tudo num erro. */
function estado_form(array $post): array
{
    $tags = [];

    foreach ((is_array($post['tags'] ?? null) ? $post['tags'] : []) as $tag) {
        if (is_string($tag)) {
            $tags[] = $tag;
        }
    }

    $subtitulos = is_array($post['subtitulos'] ?? null) ? array_values($post['subtitulos']) : [];
    $conteudos = is_array($post['conteudos'] ?? null) ? array_values($post['conteudos']) : [];
    $total = min(max(count($subtitulos), count($conteudos)), LIMITE_SECOES);
    $secoes = [];

    for ($i = 0; $i < $total; $i++) {
        $secoes[] = [
            'titulo' => is_string($subtitulos[$i] ?? null) ? $subtitulos[$i] : '',
            'conteudo' => is_string($conteudos[$i] ?? null) ? $conteudos[$i] : '',
        ];
    }

    return ['tags' => $tags, 'secoes' => $secoes];
}


/* ---------- Gravação ---------- */

/**
 * Grava card, tags, conteúdo e seções numa única transação.
 * Recebe o array devolvido por validar_dados_aula(). Retorna o id do card.
 *
 * @throws InvalidArgumentException número de aula repetido
 * @throws PDOException             erro de banco
 */
function salvar_aula(PDO $pdo, array $dados): int
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("SELECT 1 FROM card_aulas WHERE num_aula = :num LIMIT 1");
        $stmt->execute([':num' => $dados['num_aula']]);

        if ($stmt->fetchColumn()) {
            throw new InvalidArgumentException('Já existe uma aula com esse número.');
        }

        $stmt = $pdo->prepare(
            "INSERT INTO card_aulas (title, num_aula, descricao)
             VALUES (:titulo, :num_aula, :descricao)"
        );
        $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':num_aula' => $dados['num_aula'],
            ':descricao' => $dados['descricao'],
        ]);

        $id_card = (int) $pdo->lastInsertId();

        // Tags: reaproveita as que já existem e cria as novas
        $buscar = $pdo->prepare("SELECT id_tag FROM tag WHERE tag = :tag");
        $inserir = $pdo->prepare("INSERT INTO tag (tag) VALUES (:tag)");
        $relacionar = $pdo->prepare("INSERT INTO aula_tag (id_card, id_tag) VALUES (:id_card, :id_tag)");

        foreach ($dados['tags'] as $nome_tag) {
            $buscar->execute([':tag' => $nome_tag]);
            $id_tag = $buscar->fetchColumn();

            if (!$id_tag) {
                $inserir->execute([':tag' => $nome_tag]);
                $id_tag = $pdo->lastInsertId();
            }

            $relacionar->execute([':id_card' => $id_card, ':id_tag' => $id_tag]);
        }

        // Conteúdo (só cria se houver link de referência ou seções)
        if ($dados['referencia'] !== '' || !empty($dados['secoes'])) {
            $stmt = $pdo->prepare(
                "INSERT INTO conteudos (id_card, titulo, referencia)
                 VALUES (:id_card, :titulo, :referencia)"
            );
            $stmt->execute([
                ':id_card' => $id_card,
                ':titulo' => $dados['titulo'],
                ':referencia' => $dados['referencia'],
            ]);

            $id_conteudo = (int) $pdo->lastInsertId();

            $stmt = $pdo->prepare(
                "INSERT INTO subtitulos (id_conteudo, titulo, conteudo, ordem)
                 VALUES (:id_conteudo, :titulo, :conteudo, :ordem)"
            );

            foreach ($dados['secoes'] as $indice => $secao) {
                $stmt->execute([
                    ':id_conteudo' => $id_conteudo,
                    ':titulo' => $secao['titulo'],
                    ':conteudo' => $secao['conteudo'],
                    ':ordem' => $indice + 1,
                ]);
            }
        }

        $pdo->commit();

        return $id_card;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}
