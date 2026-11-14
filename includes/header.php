<?php

/**
 * Cabeçalho do site (logo, links de navegação e menu do usuário).
 * Requer includes/bootstrap.php (usa $logado e $admin).
 */

$pagina_atual = basename($_SERVER['SCRIPT_NAME']);

// Na home os links ficam na ponta oposta ao logo; nas outras páginas ficam juntos.
$navbar_class = $pagina_atual === 'index.php'
    ? 'navbar-group navbar-group--spread'
    : 'navbar-group';

// Itens do menu do usuário: Logout e, para o admin, os links de cadastro
// (sem o link da página em que ele já está).
$itens_menu = [
    ['href' => '../actions/logout_action.php', 'rotulo' => 'Logout <i class="ph ph-sign-out"></i>', 'classe' => 'btn-red'],
];

if ($admin) {
    $links_admin = [
        'add_aula.php' => 'Criar Aula',
        'add_pergunta.php' => 'Criar Pergunta',
    ];

    unset($links_admin[$pagina_atual]);

    foreach ($links_admin as $arquivo => $rotulo) {
        $itens_menu[] = ['href' => $arquivo, 'rotulo' => e($rotulo), 'classe' => ''];
    }
}

$ultimo_item = count($itens_menu) - 1;

?>
    <header class="header">
        <nav class="navbar container">

            <div class="<?= $navbar_class ?>">
                <a href="index.php" class="brand">
                    <img src="../assets/imgs/logo.png" alt="Learn AI">
                    <span>Learn<span class="brand-highlight">AI</span></span>
                </a>

                <div class="nav-links">
                    <a href="index.php#aulas" class="nav-link">Explorar aulas</a>
                    <a href="prova.php" class="nav-link">Provas</a>
                </div>
            </div>

            <?php if (!$logado): ?>

                <a href="login.php" class="btn-primary">Login</a>

            <?php else: ?>

                <div class="lesson-counter user-menu" onclick="ToggleLogout()">
                    <button class="username"><?= e($_SESSION['user']) ?></button>

                    <div class="popup-conta hidden">
                        <?php foreach ($itens_menu as $i => $item): ?>
                            <?php
                            $posicao = '';

                            if ($ultimo_item > 0) {
                                $posicao = $i === 0 ? 'btn-top' : ($i === $ultimo_item ? 'btn-bottom' : 'btn-mid');
                            }
                            ?>
                            <a href="<?= e($item['href']) ?>" class="btn-secondary <?= $item['classe'] ?> <?= $posicao ?>"><?= $item['rotulo'] ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>

            <?php endif; ?>

        </nav>
    </header>
