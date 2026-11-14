/**
 * Renderiza Markdown (com fórmulas KaTeX) no navegador.
 *
 * Uso no HTML (o texto puro fica como fallback caso as bibliotecas não carreguem):
 *   <div class="markdown" data-markdown><pre>texto **em** markdown</pre></div>
 *   <span data-markdown-inline>texto curto com $x^2$</span>
 *
 * Depende de marked, DOMPurify, KaTeX e auto-render (includes/scripts.php).
 */
(function () {
    function escapar(texto) {
        return texto.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    // Markdown -> HTML sem estragar as fórmulas ($...$ e $$...$$), que o Markdown
    // confundiria com itálico por causa dos "_" (ex.: w_1, w_2).
    function renderizar(origem, emLinha) {
        const codigos = [];
        const formulas = [];

        const texto = origem
            .replace(/```[\s\S]*?```/g, (m) => '@@C' + (codigos.push(m) - 1) + '@@')
            .replace(/`[^`\n]+`/g, (m) => '@@C' + (codigos.push(m) - 1) + '@@')
            .replace(/\$\$[\s\S]+?\$\$/g, (m) => '@@F' + (formulas.push(m) - 1) + '@@')
            .replace(/\$[^$\n]+?\$/g, (m) => '@@F' + (formulas.push(m) - 1) + '@@')
            .replace(/@@C(\d+)@@/g, (_, i) => codigos[i]);

        const html = emLinha
            ? marked.parseInline(texto, { gfm: true })
            : marked.parse(texto, { gfm: true });

        return DOMPurify.sanitize(html).replace(/@@F(\d+)@@/g, (_, i) => escapar(formulas[i]));
    }

    function aplicar(raiz) {
        // Sem as bibliotecas (ex.: sem internet), o texto puro continua visível.
        if (!window.marked || !window.DOMPurify) return;

        const alvos = (raiz || document).querySelectorAll('[data-markdown], [data-markdown-inline]');

        alvos.forEach((elemento) => {
            const emLinha = elemento.hasAttribute('data-markdown-inline');

            elemento.innerHTML = renderizar(elemento.textContent, emLinha);

            if (window.renderMathInElement) {
                window.renderMathInElement(elemento, {
                    delimiters: [
                        { left: '$$', right: '$$', display: true },
                        { left: '$', right: '$', display: false }
                    ],
                    throwOnError: false
                });
            }
        });
    }

    window.Markdown = { renderizar, aplicar };

    aplicar();
})();
