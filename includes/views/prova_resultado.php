<?php

/**
 * Tela de resultado: nota e gabarito comentado.
 * Variável: $resultado (de buscar_resultado)
 */

$refazer = $resultado['id_card'] === null
    ? 'prova.php?geral=1'
    : 'prova.php?aula=' . (int) $resultado['id_card'];

$escopo = $resultado['id_card'] === null
    ? 'Prova final'
    : 'Quiz da aula ' . str_pad($resultado['num_aula'], 2, '0', STR_PAD_LEFT);

?>
        <header class="prova-resumo">
            <span class="eyebrow">RESULTADO</span>

            <h1><?= (int) $resultado['acertos'] ?> de <?= (int) $resultado['total'] ?> acertos</h1>

            <p class="prova-percentual">
                <?= (int) $resultado['percentual'] ?>% ·
                <?php if ($resultado['aprovado']): ?>
                    <span class="prova-status">Média atingida</span>
                <?php else: ?>
                    <span class="prova-status prova-status--abaixo">Abaixo da média (<?= MEDIA_APROVACAO ?>%)</span>
                <?php endif; ?>
            </p>

            <div class="prova-barra"><span style="width: <?= (int) $resultado['percentual'] ?>%"></span></div>

            <p class="prova-meta"><?= e($escopo) ?> · <?= e(data_br($resultado['data_tentativa'])) ?></p>

            <div class="prova-resumo-acoes">
                <a href="<?= e($refazer) ?>" class="btn-primary">Refazer <i class="ph ph-arrow-clockwise"></i></a>
                <a href="prova.php" class="btn-secondary">Todas as provas</a>
                <a href="index.php#aulas" class="btn-secondary">Voltar às aulas</a>
            </div>
        </header>

        <?php if (empty($resultado['perguntas'])): ?>

            <div class="lesson-empty">
                <h3>Sem detalhes para mostrar</h3>
                <p>As perguntas dessa tentativa foram removidas.</p>
            </div>

        <?php else: ?>

            <ol class="prova-lista">

                <?php foreach ($resultado['perguntas'] as $i => $questao): ?>

                    <li class="prova-questao">

                        <div class="prova-questao-topo">
                            <span class="prova-numero">Questão <?= $i + 1 ?></span>
                            <span class="prova-aula-tag">Aula <?= e(str_pad($questao['num_aula'], 2, '0', STR_PAD_LEFT)) ?></span>

                            <?php if ($questao['correta']): ?>
                                <span class="prova-selo prova-selo--certa">Acertou</span>
                            <?php elseif ($questao['escolhida'] === null): ?>
                                <span class="prova-selo prova-selo--errada">Em branco</span>
                            <?php else: ?>
                                <span class="prova-selo prova-selo--errada">Errou</span>
                            <?php endif; ?>
                        </div>

                        <div class="markdown prova-enunciado" data-markdown><pre><?= e($questao['pergunta']) ?></pre></div>

                        <div class="prova-alternativas">
                            <?php foreach ($questao['alternativas'] as $alternativa): ?>
                                <?php
                                $marcada = $questao['escolhida'] === (int) $alternativa['id_alternativa'];
                                $estado = '';

                                if ($alternativa['correta']) {
                                    $estado = 'certa';
                                } elseif ($marcada) {
                                    $estado = 'errada';
                                }
                                ?>
                                <div class="prova-alternativa prova-alternativa--resultado <?= $estado !== '' ? 'prova-alternativa--' . $estado : '' ?>">
                                    <span class="prova-letra">
                                        <?php if ($estado === 'certa'): ?>
                                            <i class="ph ph-check"></i>
                                        <?php elseif ($estado === 'errada'): ?>
                                            <i class="ph ph-x"></i>
                                        <?php endif; ?>
                                    </span>

                                    <span class="prova-alt-texto" data-markdown-inline><?= e($alternativa['conteudo']) ?></span>

                                    <?php if ($marcada): ?>
                                        <span class="prova-selo">Sua resposta</span>
                                    <?php elseif ($alternativa['correta']): ?>
                                        <span class="prova-selo prova-selo--certa">Correta</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (!empty($questao['explicacao'])): ?>
                            <div class="prova-explicacao">
                                <strong>Explicação</strong>
                                <div class="markdown" data-markdown><pre><?= e($questao['explicacao']) ?></pre></div>
                            </div>
                        <?php endif; ?>

                    </li>

                <?php endforeach; ?>

            </ol>

        <?php endif; ?>
