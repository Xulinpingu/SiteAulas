<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../pages/cadastro.php");
    exit;
}

$user = texto_limpo($_POST['username'] ?? '');
$senha = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
$confirmar_senha = is_string($_POST['confirmar_senha'] ?? null) ? $_POST['confirmar_senha'] : '';

/** Volta ao formulário com uma mensagem, mantendo o nome digitado. */
function voltar_com_erro(string $mensagem, string $user): void
{
    definir_aviso($mensagem);
    $_SESSION['name_placeholder'] = $user;

    header("Location: ../pages/cadastro.php");
    exit;
}

if ($user === '' || $senha === '') {
    voltar_com_erro("Preencha o usuário e a senha.", $user);
}

if (mb_strlen($user, 'UTF-8') > 100) {
    voltar_com_erro("O usuário deve ter no máximo 100 caracteres.", $user);
}

if ($senha !== $confirmar_senha) {
    voltar_com_erro("As senhas não coincidem.", $user);
}

$stmt = $pdo->prepare("SELECT 1 FROM usuarios WHERE nome = :nome LIMIT 1");
$stmt->execute([':nome' => $user]);

if ($stmt->fetchColumn()) {
    voltar_com_erro("Esse nome de usuário já está em uso.", $user);
}

$stmt = $pdo->prepare("INSERT INTO usuarios (nome, senha) VALUES (:nome, :senha)");
$stmt->execute([
    ':nome' => $user,
    ':senha' => password_hash($senha, PASSWORD_DEFAULT),
]);

definir_aviso("Cadastro realizado com sucesso.", "success");

header("Location: ../pages/login.php");
exit;
