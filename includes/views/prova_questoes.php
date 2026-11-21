<?php

/**
 * Tela da prova: perguntas com alternativas para marcar.
 * Variáveis: $prova (perguntas sorteadas), $token, $escopo_titulo
 */

$total_questoes = count($prova);

?>
        <header class="prova-cabecalho">
            <span class="eyebrow">EM ANDAMENTO</span>

            <h1><?= e($escopo_titulo) ?></h1>

            <p>
                Marque uma alternativa em cada questão e envie no final.
                As respostas só são corrigidas depois do envio.
            </p>
        </header>

        <form method="POST" action="prova.php" class="prova-form" data-total="<?= $total_questoes ?>">

            <?= csrf_campo() ?>
            <input type="hidden" name="token" value="<?= e($token) ?>">

            <div class="prova-progresso">
                <div id="timer">00:00</div>
                <div class="prova-progresso-texto">
                    <span data-progresso-texto>0 de <?= $total_questoes ?> respondidas</span>
                </div>

                <div class="prova-barra"><span></span></div>
            </div>

            <ol class="prova-lista">

                <?php foreach ($prova as $i => $questao): ?>

                    <li class="prova-questao">

                        <div class="prova-questao-topo">
                            <span class="prova-numero">Questão <?= $i + 1 ?></span>
                            <span class="prova-aula-tag">Aula <?= e(str_pad($questao['num_aula'], 2, '0', STR_PAD_LEFT)) ?></span>
                        </div>

                        <div class="markdown prova-enunciado" data-markdown><pre><?= e($questao['pergunta']) ?></pre></div>

                        <div class="prova-alternativas">
                            <?php foreach ($questao['alternativas'] as $j => $alternativa): ?>
                                <label class="prova-alternativa">
                                    <input
                                        type="radio"
                                        name="resp[<?= (int) $questao['id_pergunta'] ?>]"
                                        value="<?= (int) $alternativa['id_alternativa'] ?>"
                                        required
                                    >
                                    <span class="prova-letra"><?= chr(65 + $j) ?></span>
                                    <span class="prova-alt-texto" data-markdown-inline><?= e($alternativa['conteudo']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>

                    </li>

                <?php endforeach; ?>

            </ol>

            <div class="prova-enviar">
                <a href="prova.php" class="btn-secondary">Cancelar</a>

                <button type="submit" class="btn-primary">
                    Enviar respostas <i class="ph ph-check"></i>
                </button>
            </div>

        </form>

        <script>
            const btns_enviar = document.querySelector('.prova-enviar');

            let saiu = 0;
            let unload = true;
            let cancelando = false;

            document.addEventListener('click', (e) => {
                const link = btns_enviar?.querySelector('a');
                const button = btns_enviar?.querySelector('button');

                if (
                    (link && link.contains(e.target)) ||
                    (button && button.contains(e.target))
                ) {
                    unload = false;
                    cancelando = true;
                }
            });

            document.addEventListener('visibilitychange', () => {

                if (document.hidden && !cancelando && unload) {

                    saiu++;

                    if (saiu === 1) {
                        window.location.reload();

                        alert('Você saiu da aba do navegador. Por favor, não faça isso durante a prova, na próxima ocorrência terá sua prova cancelada.');
                    }
                    else if (saiu >= 2) {
                        cancelando = true;

                        // Aguarda o usuário retornar
                        document.addEventListener('visibilitychange', function retornar() {

                            if (!document.hidden) {
                                document.removeEventListener('visibilitychange', retornar);

                                alert('Você saiu da aba do navegador novamente. Sua prova será cancelada.');

                                unload = false;
                                window.location.href = 'prova.php';
                            }

                        });
                    }

                }
            });

            window.addEventListener('beforeunload', (e) => {
                if (unload && !cancelando) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            const duracao = 900 + (180 * <?= count($prova) ?>);
            // Usa o relógio (e não só a contagem do setInterval) para o tempo não
            // atrasar quando a aba fica em segundo plano.
            const prazoFinal = Date.now() + duracao * 1000;
            let tempoRestante = duracao;
            let intervalo = null;
            let enviadoPorTempo = false;

            function atualizarDisplay() {
                const minutos = Math.floor(tempoRestante / 60);
                const segundos = tempoRestante % 60;

                document.getElementById('timer').innerText =
                    `${String(minutos).padStart(2, '0')}:${String(segundos).padStart(2, '0')}`;
            }

            // Envia a prova do jeito que estiver (mesmo incompleta) quando o tempo acaba
            function enviarPorTempoEsgotado() {
                if (enviadoPorTempo) return;
                enviadoPorTempo = true;

                const form = document.querySelector('.prova-form');

                document.getElementById('timer').innerText = 'Tempo esgotado, enviando...';

                // Libera as travas de saída para o navegador não bloquear o envio
                unload = false;
                cancelando = true;

                // Impede novos cliques enquanto envia
                form.querySelectorAll('button, input').forEach((el) => {
                    if (el.type !== 'hidden' && el.type !== 'radio') el.disabled = true;
                });

                // form.submit() ignora o "required" dos radios, então vai mesmo incompleta.
                // Questões sem resposta são corrigidas como erradas pelo servidor.
                form.submit();
            }

            function iniciarTimer() {
                if (intervalo) return; // Evita múltiplos intervals

                intervalo = setInterval(() => {
                    tempoRestante = Math.max(0, Math.ceil((prazoFinal - Date.now()) / 1000));
                    atualizarDisplay();

                    if (tempoRestante <= 0) {
                        clearInterval(intervalo);
                        intervalo = null;

                        enviarPorTempoEsgotado();
                    }
                }, 1000);
            }

            window.addEventListener('load', (event) => {
                iniciarTimer();
            });

        </script>
