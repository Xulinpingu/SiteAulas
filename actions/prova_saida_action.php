<?php

/**
 * Registra (acao=saida) ou consulta (acao=estado) as saídas de aba de uma prova
 * em andamento. Chamado por assets/js/prova.js; responde sempre em JSON.
 */

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/provas.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['id_usuario']) || !csrf_valido()) {
    http_response_code(403);
    echo json_encode(['erro' => true]);
    exit;
}

$token = texto_limpo($_POST['token'] ?? '');
$acao = texto_limpo($_POST['acao'] ?? 'saida');

$estado = $acao === 'estado'
    ? estado_prova_ativa($token)
    : registrar_saida_prova($token);

if ($estado === null) {
    http_response_code(404);
    echo json_encode(['expirada' => true]);
    exit;
}

echo json_encode($estado);
