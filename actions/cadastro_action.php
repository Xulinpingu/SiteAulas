<?php

session_start();

require_once __DIR__ . '/../config/connDB.php';

$user = $_POST['username'] ?? '';
$senha = $_POST['password'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

if ($senha !== $confirmar_senha) {
    $_SESSION['aviso'] = "As senhas não coincidem.";
    $_SESSION['aviso_tipo'] = "danger";

    $_SESSION['name_placeholder'] = $user;

    header("Location: ../pages/cadastro.php");
    exit();
}

$senha = password_hash($senha, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuarios(nome, senha) VALUES 
        (:nome, :senha)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':nome' => $user,
    ':senha' => $senha
]);

$_SESSION['aviso'] = "Cadastro realizado com sucesso.";
$_SESSION['aviso_tipo'] = "success";

header ("Location: ../pages/login.php");