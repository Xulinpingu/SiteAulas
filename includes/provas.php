<?php

/**
 * Provas: cadastro de perguntas/alternativas (admin) e provas dos alunos
 * (sorteio, correção e histórico de tentativas).
 * Requer $pdo (config/connDB.php) e includes/bootstrap.php.
 *
 * Modelo de dados:
 *   perguntas  -> conteudos (id_conteudo) -> card_aulas (id_card)
 *   alternativas -> perguntas
 *   tentativas / tentativa_respostas guardam o resultado de cada aluno
 *
 * A correção é sempre feita aqui, no servidor: o navegador nunca recebe
 * qual alternativa é a correta antes do aluno enviar a prova.
 */

const LIMITE_ENUNCIADO = 5000;
const LIMITE_ALTERNATIVA = 500;
const LIMITE_EXPLICACAO = 2000;
const MIN_ALTERNATIVAS = 2;
const MAX_ALTERNATIVAS = 6;

/** Máximo de perguntas sorteadas na prova final (todas as aulas). */
const PERGUNTAS_PROVA_GERAL = 20;

/** Quantidade de perguntas em cada quiz individual. */
const PERGUNTAS_PROVA_AULA = 15;

/** Percentual mínimo de acertos para considerar a média atingida. */
const MEDIA_APROVACAO = 70;

/** Saídas da aba permitidas: a 1ª é só aviso; ao chegar neste número a prova é cancelada. */
const PROVA_MAX_SAIDAS = 2;

/** Folga (em segundos) entre o fim do tempo e o envio, para absorver latência de rede. */
const PROVA_TOLERANCIA_SEGUNDOS = 60;

/** Duração fixa de todas as provas: 30 minutos. */
const DURACAO_PROVA_SEGUNDOS = 30 * 60;


/** "?,?,?" para usar em IN (...) com parâmetros posicionais. */
function marcadores(int $quantidade): string
{
    return implode(',', array_fill(0, $quantidade, '?'));
}

function data_br(string $valor): string
{
    $tempo = strtotime($valor);

    return $tempo ? date('d/m/Y H:i', $tempo) : '';
}


/* ==========================================================================
   Cadastro de perguntas (admin)
   ========================================================================== */

/**
 * Valida o POST do formulário de pergunta.
 * Retorna [$dados, null] se estiver tudo certo ou [[], 'mensagem de erro'].
 */
function validar_dados_pergunta(array $post): array
{
    $id_card = filter_var($post['id_card'] ?? null, FILTER_VALIDATE_INT);
    $enunciado = is_string($post['enunciado'] ?? null) ? trim(str_replace("\r\n", "\n", $post['enunciado'])) : '';
    $explicacao = is_string($post['explicacao'] ?? null) ? trim(str_replace("\r\n", "\n", $post['explicacao'])) : '';
    $brutas = is_array($post['alternativas'] ?? null) ? array_values($post['alternativas']) : [];
    $correta = filter_var($post['correta'] ?? null, FILTER_VALIDATE_INT);

    if (!$id_card || $id_card < 1) {
        return [[], 'Escolha a aula da pergunta.'];
    }

    if ($enunciado === '') {
        return [[], 'Escreva o enunciado da pergunta.'];
    }

    if (mb_strlen($enunciado, 'UTF-8') > LIMITE_ENUNCIADO) {
        return [[], 'O enunciado deve ter no máximo ' . LIMITE_ENUNCIADO . ' caracteres.'];
    }

    if (mb_strlen($explicacao, 'UTF-8') > LIMITE_EXPLICACAO) {
        return [[], 'A explicação deve ter no máximo ' . LIMITE_EXPLICACAO . ' caracteres.'];
    }

    $total = count($brutas);

    if ($total < MIN_ALTERNATIVAS || $total > MAX_ALTERNATIVAS) {
        return [[], 'Use de ' . MIN_ALTERNATIVAS . ' a ' . MAX_ALTERNATIVAS . ' alternativas.'];
    }

    $alternativas = [];
    $repetidas = [];

    foreach ($brutas as $i => $texto) {
        $letra = chr(65 + $i);
        $texto = texto_limpo($texto);

        if ($texto === '') {
            return [[], "A alternativa {$letra} está vazia. Preencha ou remova."];
        }

        if (mb_strlen($texto, 'UTF-8') > LIMITE_ALTERNATIVA) {
            return [[], "A alternativa {$letra} deve ter no máximo " . LIMITE_ALTERNATIVA . ' caracteres.'];
        }

        $repetidas[mb_strtolower($texto, 'UTF-8')] = true;
        $alternativas[] = $texto;
    }

    if (count($repetidas) !== $total) {
        return [[], 'Há alternativas repetidas.'];
    }

    if ($correta === false || $correta < 0 || $correta >= $total) {
        return [[], 'Marque qual alternativa é a correta.'];
    }

    return [[
        'id_card' => $id_card,
        'enunciado' => $enunciado,
        'explicacao' => $explicacao,
        'alternativas' => $alternativas,
        'correta' => $correta,
    ], null];
}

