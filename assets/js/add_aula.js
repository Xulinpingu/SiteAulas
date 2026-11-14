/**
 * Página "Adicionar aula" (pages/add_aula.php): tags, seções do conteúdo e
 * importação de uma apostila em Markdown.
 *
 * Configuração vem do PHP:
 *   - data-limite-* no <form class="admin-form">
 *   - estado inicial (quando o envio volta com erro) em <script id="estado-inicial">
 */
(function () {
    const form = document.querySelector('.admin-form');
    const config = {
        limiteTitulo: Number(form.dataset.limiteTitulo),
        limiteTag: Number(form.dataset.limiteTag),
        limiteSubtitulo: Number(form.dataset.limiteSubtitulo),
        limiteConteudo: Number(form.dataset.limiteConteudo)
    };
    const estadoInicial = JSON.parse(document.getElementById('estado-inicial').textContent);

    /* ---------- Tags ---------- */

    const campoTag = document.querySelector('#nova_tag');
    const botaoAdicionarTag = document.querySelector('#adicionar_tag');
    const listaTags = document.querySelector('#tags-list');
    const tagsAdicionadas = new Set();

    function adicionarTag(nome) {
        const nomeTag = nome.trim();

        if (nomeTag === '') return;

        if (nomeTag.length > config.limiteTag) {
            alert('A tag deve ter no máximo ' + config.limiteTag + ' caracteres.');
            return;
        }

        const normalizada = nomeTag.toLocaleLowerCase('pt-BR');

        // Impede tags duplicadas
        if (tagsAdicionadas.has(normalizada)) return;

        tagsAdicionadas.add(normalizada);

        const tag = document.createElement('div');
        tag.classList.add('tag-option');

        const texto = document.createElement('span');
        texto.textContent = nomeTag;

        // Campo enviado pelo formulário
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'tags[]';
        input.value = nomeTag;

        const remover = document.createElement('button');
        remover.type = 'button';
        remover.textContent = '×';
        remover.setAttribute('aria-label', 'Remover tag ' + nomeTag);
        remover.addEventListener('click', () => {
            tagsAdicionadas.delete(normalizada);
            tag.remove();
        });

        tag.append(texto, input, remover);
        listaTags.appendChild(tag);
    }

    function adicionarTagDoCampo() {
        adicionarTag(campoTag.value);
        campoTag.value = '';
        campoTag.focus();
    }

    botaoAdicionarTag.addEventListener('click', adicionarTagDoCampo);

    campoTag.addEventListener('keydown', (event) => {
        if (event.key === 'Enter') {
            event.preventDefault();
            adicionarTagDoCampo();
        }
    });

    /* ---------- Seções do conteúdo ---------- */

    const listaSecoes = document.querySelector('#secoes-list');

    function criarBotaoIcone(icone, rotulo, classeExtra) {
        const botao = document.createElement('button');
        botao.type = 'button';
        botao.className = ('icon-btn ' + (classeExtra || '')).trim();
        botao.setAttribute('aria-label', rotulo);
        botao.title = rotulo;
        botao.innerHTML = '<i class="ph ' + icone + '"></i>';
        return botao;
    }

    function renumerarSecoes() {
        const itens = [...listaSecoes.children];

        itens.forEach((item, i) => {
            item.querySelector('.section-number').textContent = i + 1;
            item.querySelector('[data-acao="subir"]').disabled = i === 0;
            item.querySelector('[data-acao="descer"]').disabled = i === itens.length - 1;
        });
    }

    function criarSecao(titulo, conteudo) {
        const item = document.createElement('div');
        item.className = 'section-item';

        const cabecalho = document.createElement('div');
        cabecalho.className = 'section-item-header';

        const numero = document.createElement('span');
        numero.className = 'section-number';

        const campoTitulo = document.createElement('input');
        campoTitulo.type = 'text';
        campoTitulo.name = 'subtitulos[]';
        campoTitulo.maxLength = config.limiteSubtitulo;
        campoTitulo.placeholder = 'Subtítulo da seção';
        campoTitulo.value = titulo || '';

        const acoes = document.createElement('div');
        acoes.className = 'section-actions';

        const subir = criarBotaoIcone('ph-arrow-up', 'Subir seção');
        subir.dataset.acao = 'subir';
        subir.addEventListener('click', () => {
            if (item.previousElementSibling) {
                listaSecoes.insertBefore(item, item.previousElementSibling);
                renumerarSecoes();
            }
        });

        const descer = criarBotaoIcone('ph-arrow-down', 'Descer seção');
        descer.dataset.acao = 'descer';
        descer.addEventListener('click', () => {
            if (item.nextElementSibling) {
                listaSecoes.insertBefore(item.nextElementSibling, item);
                renumerarSecoes();
            }
        });

        const excluir = criarBotaoIcone('ph-trash', 'Remover seção', 'danger');
        excluir.addEventListener('click', () => {
            item.remove();
            renumerarSecoes();
        });

        acoes.append(subir, descer, excluir);
        cabecalho.append(numero, campoTitulo, acoes);

        const area = document.createElement('textarea');
        area.name = 'conteudos[]';
        area.className = 'code-area';
        area.maxLength = config.limiteConteudo;
        area.placeholder = 'Conteúdo da seção (Markdown)';
        area.value = conteudo || '';

        item.append(cabecalho, area);
        listaSecoes.appendChild(item);
        renumerarSecoes();

        return item;
    }

    document.querySelector('#adicionar_secao').addEventListener('click', () => {
        criarSecao().querySelector('input').focus();
    });

    /* ---------- Importar Markdown ---------- */

    // Divide a apostila em seções a cada título "## ". O texto antes do primeiro
    // "##" (depois do "# Título") vira a seção "Introdução". Ignora "##" dentro
    // de blocos de código.
    function dividirMarkdown(markdown) {
        const linhas = markdown.replace(/\r\n?/g, '\n').split('\n');
        const secoes = [];
        let atual = { titulo: 'Introdução', linhas: [] };
        let h1 = null;
        let emCodigo = false;

        for (const linha of linhas) {
            if (/^\s*```/.test(linha)) emCodigo = !emCodigo;

            const h2 = emCodigo ? null : linha.match(/^##\s+(.+?)\s*#*\s*$/);
            const titulo1 = emCodigo ? null : linha.match(/^#\s+(.+?)\s*#*\s*$/);

            if (h2) {
                secoes.push(atual);
                atual = { titulo: h2[1].replace(/[`*]/g, ''), linhas: [] };
            } else if (titulo1 && h1 === null && secoes.length === 0) {
                h1 = titulo1[1].replace(/[`*]/g, '');
            } else {
                atual.linhas.push(linha);
            }
        }

        secoes.push(atual);

        return {
            h1,
            secoes: secoes
                .map((s) => ({
                    titulo: s.titulo.slice(0, config.limiteSubtitulo),
                    conteudo: s.linhas.join('\n').trim()
                }))
                .filter((s) => s.conteudo !== '')
        };
    }

    const campoMarkdown = document.querySelector('#importar_md');

    document.querySelector('#importar_btn').addEventListener('click', () => {
        const markdown = campoMarkdown.value.trim();

        if (markdown === '') return;

        const resultado = dividirMarkdown(markdown);

        if (resultado.secoes.length === 0) {
            alert('Não encontrei conteúdo nesse texto.');
            return;
        }

        if (listaSecoes.children.length > 0 && !confirm('Isso vai substituir as seções atuais. Continuar?')) {
            return;
        }

        listaSecoes.replaceChildren();
        resultado.secoes.forEach((s) => criarSecao(s.titulo, s.conteudo));

        // Aproveita o "# Aula 01 — Título" do início para preencher campos vazios
        if (resultado.h1) {
            const campoTituloAula = document.querySelector('#titulo');
            const campoNumero = document.querySelector('#num_aula');
            const numero = resultado.h1.match(/Aula\s+0*(\d+)/i);

            if (campoTituloAula.value.trim() === '' && resultado.h1.length <= config.limiteTitulo) {
                campoTituloAula.value = resultado.h1;
            }

            if (numero && campoNumero.value.trim() === '') {
                campoNumero.value = numero[1];
            }
        }

        campoMarkdown.value = '';
    });

    /* ---------- Estado inicial (quando o envio volta com erro) ---------- */

    estadoInicial.tags.forEach(adicionarTag);
    estadoInicial.secoes.forEach((s) => criarSecao(s.titulo, s.conteudo));
})();
