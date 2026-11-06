<?php

session_start();

require_once __DIR__ . '/../config/connDB.php';

$user = $_POST['username'] ?? '';
$senha = $_POST['password'] ?? '';

$sql = "SELECT senha, id_usuario FROM usuarios 
        WHERE nome = :nome";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome' => $user
]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result && password_verify($senha, $result["senha"])) {
    $_SESSION['user'] = $user;
    if ($result["id_usuario"] == 1){
        $_SESSION['admin'] = true;
    }

    header("Location: ../pages/index.php");

} else {
    $_SESSION['aviso'] = "Usuário ou senha incorretos.";
    $_SESSION['aviso_tipo'] = "danger";
    
    header("Location: ../pages/login.php");
}