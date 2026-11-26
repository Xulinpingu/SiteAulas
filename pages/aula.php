<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';
require_once __DIR__ . '/../includes/aulas.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$aula = null;
$erro_banco = false;

if ($id) {
    try {
        $aula = buscar_aula($pdo, $id);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        $erro_banco = true;
    }
}

if (!$aula && !$erro_banco) {
    http_response_code(404);
}

$page_title = $aula ? $aula['title'] . ' | Learn AI' : 'Aula não encontrada | Learn AI';
$markdown = true;

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';

?>

    <main class="lesson-page">

        <?php exibir_aviso(); ?>

        <?php if ($erro_banco): ?>

            <div class="lesson-empty">
                <h3>Não foi possível carregar a aula</h3>
                <p>Ocorreu um erro ao buscar a aula. Tente novamente em instantes.</p>
                <a href="index.php#aulas" class="btn-secondary">Voltar às aulas</a>
            </div>

        <?php elseif (!$aula): ?>

            <div class="lesson-empty">
                <h3>Aula não encontrada</h3>
                <p>Essa aula não existe ou foi removida.</p>
                <a href="index.php#aulas" class="btn-secondary">Voltar às aulas</a>
            </div>

        <?php else: ?>

            <a href="index.php#aulas" class="btn-secondary lesson-back">
                <i class="ph ph-arrow-left"></i> Voltar às aulas
            </a>

            <header class="lesson-page-header">
                <span class="eyebrow">AULA <?= e(str_pad($aula['num_aula'], 2, '0', STR_PAD_LEFT)) ?></span>

                <h1><?= e($aula['title']) ?></h1>

                <p><?= e($aula['descricao']) ?></p>

                <?php if (!empty($aula['tags'])): ?>
                    <div class="lesson-meta">
                        <?php foreach ($aula['tags'] as $tag): ?>
                            <span><?= e($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if ($aula['referencia'] !== ''): ?>
                    <a href="<?= e($aula['referencia']) ?>" class="lesson-ref" target="_blank" rel="noopener noreferrer">
                        <i class="ph ph-link"></i> Material de referência
                    </a>
                <?php endif; ?>
            </header>

            <?php if (empty($aula['secoes'])): ?>

                <div class="lesson-empty">
                    <h3>Conteúdo em breve</h3>
                    <p>O conteúdo desta aula ainda não foi cadastrado.</p>
                </div>

            <?php else: ?>

                <div class="lesson-article">
                    <?php foreach ($aula['secoes'] as $secao): ?>
                        <section class="lesson-section">
                            <h2><?= e($secao['titulo']) ?></h2>

                            <?php /* O <pre> é o texto puro (fallback sem JavaScript); assets/js/markdown.js o troca pelo Markdown renderizado. */ ?>
                            <div class="markdown" data-markdown><pre><?= e($secao['conteudo']) ?></pre></div>
                        </section>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <?php if ($aula['total_perguntas'] > 0): ?>
                <div class="lesson-quiz">
                    <div class="lesson-quiz-texto">
                        <strong>Teste o que você aprendeu</strong>
                        <span>
                            Quiz com <?= $aula['total_perguntas'] ?>
                            <?= $aula['total_perguntas'] === 1 ? 'pergunta' : 'perguntas' ?> desta aula
                        </span>
                    </div>

                    <a href="prova.php?aula=<?= (int) $aula['id_card'] ?>" class="btn-primary">
                        Fazer o quiz <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($aula['anterior'] || $aula['proxima']): ?>
                <nav class="lesson-nav" aria-label="Navegação entre aulas">

                    <?php if ($aula['anterior']): ?>
                        <a href="aula.php?id=<?= (int) $aula['anterior']['id_card'] ?>" class="lesson-nav-link">
                            <span class="lesson-nav-label"><i class="ph ph-arrow-left"></i> Aula anterior</span>
                            <span class="lesson-nav-title"><?= e($aula['anterior']['title']) ?></span>
                        </a>
                    <?php endif; ?>

                    <?php if ($aula['proxima']): ?>
                        <a href="aula.php?id=<?= (int) $aula['proxima']['id_card'] ?>" class="lesson-nav-link lesson-nav-next">
                            <span class="lesson-nav-label">Próxima aula <i class="ph ph-arrow-right"></i></span>
                            <span class="lesson-nav-title"><?= e($aula['proxima']['title']) ?></span>
                        </a>
                    <?php endif; ?>

                </nav>
            <?php endif; ?>

        <?php endif; ?>

    </main>

<?php

require __DIR__ . '/../includes/footer.php';
require __DIR__ . '/../includes/scripts.php';
