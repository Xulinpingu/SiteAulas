<?php

/**
 * Tela inicial das provas: prova final, quiz por aula e histórico.
 * Variáveis: $aulas, $perguntas_por_aula, $total_geral, $tentativas, $admin
 */

?>
        <header class="prova-cabecalho">
            <span class="eyebrow">AVALIAÇÃO</span>

            <h1>Provas e quizzes</h1>

            <p>
                Teste o que você aprendeu. Faça a prova final com perguntas de todas as aulas
                ou treine com o quiz de uma aula específica.
            </p>
        </header>

        <section class="prova-bloco">
            <h2>Prova final</h2>

            <p>
                Perguntas sorteadas de todas as aulas (até <?= PERGUNTAS_PROVA_GERAL ?> por prova).
                Para atingir a média é preciso acertar pelo menos <?= MEDIA_APROVACAO ?>%.
            </p>

            <?php if ($total_geral > 0): ?>

                <div class="prova-acoes">
                    <a href="prova.php?geral=1" class="btn-primary">
                        Começar prova final <i class="ph ph-arrow-right"></i>
                    </a>

                    <?php if ($admin): ?>
                        <span class="prova-nota">
                            <?= (int) $total_geral ?>
                            <?= $total_geral === 1 ? 'pergunta cadastrada' : 'perguntas cadastradas' ?>
                        </span>
                    <?php endif; ?>
                </div>

            <?php else: ?>

                <p class="prova-vazio">Ainda não há perguntas cadastradas.</p>

                <?php if ($admin): ?>
                    <div class="prova-acoes">
                        <a href="add_pergunta.php" class="btn-primary">
                            Cadastrar perguntas <i class="ph ph-plus"></i>
                        </a>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </section>

        <section class="prova-bloco">
            <h2>Quiz por aula</h2>

            <?php if (empty($aulas)): ?>

                <p class="prova-vazio">Nenhuma aula cadastrada ainda.</p>

            <?php else: ?>

                <ul class="prova-linhas">
                    <?php foreach ($aulas as $aula): ?>
                        <?php
                        $total_aula = $perguntas_por_aula[(int) $aula['id_card']] ?? 0;
                        $numero = str_pad($aula['num_aula'], 2, '0', STR_PAD_LEFT);
                        ?>
                        <li class="prova-linha">
                            <span class="prova-linha-numero"><?= e($numero) ?></span>

                            <div class="prova-linha-info">
                                <strong><?= e($aula['title']) ?></strong>
                                <?php if ($admin): ?>
                                    <span>
                                        <?php if ($total_aula > 0): ?>
                                            <?= $total_aula ?> <?= $total_aula === 1 ? 'pergunta' : 'perguntas' ?>
                                        <?php else: ?>
                                            Sem perguntas ainda
                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php if ($total_aula > 0): ?>
                                <a href="prova.php?aula=<?= (int) $aula['id_card'] ?>" class="btn-secondary">
                                    Fazer quiz <i class="ph ph-arrow-right"></i>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
        </section>

        <section class="prova-bloco">
            <h2>Suas tentativas</h2>

            <?php if (empty($tentativas)): ?>

                <p class="prova-vazio">Você ainda não fez nenhuma prova.</p>

            <?php else: ?>

                <ul class="prova-linhas">
                    <?php foreach ($tentativas as $tentativa): ?>
                        <?php
                        $total_t = (int) $tentativa['total'];
                        $percentual = $total_t > 0 ? (int) round($tentativa['acertos'] * 100 / $total_t) : 0;
                        ?>
                        <li class="prova-linha">
                            <div class="prova-linha-info">
                                <strong>
                                    <?= $tentativa['id_card'] === null
                                        ? 'Prova final'
                                        : 'Quiz da aula ' . e(str_pad($tentativa['num_aula'], 2, '0', STR_PAD_LEFT)) ?>
                                </strong>
                                <span><?= e(data_br($tentativa['data_tentativa'])) ?></span>
                            </div>

                            <span class="prova-linha-nota <?= $percentual >= MEDIA_APROVACAO ? 'prova-linha-nota--ok' : '' ?>">
                                <?= (int) $tentativa['acertos'] ?>/<?= $total_t ?> · <?= $percentual ?>%
                            </span>

                            <a href="prova.php?resultado=<?= (int) $tentativa['id_tentativa'] ?>" class="btn-secondary">
                                Ver resultado
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

            <?php endif; ?>
        </section>
