<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';
require_once __DIR__ . '/../includes/aulas.php';

$aulas = [];
$tags_por_aula = [];
$erro_aulas = false;

try {
    [$aulas, $tags_por_aula] = listar_aulas($pdo);
} catch (PDOException $e) {
    error_log($e->getMessage());
    $erro_aulas = true;
}

$total_aulas = count($aulas);

$page_title = 'Learn AI | Aprenda Inteligência Artificial';

require __DIR__ . '/../includes/head.php';
require __DIR__ . '/../includes/header.php';

?>

    <main>

        <section class="hero container">
            <div class="hero-content">
                <span class="eyebrow">APRENDA NO SEU RITMO</span>

                <h1>
                    Descubra o mundo da <span>Inteligência Artificial.</span>
                </h1>

                <p class="hero-description">
                    Entenda como as inteligências artificiais funcionam,
                    conheça seus conceitos fundamentais e descubra como
                    essa tecnologia está transformando o mundo.
                </p>

                <div class="hero-actions">
                    <a href="#aulas" class="btn-primary">começar a aprender <i class="ph ph-arrow-right"></i></a>

                    <span class="hero-info"><?= $total_aulas ?> <?= $total_aulas === 1 ? 'aula' : 'aulas' ?></span>
                </div>

            </div>

            <div class="hero-visual">
                <div class="visual-glow"></div>

                <img src="../assets/imgs/logo.png" alt="" class="hero-logo">

                <div class="visual-caption">
                    <span class="status-dot"></span>
                    <span>Seu aprendizado começa aqui</span>
                </div>
            </div>

        </section>

        <section class="about container">
            <div class="section-heading">
                <span class="eyebrow">SOBRE O CURSO</span>

                <h2>Conhecimento que vai além da teoria.</h2>

                <p>
                    Uma introdução aos principais conceitos da inteligência
                    artificial, organizada para facilitar seu aprendizado.
                </p>
            </div>

            <div class="features">
                <article class="feature-card">
                    <div class="feature-icon">01</div>
                    <h3>Aprenda</h3>
                    <p>
                        Explore conteúdos organizados em aulas
                        objetivas e fáceis de acompanhar.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">02</div>
                    <h3>Acompanhe</h3>
                    <p>
                        Marque as aulas concluídas e acompanhe
                        seu progresso durante o curso.
                    </p>
                </article>

                <article class="feature-card">
                    <div class="feature-icon">03</div>
                    <h3>Pratique</h3>
                    <p>
                        Teste seus conhecimentos através de
                        uma avaliação ao final do aprendizado.
                    </p>
                </article>
            </div>

        </section>

        <section class="lessons-section" id="aulas">
            <div class="container">

                <div class="section-heading lessons-heading">
                    <div>
                        <span class="eyebrow">TRILHA DE APRENDIZADO</span>

                        <h2>Suas aulas</h2>

                        <p>Siga a sequência e avance no seu aprendizado</p>
                    </div>

                    <div class="lesson-counter">
                        <strong><?= str_pad($total_aulas, 2, '0', STR_PAD_LEFT) ?></strong>
                        <span><?= $total_aulas === 1 ? 'aula disponível' : 'aulas disponíveis' ?></span>
                    </div>
                </div>

                <div class="lesson-list">

                    <?php if ($erro_aulas): ?>

                        <div class="lesson-empty">
                            <h3>Não foi possível carregar as aulas</h3>
                            <p>Ocorreu um erro ao buscar as aulas. Tente novamente em instantes.</p>
                        </div>

                    <?php elseif (empty($aulas)): ?>

                        <div class="lesson-empty">
                            <h3>Nenhuma aula cadastrada ainda</h3>
                            <p>As aulas aparecerão aqui assim que forem publicadas.</p>

                            <?php if ($admin): ?>
                                <a href="add_aula.php" class="btn-primary">
                                    Criar a primeira aula <i class="ph ph-plus"></i>
                                </a>
                            <?php endif; ?>
                        </div>

                    <?php else: ?>

                        <?php foreach ($aulas as $i => $aula): ?>

                            <?php $numero = str_pad($aula['num_aula'], 2, '0', STR_PAD_LEFT); ?>

                            <article class="lesson-card" style="--i: <?= (int) $i ?>">

                                <div class="lesson-number"><?= e($numero) ?></div>

                                <div class="lesson-content">
                                    <span class="lesson-label">AULA <?= e($numero) ?></span>

                                    <h3><?= e($aula['title']) ?></h3>

                                    <p><?= e($aula['descricao']) ?></p>

                                    <?php if (!empty($tags_por_aula[$aula['id_card']])): ?>
                                        <div class="lesson-meta">
                                            <?php foreach ($tags_por_aula[$aula['id_card']] as $tag): ?>
                                                <span><?= e($tag) ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <a href="aula.php?id=<?= (int) $aula['id_card'] ?>" class="btn-secondary">
                                    Acessar aula <i class="ph ph-arrow-right"></i>
                                </a>

                            </article>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </div>

        </section>

        <section class="evaluation container">

            <div class="evaluation-content">

                <span class="eyebrow">ETAPA FINAL</span>

                <h2>Está preparado para testar seus conhecimentos?</h2>

                <p>
                    Depois de estudar as aulas, faça a avaliação
                    e descubra quanto você aprendeu.
                </p>

                <a href="prova.php" class="btn-primary">
                    Acessar avaliação <i class="ph ph-arrow-right"></i>
                </a>

            </div>

            <div class="evaluation-decoration">
                <div class="orbit orbit-one"></div>
                <div class="orbit orbit-two"></div>

                <div class="tech-symbol">
                    <span>AI</span>
                </div>

                <span class="tech-dot dot-one"></span>
                <span class="tech-dot dot-two"></span>
                <span class="tech-dot dot-three"></span>
            </div>
        </section>

    </main>

<?php

require __DIR__ . '/../includes/footer.php';
require __DIR__ . '/../includes/scripts.php';
