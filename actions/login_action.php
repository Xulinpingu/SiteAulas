<?php

session_start();

require_once __DIR__ . '/../config/connDB.php';

$user = $_POST['username'] ?? '';
$senha = $_POST['password'] ?? '';

$sql = "SELECT senha FROM usuarios 
        WHERE nome = :nome";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome' => $user
]);
$result = $stmt->fetch(PDO::FETCH_COLUMN);

if ($result && password_verify($senha, $result)) {
    $_SESSION['user'] = $user;
    header("Location: ../pages/index.php");

} else {
    $_SESSION['aviso'] = "Usuário ou senha incorretos.";
    $_SESSION['aviso_tipo'] = "danger";
    
    header("Location: ../pages/login.php");
}