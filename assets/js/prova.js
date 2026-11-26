/**
 * Tela da prova (includes/views/prova_questoes.php):
 *  - barra de progresso com a quantidade de questões respondidas;
 *  - cronômetro (o tempo restante vem do servidor, em data-restante);
 *  - trava de troca de aba: cada saída é registrada no servidor
 *    (actions/prova_saida_action.php), que decide quando cancelar a prova.
 */
(function () {
    const form = document.querySelector('.prova-form');
    if (!form) return;

    const total = Number(form.dataset.total);
    const texto = document.querySelector('[data-progresso-texto]');
    const barra = document.querySelector('.prova-progresso .prova-barra span');
    const botao = form.querySelector('button[type="submit"]');
    const timerEl = document.getElementById('timer');
    const linkCancelar = document.querySelector('.prova-enviar a');

    const dialogo = document.getElementById('aviso-saida');
    const dialogoTitulo = dialogo.querySelector('[data-aviso-titulo]');
    const dialogoTexto = dialogo.querySelector('[data-aviso-texto]');
    const dialogoBotao = dialogo.querySelector('[data-aviso-botao]');


    /* ---------- Progresso ---------- */

    function atualizar() {
        const nomes = new Set(
            [...form.querySelectorAll('input[type="radio"]:checked')].map((radio) => radio.name)
        );
        const respondidas = nomes.size;

        texto.textContent = respondidas + ' de ' + total + ' respondidas';
        barra.style.width = (total ? (respondidas / total) * 100 : 0) + '%';
    }

    form.addEventListener('change', atualizar);
    atualizar();


    /* ---------- Saída legítima da página ---------- */

    // true quando sair da página é esperado (enviar, cancelar, tempo esgotado):
    // nesses casos não conta como "troca de aba" e não abre o aviso do navegador.
    let liberado = false;
    let cancelada = false;

    // 'submit' só dispara quando o formulário passou na validação (radios "required").
    // Antes a trava era desligada no clique do botão, e um "Enviar" com questões
    // em branco deixava a prova sem proteção até o fim.
    form.addEventListener('submit', () => {
        liberado = true;
        if (botao) botao.disabled = true;
    });

    if (linkCancelar) {
        linkCancelar.addEventListener('click', () => {
            liberado = true;
        });
    }

    window.addEventListener('beforeunload', (e) => {
        if (liberado || cancelada) return;
        e.preventDefault();
        e.returnValue = '';
    });


    /* ---------- Troca de aba ---------- */

    function chamar(acao) {
        const dados = new FormData();
        dados.append('csrf', form.elements.csrf.value);
        dados.append('token', form.elements.token.value);
        dados.append('acao', acao);

        // keepalive: a requisição termina mesmo se a página for descarregada
        return fetch(form.dataset.saidaUrl, {
            method: 'POST',
            body: dados,
            credentials: 'same-origin',
            keepalive: true,
        })
            .then((resposta) => resposta.json())
            .catch(() => null);
    }

    function abrirAviso(titulo, mensagem, rotulo, aoConfirmar) {
        document.querySelector('body').classList.toggle('unscrollable');

        dialogoTitulo.textContent = titulo;
        dialogoTexto.textContent = mensagem;
        dialogoBotao.textContent = rotulo;
        dialogoBotao.onclick = aoConfirmar;

        if (!dialogo.open) dialogo.showModal();
    }

    function fecharAviso() {
        document.querySelector('body').classList.toggle('unscrollable');
        dialogo.close();
    }

    // Enquanto a prova está cancelada o aviso não pode ser fechado com Esc
    dialogo.addEventListener('cancel', (e) => {
        if (cancelada) e.preventDefault();
    });

    function encerrarNaTela() {
        cancelada = true;
        clearInterval(intervalo);
        form.querySelectorAll('input, button').forEach((el) => {
            el.disabled = true;
        });
    }

    function tratarSaida(estado) {
        if (estado && estado.expirada) {
            encerrarNaTela();
            abrirAviso('Prova encerrada', 'Essa prova não está mais ativa.', 'Voltar às provas', () => {
                window.location.href = 'prova.php';
            });
            return;
        }

        if (estado && estado.cancelada) {
            encerrarNaTela();
            abrirAviso(
                'Prova cancelada',
                'Você saiu da aba do navegador mais de uma vez durante a prova.',
                'Voltar às provas',
                () => {
                    window.location.href = 'prova.php';
                }
            );
            return;
        }

        const mensagem = estado
            ? 'Saída ' + estado.saidas + ' de ' + estado.max + '. Na saída número ' + estado.max +
              ' a prova é cancelada.'
            : 'Saídas repetidas da aba cancelam a prova.';

        abrirAviso('Você saiu da aba da prova', mensagem, 'Voltar à prova', () => fecharAviso());
    }

    // A saída é registrada quando a aba some e o aviso aparece quando ela volta.
    // O aviso usa a resposta do próprio registro, então não há "corrida" entre
    // registrar a saída e consultar o estado.
    let pendente = null;

    document.addEventListener('visibilitychange', () => {
        if (liberado || cancelada) return;

        if (document.hidden) {
            if (!pendente) pendente = chamar('saida');
            return;
        }

        if (!pendente) return;

        const consulta = pendente;
        pendente = null;
        consulta.then(tratarSaida);
    });


    /* ---------- Cronômetro ---------- */

    // Date.now() (e não só a contagem do setInterval) para o tempo não atrasar
    // quando a aba fica em segundo plano.
    const prazoFinal = Date.now() + Number(form.dataset.restante) * 1000;
    let intervalo = null;

    function formatar(segundos) {
        const mm = String(Math.floor(segundos / 60)).padStart(2, '0');
        const ss = String(segundos % 60).padStart(2, '0');
        return mm + ':' + ss;
    }

    // Envia a prova do jeito que estiver (mesmo incompleta) quando o tempo acaba
    function enviarPorTempoEsgotado() {
        if (cancelada || liberado) return;
        liberado = true;

        timerEl.textContent = 'Tempo esgotado, enviando...';
        form.querySelectorAll('button').forEach((el) => {
            el.disabled = true;
        });

        // form.submit() ignora o "required" dos radios, então vai mesmo incompleta.
        // Questões sem resposta são corrigidas como erradas pelo servidor.
        form.submit();
    }

    function tick() {
        const restante = Math.max(0, Math.ceil((prazoFinal - Date.now()) / 1000));
        timerEl.textContent = formatar(restante);

        if (restante <= 0) {
            clearInterval(intervalo);
            enviarPorTempoEsgotado();
        }
    }

    intervalo = setInterval(tick, 1000);
    tick();
})();
