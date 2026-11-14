<?php

/**
 * Fim de TODAS as páginas: scripts + fechamento de </body> e </html>.
 *
 * Variáveis opcionais, definidas antes do include:
 *   $markdown  true carrega marked, DOMPurify, KaTeX e assets/js/markdown.js
 *   $page_js   JS da página, sem extensão. Ex.: ['add_aula'] carrega assets/js/add_aula.js
 */

$markdown = $markdown ?? false;
$page_js = $page_js ?? [];

?>
    <?php if ($markdown): ?>
        <script src="https://cdn.jsdelivr.net/npm/marked@12.0.2/marked.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.6/dist/purify.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/katex.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/katex@0.16.11/dist/contrib/auto-render.min.js"></script>
        <script src="<?= e(asset('js/markdown.js')) ?>"></script>
    <?php endif; ?>

    <script src="<?= e(asset('js/main.js')) ?>"></script>

    <?php foreach ($page_js as $arquivo_js): ?>
        <script src="<?= e(asset('js/' . $arquivo_js . '.js')) ?>"></script>
    <?php endforeach; ?>

</body>

</html>
