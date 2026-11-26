<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';
require_once __DIR__ . '/../includes/aulas.php';
require_once __DIR__ . '/../includes/provas.php';

exigir_admin();

// Aula escolhida (?aula=ID) ou a do formulário enviado
$id_card = filter_var($_POST['id_card'] ?? ($_GET['aula'] ?? null), FILTER_VALIDATE_INT);

$estado = ['alternativas' => [], 'correta' => 0];
$valores = ['enunciado' => '', 'explicacao' => ''];


/* ---------- Envio dos formulários (POST) ---------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'criar';

    if (!csrf_valido()) {

        definir_aviso('Sessão expirada. Recarregue a página e tente novamente.');

    } elseif ($acao === 'excluir') {

        $id_pergunta = filter_var($_POST['id_pergunta'] ?? null, FILTER_VALIDATE_INT);

        try {
            if ($id_pergunta) {
                excluir_pergunta($pdo, $id_pergunta);
                definir_aviso('Pergunta excluída.', 'success');
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            definir_aviso('Não foi possível excluir a pergunta.');
        }

        header('Location: add_pergunta.php?aula=' . (int) $id_card);
        exit;

    } else {

        [$dados, $erro] = validar_dados_pergunta($_POST);

        if ($erro === null) {
            try {
                salvar_pergunta($pdo, $dados);

                definir_aviso('Pergunta cadastrada com sucesso!', 'success');
                header('Location: add_pergunta.php?aula=' . $dados['id_card']);
                exit;
            } catch (InvalidArgumentException $e) {
                $erro = $e->getMessage();
            } catch (PDOException $e) {
                error_log($e->getMessage());

                $erro = 'Erro ao cadastrar a pergunta. Verifique os dados e tente novamente.';
            }
        }

        // Algo deu errado: mostra o erro e devolve ao formulário o que foi digitado
        definir_aviso($erro);
        $estado = estado_pergunta($_POST);
        $valores = [
            'enunciado' => is_string($_POST['enunciado'] ?? null) ? $_POST['enunciado'] : '',
            'explicacao' => is_string($_POST['explicacao'] ?? null) ? $_POST['explicacao'] : '',
        ];
    }
}


/* ---------- Dados da página ---------- */

$aulas = [];
$contagem = [];
$aula = null;
$perguntas = [];
$erro_banco = false;