/** Devolve ao formulário (como JSON) o que foi digitado, para não perder tudo num erro. */
function estado_pergunta(array $post): array
{
    $alternativas = [];

    foreach ((is_array($post['alternativas'] ?? null) ? $post['alternativas'] : []) as $texto) {
        if (is_string($texto)) {
            $alternativas[] = $texto;
        }
    }

    $correta = filter_var($post['correta'] ?? 0, FILTER_VALIDATE_INT);

    return [
        'alternativas' => array_slice($alternativas, 0, MAX_ALTERNATIVAS),
        'correta' => $correta === false ? 0 : $correta,
    ];
}

/**
 * Grava a pergunta e suas alternativas numa transação. Se a aula ainda não
 * tem registro em "conteudos", ele é criado (sem seções) para receber a pergunta.
 * Retorna o id da pergunta.
 *
 * @throws InvalidArgumentException aula inexistente
 * @throws PDOException             erro de banco
 */
function salvar_pergunta(PDO $pdo, array $dados): int
{
    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("SELECT title FROM card_aulas WHERE id_card = :id");
        $stmt->execute([':id' => $dados['id_card']]);
        $titulo_aula = $stmt->fetchColumn();

        if ($titulo_aula === false) {
            throw new InvalidArgumentException('A aula escolhida não existe.');
        }

        $stmt = $pdo->prepare("SELECT id_conteudo FROM conteudos WHERE id_card = :id");
        $stmt->execute([':id' => $dados['id_card']]);
        $id_conteudo = $stmt->fetchColumn();

        if (!$id_conteudo) {
            $stmt = $pdo->prepare(
                "INSERT INTO conteudos (id_card, titulo, referencia)
                 VALUES (:id_card, :titulo, '')"
            );
            $stmt->execute([':id_card' => $dados['id_card'], ':titulo' => $titulo_aula]);
            $id_conteudo = (int) $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare(
            "INSERT INTO perguntas (id_conteudo, pergunta, explicacao)
             VALUES (:id_conteudo, :pergunta, :explicacao)"
        );
        $stmt->execute([
            ':id_conteudo' => $id_conteudo,
            ':pergunta' => $dados['enunciado'],
            ':explicacao' => $dados['explicacao'] !== '' ? $dados['explicacao'] : null,
        ]);

        $id_pergunta = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            "INSERT INTO alternativas (id_pergunta, conteudo, correta)
             VALUES (:id_pergunta, :conteudo, :correta)"
        );

        foreach ($dados['alternativas'] as $i => $texto) {
            $stmt->execute([
                ':id_pergunta' => $id_pergunta,
                ':conteudo' => $texto,
                ':correta' => $i === $dados['correta'] ? 1 : 0,
            ]);
        }

        $pdo->commit();

        return $id_pergunta;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}

/** Exclui a pergunta (alternativas e respostas de tentativas vão junto, por cascata). */
function excluir_pergunta(PDO $pdo, int $id_pergunta): void
{
    $stmt = $pdo->prepare("DELETE FROM perguntas WHERE id_pergunta = :id");
    $stmt->execute([':id' => $id_pergunta]);
}

/** Perguntas de uma aula com todas as alternativas (inclusive qual é a correta). */
function listar_perguntas_admin(PDO $pdo, int $id_card): array
{
    $stmt = $pdo->prepare(
        "SELECT p.id_pergunta, p.pergunta, p.explicacao
         FROM perguntas p
         INNER JOIN conteudos c ON c.id_conteudo = p.id_conteudo
         WHERE c.id_card = :id
         ORDER BY p.id_pergunta ASC"
    );
    $stmt->execute([':id' => $id_card]);
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$perguntas) {
        return [];
    }

    $ids = array_column($perguntas, 'id_pergunta');

    $stmt = $pdo->prepare(
        "SELECT id_alternativa, id_pergunta, conteudo, correta
         FROM alternativas
         WHERE id_pergunta IN (" . marcadores(count($ids)) . ")
         ORDER BY id_alternativa ASC"
    );
    $stmt->execute($ids);

    $por_pergunta = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $alternativa) {
        $alternativa['correta'] = (int) $alternativa['correta'] === 1;
        $por_pergunta[$alternativa['id_pergunta']][] = $alternativa;
    }

    $resultado = [];

    foreach ($perguntas as $pergunta) {
        $pergunta['alternativas'] = $por_pergunta[$pergunta['id_pergunta']] ?? [];
        $resultado[] = $pergunta;
    }

    return $resultado;
}

