<?php

require_once __DIR__ . '/../includes/bootstrap.php';

// Nome digitado antes de um erro (ex.: senhas diferentes), usado só uma vez
$nome_anterior = $_SESSION['name_placeholder'] ?? '';
unset($_SESSION['name_placeholder']);

$page_title = 'Cadastro';

require __DIR__ . '/../includes/head.php';

?>

    <button type="button" class="back-btn" onclick="window.location.href = 'index.php'">
        <i class="ph ph-arrow-left"></i>
    </button>

    <form action="../actions/cadastro_action.php" method="POST" class="login-form">
        <h1>Criar Conta</h1>

        <?php exibir_aviso(); ?>

        <div>
            <label for="username">Usuário</label>
            <input type="text" name="username" id="username" placeholder="Usuário" value="<?= e($nome_anterior) ?>" required>
        </div>

        <div>
            <label for="password">Senha</label>
            <input type="password" name="password" id="password" placeholder="Senha" required>
        </div>

        <div>
            <label for="confirmar_senha">Confirmar Senha</label>
            <input type="password" name="confirmar_senha" id="confirmar_senha" placeholder="Confirmar Senha" required>
        </div>

        <button type="submit">Criar Conta</button>

        <span>Já possui conta? <a href="login.php">Faça login</a></span>
    </form>

<?php require __DIR__ . '/../includes/scripts.php';
