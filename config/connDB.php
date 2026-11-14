<?php

// Conexão com o banco de dados (MySQL/MariaDB via PDO).
// Ajuste usuário e senha conforme o seu ambiente.

$dsn = "mysql:host=localhost;dbname=site_aulas;charset=utf8mb4";
$usuario = "root";
$senha = "";

try {
    $pdo = new PDO($dsn, $usuario, $senha, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // erros viram exceções (o código usa try/catch)
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Erro ao conectar ao banco de dados.");
}
