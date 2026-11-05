<?php

session_start();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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
    
    <button type="button" class="back-btn" onclick="window.location.href = 'index.php'">
        <i class="ph ph-arrow-left"></i>
    </button>

    <form action="../actions/login_action.php" method="POST" class="login-form">
        <h1>Login</h1>

        <?php if (isset($_SESSION['aviso'])): ?>
            <div class="alerta <?= $_SESSION['aviso_tipo'] ?>">
                <?php 
                    echo "<p>" . $_SESSION['aviso'] . "</p>"; 
                    // Limpa o aviso da sessão para sumir no próximo refresh
                    unset($_SESSION['aviso']);
                    unset($_SESSION['aviso_tipo']);
                ?>
            </div>
        <?php endif; ?>

        <div>
            <label for="username">Usuário</label>
            <input type="text" name="username" placeholder="Usuário" required>
        </div>

        <div>
            <label for="password">Senha</label>
            <input type="password" name="password" placeholder="Senha" required>
        </div>

        <button type="submit">Entrar</button>

        <span>Não possui conta? <a href="cadastro.php">Cadastre-se</a></span>

    </form>

</body>