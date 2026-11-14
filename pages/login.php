<?php

require_once __DIR__ . '/../includes/bootstrap.php';

$page_title = 'Login';

require __DIR__ . '/../includes/head.php';

?>

    <button type="button" class="back-btn" onclick="window.location.href = 'index.php'">
        <i class="ph ph-arrow-left"></i>
    </button>

    <form action="../actions/login_action.php" method="POST" class="login-form">
        <h1>Login</h1>

        <?php exibir_aviso(); ?>

        <div>
            <label for="username">Usuário</label>
            <input type="text" name="username" id="username" placeholder="Usuário" required>
        </div>

        <div>
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Senha" required>
        </div>

        <button type="submit">Entrar</button>

        <span>Não possui conta? <a href="cadastro.php">Cadastre-se</a></span>
    </form>

<?php require __DIR__ . '/../includes/scripts.php';
