/**
 * Comportamentos comuns a todas as páginas (carregado por includes/scripts.php).
 */
(function () {
    // Menu do usuário (botão com o nome no header)
    const popupConta = document.querySelector('.popup-conta');

    window.ToggleLogout = function () {
        if (popupConta) popupConta.classList.toggle('hidden');
    };

    // Avisos de sucesso (.alerta[data-autohide]) somem sozinhos depois de 2 segundos
    document.querySelectorAll('.alerta[data-autohide]').forEach((aviso) => {
        setTimeout(() => {
            aviso.style.maxHeight = aviso.offsetHeight + 'px';
            aviso.getBoundingClientRect(); // fixa a altura antes da animação de saída
            aviso.classList.add('alerta--saindo');
            setTimeout(() => aviso.remove(), 500);
        }, 2000);
    });

    // Formulários com data-confirmar="mensagem" pedem confirmação antes de enviar
    document.querySelectorAll('form[data-confirmar]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!confirm(form.dataset.confirmar)) event.preventDefault();
        });
    });
})();
