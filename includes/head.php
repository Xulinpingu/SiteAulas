<?php

/**
 * Abre o documento: <!DOCTYPE>, <head> completo e <body>.
 * O fechamento (</body></html>) fica em includes/scripts.php.
 *
 * Variáveis opcionais, definidas ANTES do include:
 *   $page_title  título da aba
 *   $body_class  classe do <body>
 *   $markdown    true se a página mostra Markdown/fórmulas (carrega o CSS do KaTeX)
 *
 * Todo o CSS do site fica em assets/css/style.css.
 */

$page_title = $page_title ?? 'Learn AI';
$body_class = $body_class ?? '';
$markdown = $markdown ?? false;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?></title>

    <link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="icon" type="image/png" href="../assets/imgs/favicon.ico">

    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />

    <?php if ($markdown): ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.css">
    <?php endif; ?>
</head>

<body<?= $body_class !== '' ? ' class="' . e($body_class) . '"' : '' ?>>
