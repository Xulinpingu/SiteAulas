<?php

session_start();

$logado = isset($_SESSION['user']);
$admin = isset($_SESSION['admin']);

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learn AI | Aprenda Inteligência Artificial</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="../assets/imgs/favicon.ico">

    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />
</head>

<body>
    <header class="header">
        <nav class="navbar container">
            <div style="display: flex; width: 100%; justify-content: space-between; align-items: center; padding-right: 15px;">
                <a href="index.php" class="brand">
                    <img src="../assets/imgs/logo.png" alt="Learn AI">
                    <span>Learn<span class="brand-highligth">AI</span></span>
                </a>

                <a href="#aulas" class="nav-link">Explorar aulas</a>
            </div>

            <?php if (!$logado): ?>

                <a href="login.php" class="btn-primary">Login</a>

            <?php else: ?>

                <div class="lesson-counter" onclick="ToggleLogout()" style="cursor: pointer;">
                    <button class="username"><?= $_SESSION['user'] ?>  </button>  

                    <div class="popup-conta hidden">
                        <a href = '../actions/logout_action.php' class="btn-secondary btn-red <?= $admin ? "btn-top" : "" ?>">Logout <i class="ph ph-sign-out"></i></a>

                        <?php if ($admin): ?>
                            <a href="add_aula.php" class="btn-secondary btn-bottom">Criar Aula</a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </nav>
    </header>

    <main>

        <section class="hero container">
            <div class="hero-content">
                <span class="eyebrow">APRENDA NO SEU RITMO</span>

                <h1>
                    Descubra o munda da <span>Inteligência Artificial.</span>
                </h1>

                <p class="hero-description">
                    Entenda como as inteligências artificiais funcionam,
                    conheça seus conceitos fundamentais e descubra como
                    essa tecnologia está transformando o mundo.
                </p>

                <div class="hero-actions">
                    <a href="#aulas" class="btn-primary">começar a aprender <i class="ph ph-arrow-right"></i></a>

                    <span class="hero-info">2 aulas</span>
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
                        <strong>02</strong>
                        <span>aulas disponíveis</span>
                    </div>
                </div>

                <div class="lesson-list">
                    <article class="lesson-card">
                        <div class="lesson-number">01</div>

                        <div class="lesson-content">
                            <span class="lesson-label">AULA 01</span>

                            <h3>Introdução à Inteligência Artificial</h3>

                            <p>
                                Descubra o que é inteligência artificial,
                                sua história e como ela está presente
                                no nosso cotidiano.
                            </p>

                            <div class="lesson-meta">
                                <span>Fundamentos</span>
                                <span>Iniciante</span>
                            </div>

                        </div>

                        <a href="aula1.php" class="btn-secondary">
                            Acessar aula <i class="ph ph-arrow-right"></i>
                        </a>

                    </article>

                    <article class="lesson-card">

                        <div class="lesson-number">02</div>

                        <div class="lesson-content">

                            <span class="lesson-label">AULA 02</span>

                            <h3>Como as Inteligências Artificiais aprendem?</h3>

                            <p>
                                Conheça os conceitos de Machine Learning,
                                redes neurais e o processo de aprendizagem
                                das máquinas.
                            </p>

                            <div class="lesson-meta">
                                <span>Machine Learning</span>
                                <span>Iniciante</span>
                            </div>

                        </div>

                        <a href="aula2.php" class="btn-secondary">
                            Acessar aula <i class="ph ph-arrow-right"></i>
                        </a>

                    </article>

                </div>

            </div>

        </section>

        <section class="evaluation container">

            <div class="evaluation-content">

                <span class="eyebrow">ETAPA FINAL</span>

                <h2>
                    Está preparado para testar seus conhecimentos?
                </h2>

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

    <footer class="footer">

        <div class="container footer-content">

            <a href="index.php" class="brand">
                <img src="../assets/imgs/logo.png" alt="">
                <span>Learn<span class="brand-highlight">AI</span></span>
            </a>

            <p>
                Aprenda hoje. Construa o amanhã.
            </p>

            <span class="copyright">
                © 2026 Learn AI
            </span>

        </div>

    </footer>
</body>

<script>

    const logout = document.querySelector(".popup-conta");

    function ToggleLogout(){
        logout.classList.toggle('hidden');
    }

</script>

</html>