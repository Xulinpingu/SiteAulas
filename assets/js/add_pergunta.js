/**
 * Página "Adicionar pergunta" (pages/add_pergunta.php): editor das alternativas.
 * Cada alternativa tem uma letra (que também marca a correta), o texto e um
 * botão de remover. Mínimo e máximo vêm do PHP (data-* no <form class="admin-form">).
 */
(function () {
    const form = document.querySelector('.admin-form[data-min-alternativas]');
    if (!form) return;

    const minimo = Number(form.dataset.minAlternativas);
    const maximo = Number(form.dataset.maxAlternativas);
    const limiteTexto = Number(form.dataset.limiteAlternativa);
    const estadoInicial = JSON.parse(document.getElementById('estado-inicial').textContent);

    const lista = document.querySelector('#alternativas-list');
    const botaoAdicionar = document.querySelector('#adicionar_alternativa');

    const letra = (i) => String.fromCharCode(65 + i);

    function renumerar() {
        const itens = [...lista.children];

        itens.forEach((item, i) => {
            item.querySelector('.alt-letra').textContent = letra(i);
            item.querySelector('input[type="radio"]').value = String(i);
            item.querySelector('input[type="text"]').placeholder = 'Alternativa ' + letra(i);
            item.querySelector('[data-acao="remover"]').disabled = itens.length <= minimo;
        });

        // Se a alternativa marcada foi removida, marca a primeira
        if (itens.length && !lista.querySelector('input[type="radio"]:checked')) {
            itens[0].querySelector('input[type="radio"]').checked = true;
        }

        botaoAdicionar.disabled = itens.length >= maximo;
    }

    function criarAlternativa(texto, marcada) {
        const item = document.createElement('div');
        item.className = 'alt-item';

        const rotuloCorreta = document.createElement('label');
        rotuloCorreta.className = 'alt-correta';
        rotuloCorreta.title = 'Marcar como a alternativa correta';

        const radio = document.createElement('input');
        radio.type = 'radio';
        radio.name = 'correta';
        radio.required = true;
        radio.checked = Boolean(marcada);

        const marca = document.createElement('span');
        marca.className = 'alt-letra';

        rotuloCorreta.append(radio, marca);

        const campo = document.createElement('input');
        campo.type = 'text';
        campo.name = 'alternativas[]';
        campo.maxLength = limiteTexto;
        campo.required = true;
        campo.value = texto || '';

        const remover = document.createElement('button');
        remover.type = 'button';
        remover.className = 'icon-btn danger';
        remover.dataset.acao = 'remover';
        remover.title = 'Remover alternativa';
        remover.setAttribute('aria-label', 'Remover alternativa');
        remover.innerHTML = '<i class="ph ph-trash"></i>';
        remover.addEventListener('click', () => {
            item.remove();
            renumerar();
        });

        item.append(rotuloCorreta, campo, remover);
        lista.appendChild(item);
        renumerar();

        return item;
    }

    botaoAdicionar.addEventListener('click', () => {
        criarAlternativa().querySelector('input[type="text"]').focus();
    });

    // Estado inicial: o que foi digitado (se o envio voltou com erro) ou 4 alternativas vazias
    const alternativas = estadoInicial.alternativas.length
        ? estadoInicial.alternativas
        : ['', '', '', ''];

    alternativas.forEach((texto, i) => criarAlternativa(texto, i === estadoInicial.correta));
})();