try {
    [$aulas] = listar_aulas($pdo);
    $contagem = contar_perguntas_por_aula($pdo);

    foreach ($aulas as $candidata) {
        if ($id_card && (int) $candidata['id_card'] === $id_card) {
            $aula = $candidata;
        }
    }

    if ($aula) {
        $perguntas = listar_perguntas_admin($pdo, (int) $aula['id_card']);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    $erro_banco = true;
}


/* ---------- Página ---------- */

$page_title = 'Adicionar Perguntas';
$body_class = 'admin-page';
$markdown = true;
$page_js = $aula ? ['add_pergunta'] : [];

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';

?>

    <main class="admin-main">

        <section class="admin-form-section">

            <div class="section-heading">

                <span class="eyebrow">ÁREA ADMINISTRATIVA</span>

                <h2>Perguntas da prova</h2>

                <p>Escolha a aula e cadastre as perguntas que vão aparecer no quiz e na prova final.</p>

            </div>

            <?php exibir_aviso(); ?>

            <?php if ($erro_banco): ?>

                <div class="alerta danger">
                    <p>Ocorreu um erro ao carregar os dados. Tente novamente.</p>
                </div>

            <?php elseif (empty($aulas)): ?>

                <div class="alerta danger">
                    <p>Cadastre uma aula primeiro: as perguntas pertencem a uma aula.</p>
                </div>

                <a href="add_aula.php" class="btn-primary">Criar aula <i class="ph ph-plus"></i></a>

            <?php else: ?>

                <form method="GET" class="admin-form admin-form--escolha">
                    <div>
                        <label for="aula">Aula</label>

                        <select name="aula" id="aula" required>
                            <option value="">Escolha uma aula...</option>

                            <?php foreach ($aulas as $item): ?>
                                <?php $total_item = $contagem[(int) $item['id_card']] ?? 0; ?>
                                <option value="<?= (int) $item['id_card'] ?>" <?= $aula && (int) $aula['id_card'] === (int) $item['id_card'] ? 'selected' : '' ?>>
                                    Aula <?= e(str_pad($item['num_aula'], 2, '0', STR_PAD_LEFT)) ?> — <?= e($item['title']) ?>
                                    (<?= $total_item ?> <?= $total_item === 1 ? 'pergunta' : 'perguntas' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-outline">
                        <i class="ph ph-arrow-right"></i>
                        Abrir aula
                    </button>
                </form>

                <?php if ($aula): ?>

                    <div class="admin-contexto">
                        Nova pergunta para a
                        <strong>Aula <?= e(str_pad($aula['num_aula'], 2, '0', STR_PAD_LEFT)) ?> — <?= e($aula['title']) ?></strong>
                    </div>

                    <form
                        method="POST"
                        class="admin-form"
                        data-min-alternativas="<?= MIN_ALTERNATIVAS ?>"
                        data-max-alternativas="<?= MAX_ALTERNATIVAS ?>"
                        data-limite-alternativa="<?= LIMITE_ALTERNATIVA ?>"
                    >

                        <?= csrf_campo() ?>
                        <input type="hidden" name="acao" value="criar">
                        <input type="hidden" name="id_card" value="<?= (int) $aula['id_card'] ?>">

                        <div>
                            <label for="enunciado">Enunciado <span class="field-hint">(aceita Markdown e fórmulas)</span></label>

                            <textarea
                                name="enunciado"
                                id="enunciado"
                                class="code-area"
                                maxlength="<?= LIMITE_ENUNCIADO ?>"
                                placeholder="Ex.: Qual é a saída de um Perceptron com pesos $w_1 = 1$, $w_2 = 1$ e bias $-1.5$ para a entrada (1, 1)?"
                                required
                            ><?= e($valores['enunciado']) ?></textarea>
                        </div>

                        <div>
                            <label>Alternativas</label>

                            <p class="field-hint">Clique na letra para marcar a alternativa correta (de 2 a 6 alternativas).</p>

                            <div class="alt-list" id="alternativas-list">
                                <!-- Alternativas adicionadas pelo JavaScript -->
                            </div>

                            <button type="button" class="btn-outline" id="adicionar_alternativa">
                                <i class="ph ph-plus"></i>
                                Adicionar alternativa
                            </button>
                        </div>

                        <div>
                            <label for="explicacao">Explicação <span class="field-hint">(opcional, aparece no resultado)</span></label>

                            <textarea
                                name="explicacao"
                                id="explicacao"
                                maxlength="<?= LIMITE_EXPLICACAO ?>"
                                placeholder="Por que essa é a resposta certa?"
                            ><?= e($valores['explicacao']) ?></textarea>
                        </div>

                        <button type="submit" class="btn-primary">
                            Cadastrar pergunta
                            <i class="ph ph-plus"></i>
                        </button>

                    </form>

                    <script type="application/json" id="estado-inicial"><?= json_encode(
                        $estado,
                        JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
                    ) ?></script>

                    <div class="pergunta-lista">

                        <h3 class="form-block-title">
                            Perguntas cadastradas nesta aula (<?= count($perguntas) ?>)
                        </h3>

                        <?php if (empty($perguntas)): ?>

                            <p class="field-hint">Nenhuma pergunta ainda. Cadastre a primeira acima.</p>

                        <?php endif; ?>

                        <?php foreach ($perguntas as $i => $pergunta): ?>

                            <article class="pergunta-item">

                                <div class="pergunta-item-topo">
                                    <span class="section-number"><?= $i + 1 ?></span>

                                    <form method="POST" class="pergunta-excluir" data-confirmar="Excluir esta pergunta? As respostas dos alunos nela também serão apagadas.">
                                        <?= csrf_campo() ?>
                                        <input type="hidden" name="acao" value="excluir">
                                        <input type="hidden" name="id_card" value="<?= (int) $aula['id_card'] ?>">
                                        <input type="hidden" name="id_pergunta" value="<?= (int) $pergunta['id_pergunta'] ?>">

                                        <button type="submit" class="icon-btn danger" title="Excluir pergunta" aria-label="Excluir pergunta">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </form>
                                </div>

                                <div class="markdown" data-markdown><pre><?= e($pergunta['pergunta']) ?></pre></div>

                                <ul class="pergunta-alternativas">
                                    <?php foreach ($pergunta['alternativas'] as $j => $alternativa): ?>
                                        <li class="<?= $alternativa['correta'] ? 'correta' : '' ?>">
                                            <span class="letra"><?= chr(65 + $j) ?></span>
                                            <span data-markdown-inline><?= e($alternativa['conteudo']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <?php if (!empty($pergunta['explicacao'])): ?>
                                    <div class="pergunta-explicacao">
                                        <strong>Explicação:</strong>
                                        <span data-markdown-inline><?= e($pergunta['explicacao']) ?></span>
                                    </div>
                                <?php endif; ?>

                            </article>

                        <?php endforeach; ?>

                    </div>

                <?php endif; ?>

            <?php endif; ?>

        </section>

    </main>

<?php require __DIR__ . '/../includes/scripts.php';