/** [id_card => quantidade de perguntas]. */
function contar_perguntas_por_aula(PDO $pdo): array
{
    $linhas = $pdo->query(
        "SELECT c.id_card, COUNT(p.id_pergunta) AS total
         FROM perguntas p
         INNER JOIN conteudos c ON c.id_conteudo = p.id_conteudo
         WHERE c.id_card IS NOT NULL
         GROUP BY c.id_card"
    )->fetchAll(PDO::FETCH_ASSOC);

    $contagem = [];

    foreach ($linhas as $linha) {
        $contagem[(int) $linha['id_card']] = (int) $linha['total'];
    }

    return $contagem;
}


/* ==========================================================================
   Prova do aluno: sorteio
   ========================================================================== */

/**
 * Perguntas sorteadas de uma aula ($id_card) ou de todas as aulas (null),
 * com as alternativas embaralhadas e SEM a indicação da correta.
 */
function carregar_prova(PDO $pdo, ?int $id_card): array
{
    $sql = "SELECT p.id_pergunta, p.pergunta, a.id_card, a.num_aula, a.title AS aula_titulo
            FROM perguntas p
            INNER JOIN conteudos c ON c.id_conteudo = p.id_conteudo
            INNER JOIN card_aulas a ON a.id_card = c.id_card";
    $parametros = [];

    if ($id_card !== null) {
        $sql .= " WHERE a.id_card = :id";
        $parametros[':id'] = $id_card;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($parametros);
    $perguntas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$perguntas) {
        return [];
    }

    $ids = array_column($perguntas, 'id_pergunta');

    $stmt = $pdo->prepare(
        "SELECT id_alternativa, id_pergunta, conteudo
         FROM alternativas
         WHERE id_pergunta IN (" . marcadores(count($ids)) . ")"
    );
    $stmt->execute($ids);

    $por_pergunta = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $alternativa) {
        $por_pergunta[$alternativa['id_pergunta']][] = $alternativa;
    }

    $prova = [];

    foreach ($perguntas as $pergunta) {
        $alternativas = $por_pergunta[$pergunta['id_pergunta']] ?? [];

        // Pergunta sem alternativas suficientes não entra na prova
        if (count($alternativas) < MIN_ALTERNATIVAS) {
            continue;
        }

        shuffle($alternativas);
        $pergunta['alternativas'] = $alternativas;
        $prova[] = $pergunta;
    }

    shuffle($prova);

    // Prova final: até 20 questões.
    if ($id_card === null) {
        $prova = array_slice($prova, 0, PERGUNTAS_PROVA_GERAL);

    // Quiz individual: até 15 questões.
    } else {
        $prova = array_slice($prova, 0, PERGUNTAS_PROVA_AULA);
    }

    return $prova;
}


/* ==========================================================================
   Prova em andamento (guardada na sessão)
   A correção usa as perguntas sorteadas no servidor, não as que o navegador
   enviar: assim ninguém escolhe responder só as questões que sabe.
   ========================================================================== */

/**
 * Todas as provas possuem duração fixa de 30 minutos.
 */
function duracao_prova(): int
{
    return DURACAO_PROVA_SEGUNDOS;
}

/**
 * Guarda a prova sorteada na sessão (perguntas e alternativas já embaralhadas)
 * junto com o relógio e as saídas de aba. Tudo isso vive no servidor: recarregar
 * a página não sorteia outra prova, não reinicia o tempo e não zera as saídas.
 */
function registrar_prova_ativa(?int $id_card, string $titulo, array $prova): string
{
    $token = bin2hex(random_bytes(8));

    $_SESSION['provas_ativas'][$token] = [
        'id_card' => $id_card,
        'titulo' => $titulo,
        'ids' => array_map('intval', array_column($prova, 'id_pergunta')),
        'prova' => $prova,
        'inicio' => time(),
        'duracao' => duracao_prova(),
        'saidas' => 0,
        'cancelada' => false,
    ];

    // Guarda só as últimas 5 provas abertas (várias abas, recarregamentos)
    $_SESSION['provas_ativas'] = array_slice($_SESSION['provas_ativas'], -5, null, true);

    return $token;
}

