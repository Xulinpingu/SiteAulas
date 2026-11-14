<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';
require_once __DIR__ . '/../includes/aulas.php';

exigir_admin();

$estado = ['tags' => [], 'secoes' => []];


/* ---------- Envio do formulário (POST) ---------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $erro = null;
    $dados = [];

    if (!csrf_valido()) {
        $erro = 'Sessão expirada. Recarregue a página e tente novamente.';
    } else {
        [$dados, $erro] = validar_dados_aula($_POST);
    }

    if ($erro === null) {
        try {
            $id_aula = salvar_aula($pdo, $dados);

            definir_aviso('Aula cadastrada com sucesso!', 'success');
            header('Location: aula.php?id=' . $id_aula);
            exit;
        } catch (InvalidArgumentException $e) {
            $erro = $e->getMessage();
        } catch (PDOException $e) {
            error_log($e->getMessage());

            $erro = $e->getCode() === '42S22'
                ? 'O banco precisa da migração database/migracao_01_conteudo_aulas.sql.'
                : 'Erro ao cadastrar a aula. Verifique os dados e tente novamente.';
        }
    }

    // Algo deu errado: mostra o erro e devolve ao formulário o que foi digitado
    definir_aviso($erro);
    $estado = estado_form($_POST);

    $valores = [
        'titulo' => texto_limpo($_POST['titulo'] ?? ''),
        'descricao' => texto_limpo($_POST['descricao'] ?? ''),
        'num_aula' => texto_limpo($_POST['num_aula'] ?? ''),
        'referencia' => texto_limpo($_POST['referencia'] ?? ''),
    ];

} else {

    $proximo = '';

    try {
        $proximo = (string) proximo_numero_aula($pdo);
    } catch (PDOException $e) {
        error_log($e->getMessage());
    }

    $valores = ['titulo' => '', 'descricao' => '', 'num_aula' => $proximo, 'referencia' => ''];
}


/* ---------- Página ---------- */

$page_title = 'Adicionar Aulas';
$body_class = 'admin-page';
$page_js = ['add_aula'];

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';

?>

    <main class="admin-main">

        <section class="admin-form-section">

            <div class="section-heading">

                <span class="eyebrow">ÁREA ADMINISTRATIVA</span>

                <h2>Adicionar nova aula</h2>

                <p>Preencha as informações para disponibilizar uma nova aula.</p>

            </div>

            <?php exibir_aviso(); ?>

            <form
                method="POST"
                class="admin-form"
                data-limite-titulo="<?= LIMITE_TITULO ?>"
                data-limite-tag="<?= LIMITE_TAG ?>"
                data-limite-subtitulo="<?= LIMITE_SUBTITULO ?>"
                data-limite-conteudo="<?= LIMITE_CONTEUDO_BYTES ?>"
            >

                <?= csrf_campo() ?>

                <div>
                    <label for="titulo">Título da aula</label>

                    <input
                        type="text"
                        name="titulo"
                        id="titulo"
                        maxlength="<?= LIMITE_TITULO ?>"
                        placeholder="Ex.: Perceptron: como um neurônio aprende"
                        value="<?= e($valores['titulo']) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="descricao">Descrição</label>

                    <textarea
                        name="descricao"
                        id="descricao"
                        maxlength="<?= LIMITE_DESCRICAO ?>"
                        placeholder="Descreva o conteúdo da aula..."
                        required
                    ><?= e($valores['descricao']) ?></textarea>
                </div>

                <div>
                    <label for="num_aula">Número da aula</label>

                    <input
                        type="number"
                        name="num_aula"
                        id="num_aula"
                        min="1"
                        placeholder="Ex.: 1"
                        value="<?= e($valores['num_aula']) ?>"
                        required
                    >
                </div>

                <div>
                    <label for="referencia">Link de referência <span class="field-hint">(opcional)</span></label>

                    <input
                        type="url"
                        name="referencia"
                        id="referencia"
                        maxlength="<?= LIMITE_REFERENCIA ?>"
                        placeholder="https://exemple.com"
                        value="<?= e($valores['referencia']) ?>"
                    >
                </div>

                <div class="tags-container">

                    <label for="nova_tag">Tags da aula</label>

                    <div class="tag-input-container">

                        <input
                            type="text"
                            id="nova_tag"
                            placeholder="Digite uma tag..."
                            maxlength="<?= LIMITE_TAG ?>"
                        >

                        <button type="button" id="adicionar_tag">
                            Adicionar
                            <i class="ph ph-plus"></i>
                        </button>

                    </div>

                    <div class="tags-list" id="tags-list">
                        <!-- Tags adicionadas pelo JavaScript -->
                    </div>

                </div>

                <div class="form-block">

                    <h3 class="form-block-title">Conteúdo da aula</h3>

                    <p class="field-hint">
                        Cada seção vira um subtítulo na página da aula. O texto aceita Markdown:
                        subtítulos <code>###</code>, listas, tabelas, blocos de código e fórmulas
                        com <code>$$ ... $$</code>.
                    </p>

                    <label for="importar_md">Importar apostila em Markdown <span class="field-hint">(opcional)</span></label>

                    <textarea
                        id="importar_md"
                        class="code-area"
                        placeholder="Cole aqui o README.md da aula. Cada título com ## vira uma seção."
                    ></textarea>

                    <button type="button" class="btn-outline" id="importar_btn">
                        <i class="ph ph-list-plus"></i>
                        Dividir em seções
                    </button>

                    <div class="section-list" id="secoes-list">
                        <!-- Seções adicionadas pelo JavaScript -->
                    </div>

                    <button type="button" class="btn-outline" id="adicionar_secao">
                        <i class="ph ph-plus"></i>
                        Adicionar seção
                    </button>

                </div>

                <button type="submit" class="btn-primary">
                    Cadastrar aula
                    <i class="ph ph-plus"></i>
                </button>

            </form>

            <script type="application/json" id="estado-inicial"><?= json_encode(
                $estado,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE
            ) ?></script>

        </section>

    </main>

<?php require __DIR__ . '/../includes/scripts.php';
