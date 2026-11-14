<?php

/**
 * Inicialização comum a todas as páginas e actions.
 * Uso (primeira linha de cada página):
 *     require_once __DIR__ . '/../includes/bootstrap.php';
 *
 * Entrega: sessão iniciada, $logado, $admin e os helpers abaixo.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['user']);
$admin = !empty($_SESSION['admin']);


/* ---------- Texto e arquivos ---------- */

/** Escapa texto para imprimir em HTML. */
function e($valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/** Texto vindo de formulário: só aceita string e remove espaços das pontas. */
function texto_limpo($valor): string
{
    return is_string($valor) ? trim($valor) : '';
}

/**
 * Caminho de um arquivo dentro de assets/ com a data de modificação, para o
 * navegador não usar CSS/JS antigo em cache. Ex.: asset('css/base.css')
 */
function asset(string $caminho): string
{
    $arquivo = __DIR__ . '/../assets/' . $caminho;
    $versao = is_file($arquivo) ? filemtime($arquivo) : time();

    return '../assets/' . $caminho . '?v=' . $versao;
}


/* ---------- Acesso ---------- */

/** Manda para o login quem não estiver logado. */
function exigir_login(): void
{
    if (empty($_SESSION['user']) || empty($_SESSION['id_usuario'])) {
        definir_aviso('Faça login para acessar as provas.');
        header('Location: login.php');
        exit;
    }
}

/** Redireciona para a home quem não for administrador. */
function exigir_admin(): void
{
    global $logado, $admin;

    if (!$logado || !$admin) {
        header('Location: index.php');
        exit;
    }
}


/* ---------- Avisos (mensagens que sobrevivem a um redirect) ---------- */

/** Guarda um aviso na sessão; exibir_aviso() mostra e apaga. */
function definir_aviso(string $texto, string $tipo = 'danger'): void
{
    $_SESSION['aviso'] = $texto;
    $_SESSION['aviso_tipo'] = $tipo === 'success' ? 'success' : 'danger';
}

/** Mostra o aviso da sessão (se houver) e o apaga. */
function exibir_aviso(): void
{
    if (empty($_SESSION['aviso'])) {
        return;
    }

    $tipo = ($_SESSION['aviso_tipo'] ?? 'danger') === 'success' ? 'success' : 'danger';

    // Avisos de sucesso somem sozinhos (assets/js/main.js); erros ficam na tela.
    $autohide = $tipo === 'success' ? ' data-autohide' : '';

    echo '<div class="alerta ' . $tipo . '"' . $autohide . '><p>' . e($_SESSION['aviso']) . '</p></div>';

    unset($_SESSION['aviso'], $_SESSION['aviso_tipo']);
}


/* ---------- CSRF (formulários que alteram dados) ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

/** Campo oculto para colocar dentro de todo <form method="POST">. */
function csrf_campo(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

/** Confere o token enviado no POST. */
function csrf_valido(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && is_string($_POST['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}