function obter_prova_ativa(string $token): ?array
{
    return $_SESSION['provas_ativas'][$token] ?? null;
}

function encerrar_prova_ativa(string $token): void
{
    unset($_SESSION['provas_ativas'][$token]);
}

/** Segundos que ainda restam na prova (0 se o tempo acabou). */
function tempo_restante_prova(array $prova_ativa): int
{
    return max(0, $prova_ativa['inicio'] + $prova_ativa['duracao'] - time());
}

/** Estado da prova para o navegador, ou null se ela não existe mais. */
function estado_prova_ativa(string $token): ?array
{
    $prova_ativa = obter_prova_ativa($token);

    if ($prova_ativa === null) {
        return null;
    }

    return [
        'saidas' => $prova_ativa['saidas'],
        'max' => PROVA_MAX_SAIDAS,
        'cancelada' => $prova_ativa['cancelada'],
        'restante' => tempo_restante_prova($prova_ativa),
    ];
}

/**
 * Registra uma saída da aba. Ao chegar em PROVA_MAX_SAIDAS a prova é marcada
 * como cancelada (o envio passa a ser recusado em prova.php).
 */
function registrar_saida_prova(string $token): ?array
{
    $prova_ativa = obter_prova_ativa($token);

    if ($prova_ativa === null) {
        return null;
    }

    if (!$prova_ativa['cancelada']) {
        $_SESSION['provas_ativas'][$token]['saidas']++;

        if ($_SESSION['provas_ativas'][$token]['saidas'] >= PROVA_MAX_SAIDAS) {
            $_SESSION['provas_ativas'][$token]['cancelada'] = true;
        }
    }

    return estado_prova_ativa($token);
}


/* ==========================================================================
   Prova do aluno: correção e resultados
   ========================================================================== */

/**
 * Corrige a prova, grava a tentativa e as respostas e devolve o id da tentativa.
 * $respostas é [id_pergunta => id_alternativa escolhida]; pergunta sem resposta
 * (ou com alternativa que não pertence a ela) conta como errada.
 *
 * @throws InvalidArgumentException prova sem perguntas existentes
 * @throws PDOException             erro de banco
 */
function corrigir_prova(PDO $pdo, int $id_usuario, ?int $id_card, array $ids_perguntas, array $respostas): int
{
    $ids = array_values(array_unique(array_map('intval', $ids_perguntas)));

    if (!$ids) {
        throw new InvalidArgumentException('Essa prova não tem perguntas.');
    }

    $pdo->beginTransaction();

    try {
        // Perguntas que ainda existem (alguma pode ter sido excluída pelo admin)
        $stmt = $pdo->prepare("SELECT id_pergunta FROM perguntas WHERE id_pergunta IN (" . marcadores(count($ids)) . ")");
        $stmt->execute($ids);
        $existentes = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));

        if (!$existentes) {
            throw new InvalidArgumentException('As perguntas dessa prova não existem mais.');
        }

        $stmt = $pdo->prepare(
            "SELECT id_alternativa, id_pergunta, correta
             FROM alternativas
             WHERE id_pergunta IN (" . marcadores(count($existentes)) . ")"
        );
        $stmt->execute($existentes);

        // $gabarito[id_pergunta][id_alternativa] = true se for a correta
        $gabarito = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {
            $gabarito[(int) $linha['id_pergunta']][(int) $linha['id_alternativa']] = (int) $linha['correta'] === 1;
        }

        $corrigidas = [];
        $acertos = 0;

        foreach ($existentes as $id_pergunta) {
            $escolhida = null;

            if (isset($respostas[$id_pergunta]) && is_scalar($respostas[$id_pergunta])) {
                $candidata = (int) $respostas[$id_pergunta];

                if (isset($gabarito[$id_pergunta][$candidata])) {
                    $escolhida = $candidata;
                }
            }

            $certa = $escolhida !== null && $gabarito[$id_pergunta][$escolhida];

            if ($certa) {
                $acertos++;
            }

            $corrigidas[] = [$id_pergunta, $escolhida, $certa ? 1 : 0];
        }

        $stmt = $pdo->prepare(
            "INSERT INTO tentativas (id_usuario, id_card, acertos, total)
             VALUES (:usuario, :card, :acertos, :total)"
        );
        $stmt->execute([
            ':usuario' => $id_usuario,
            ':card' => $id_card,
            ':acertos' => $acertos,
            ':total' => count($corrigidas),
        ]);

        $id_tentativa = (int) $pdo->lastInsertId();

        $stmt = $pdo->prepare(
            "INSERT INTO tentativa_respostas (id_tentativa, id_pergunta, id_alternativa, correta)
             VALUES (:tentativa, :pergunta, :alternativa, :correta)"
        );

        foreach ($corrigidas as [$id_pergunta, $escolhida, $certa]) {
            $stmt->execute([
                ':tentativa' => $id_tentativa,
                ':pergunta' => $id_pergunta,
                ':alternativa' => $escolhida,
                ':correta' => $certa,
            ]);
        }

        $pdo->commit();

        return $id_tentativa;
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}

