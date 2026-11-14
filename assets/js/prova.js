/**
 * Tela da prova (includes/views/prova_questoes.php): barra de progresso com a
 * quantidade de questões respondidas e proteção contra envio duplicado.
 */
(function () {
    const form = document.querySelector('.prova-form');
    if (!form) return;

    const total = Number(form.dataset.total);
    const texto = document.querySelector('[data-progresso-texto]');
    const barra = document.querySelector('.prova-progresso .prova-barra span');
    const botao = form.querySelector('button[type="submit"]');

    function atualizar() {
        const nomes = new Set(
            [...form.querySelectorAll('input[type="radio"]:checked')].map((radio) => radio.name)
        );
        const respondidas = nomes.size;

        texto.textContent = respondidas + ' de ' + total + ' respondidas';
        barra.style.width = (total ? (respondidas / total) * 100 : 0) + '%';
    }

    form.addEventListener('change', atualizar);

    form.addEventListener('submit', () => {
        if (botao) botao.disabled = true;
    });

    atualizar();
})();
