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