/** Resultado de uma tentativa do usuário, com o gabarito comentado (ou null). */
function buscar_resultado(PDO $pdo, int $id_tentativa, int $id_usuario): ?array
{
    $stmt = $pdo->prepare(
        "SELECT t.id_tentativa, t.id_card, t.acertos, t.total, t.data_tentativa,
                a.num_aula, a.title AS aula_titulo
         FROM tentativas t
         LEFT JOIN card_aulas a ON a.id_card = t.id_card
         WHERE t.id_tentativa = :tentativa AND t.id_usuario = :usuario"
    );
    $stmt->execute([':tentativa' => $id_tentativa, ':usuario' => $id_usuario]);
    $tentativa = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tentativa) {
        return null;
    }

    $total = (int) $tentativa['total'];
    $acertos = (int) $tentativa['acertos'];
    $tentativa['percentual'] = $total > 0 ? (int) round($acertos * 100 / $total) : 0;
    $tentativa['aprovado'] = $tentativa['percentual'] >= MEDIA_APROVACAO;
    $tentativa['perguntas'] = [];

    $stmt = $pdo->prepare(
        "SELECT r.id_pergunta, r.id_alternativa, r.correta,
                p.pergunta, p.explicacao, a.num_aula, a.title AS aula_titulo
         FROM tentativa_respostas r
         INNER JOIN perguntas p ON p.id_pergunta = r.id_pergunta
         INNER JOIN conteudos c ON c.id_conteudo = p.id_conteudo
         INNER JOIN card_aulas a ON a.id_card = c.id_card
         WHERE r.id_tentativa = :tentativa
         ORDER BY a.num_aula ASC, p.id_pergunta ASC"
    );
    $stmt->execute([':tentativa' => $id_tentativa]);
    $respostas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$respostas) {
        return $tentativa;
    }

    $ids = array_column($respostas, 'id_pergunta');

    $stmt = $pdo->prepare(
        "SELECT id_alternativa, id_pergunta, conteudo, correta
         FROM alternativas
         WHERE id_pergunta IN (" . marcadores(count($ids)) . ")
         ORDER BY id_alternativa ASC"
    );
    $stmt->execute($ids);

    $por_pergunta = [];

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $alternativa) {
        $alternativa['correta'] = (int) $alternativa['correta'] === 1;
        $por_pergunta[$alternativa['id_pergunta']][] = $alternativa;
    }

    foreach ($respostas as $resposta) {
        $resposta['correta'] = (int) $resposta['correta'] === 1;
        $resposta['escolhida'] = $resposta['id_alternativa'] !== null ? (int) $resposta['id_alternativa'] : null;
        $resposta['alternativas'] = $por_pergunta[$resposta['id_pergunta']] ?? [];
        $tentativa['perguntas'][] = $resposta;
    }

    return $tentativa;
}

/** Últimas tentativas do usuário (mais recentes primeiro). */
function listar_tentativas(PDO $pdo, int $id_usuario, int $limite = 8): array
{
    $stmt = $pdo->prepare(
        "SELECT t.id_tentativa, t.id_card, t.acertos, t.total, t.data_tentativa,
                a.num_aula, a.title AS aula_titulo
         FROM tentativas t
         LEFT JOIN card_aulas a ON a.id_card = t.id_card
         WHERE t.id_usuario = :usuario
         ORDER BY t.id_tentativa DESC
         LIMIT " . (int) $limite
    );
    $stmt->execute([':usuario' => $id_usuario]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
