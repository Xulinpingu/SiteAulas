<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/login.php");
    exit;
}

$user = texto_limpo($_POST['username'] ?? '');
$senha = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';

$stmt = $pdo->prepare("SELECT id_usuario, senha FROM usuarios WHERE nome = :nome");
$stmt->execute([':nome' => $user]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && password_verify($senha, $result['senha'])) {
    // Novo id de sessão a cada login (evita fixação de sessão)
    session_regenerate_id(true);

    $_SESSION['user'] = $user;
    $_SESSION['id_usuario'] = (int) $result['id_usuario'];

    // O usuário de id 1 é o administrador
    if ((int) $result['id_usuario'] === 1) {
        $_SESSION['admin'] = true;
    }

    header("Location: ../pages/index.php");
    exit;
}

definir_aviso("Usuário ou senha incorretos.");

header("Location: ../pages/login.php");
exit;
