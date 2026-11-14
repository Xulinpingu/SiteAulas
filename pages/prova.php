<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../config/connDB.php';
require_once __DIR__ . '/../includes/aulas.php';
require_once __DIR__ . '/../includes/provas.php';

exigir_login();

$id_usuario = (int) $_SESSION['id_usuario'];


/* ---------- Envio das respostas (POST) ---------- */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = texto_limpo($_POST['token'] ?? '');
    $prova_ativa = csrf_valido() ? obter_prova_ativa($token) : null;

    if ($prova_ativa === null) {
        definir_aviso('Essa prova expirou. Comece novamente.');
        header('Location: prova.php');
        exit;
    }

    $respostas = is_array($_POST['resp'] ?? null) ? $_POST['resp'] : [];

    try {
        $id_tentativa = corrigir_prova($pdo, $id_usuario, $prova_ativa['id_card'], $prova_ativa['ids'], $respostas);
        encerrar_prova_ativa($token);

        header('Location: prova.php?resultado=' . $id_tentativa);
        exit;
    } catch (InvalidArgumentException $e) {
        definir_aviso($e->getMessage());
    } catch (PDOException $e) {
        error_log($e->getMessage());
        definir_aviso('Não foi possível corrigir a prova. Tente novamente.');
    }

    header('Location: prova.php');
    exit;
}


/* ---------- Qual tela mostrar (GET) ---------- */

$tela = 'inicio';             // inicio | questoes | resultado
$erro_banco = false;
$migracao_pendente = false;

$aulas = [];
$perguntas_por_aula = [];
$total_geral = 0;
$tentativas = [];
$prova = [];
$token = '';
$escopo_titulo = '';
$resultado = null;

try {
    if (isset($_GET['resultado'])) {

        $id_tentativa = filter_input(INPUT_GET, 'resultado', FILTER_VALIDATE_INT);
        $resultado = $id_tentativa ? buscar_resultado($pdo, $id_tentativa, $id_usuario) : null;

        if ($resultado === null) {
            definir_aviso('Resultado não encontrado.');
            header('Location: prova.php');
            exit;
        }

        $tela = 'resultado';

    } elseif (isset($_GET['aula']) || isset($_GET['geral'])) {

        $id_card = null;

        if (isset($_GET['aula'])) {
            $id_card = filter_input(INPUT_GET, 'aula', FILTER_VALIDATE_INT);
            $card = $id_card ? buscar_card($pdo, $id_card) : null;

            if ($card === null) {
                definir_aviso('Aula não encontrada.');
                header('Location: prova.php');
                exit;
            }

            $escopo_titulo = 'Quiz da aula ' . str_pad($card['num_aula'], 2, '0', STR_PAD_LEFT);
        } else {
            $escopo_titulo = 'Prova final';
        }

        $prova = carregar_prova($pdo, $id_card);

        if (empty($prova)) {
            definir_aviso('Ainda não há perguntas cadastradas para essa prova.');
            header('Location: prova.php');
            exit;
        }

        $token = registrar_prova_ativa($id_card, array_column($prova, 'id_pergunta'));
        $tela = 'questoes';

    } else {

        [$aulas] = listar_aulas($pdo);
        $perguntas_por_aula = contar_perguntas_por_aula($pdo);
        $total_geral = array_sum($perguntas_por_aula);
        $tentativas = listar_tentativas($pdo, $id_usuario);

    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    $erro_banco = true;
    // 42S02 = tabela inexistente, 42S22 = coluna inexistente: faltou rodar a migração
    $migracao_pendente = in_array($e->getCode(), ['42S02', '42S22'], true);
}


/* ---------- Página ---------- */

$page_title = 'Provas | Learn AI';
$markdown = !$erro_banco && $tela !== 'inicio';
$page_js = !$erro_banco && $tela === 'questoes' ? ['prova'] : [];

require __DIR__ . '/../includes/head.php';
if ($tela != 'questoes') {
    require __DIR__ . '/../includes/header.php';
}

?>

    <main class="prova-page">

        <?php exibir_aviso(); ?>

        <?php if ($erro_banco): ?>

            <div class="lesson-empty">
                <h3>Não foi possível carregar as provas</h3>
                <p>
                    <?php if ($migracao_pendente): ?>
                        O banco de dados precisa da migração
                        <strong>database/migracao_02_provas.sql</strong>.
                    <?php else: ?>
                        Ocorreu um erro ao buscar as provas. Tente novamente em instantes.
                    <?php endif; ?>
                </p>
                <a href="index.php" class="btn-secondary">Voltar ao início</a>
            </div>

        <?php elseif ($tela === 'questoes'): ?>

            <?php require __DIR__ . '/../includes/views/prova_questoes.php'; ?>

        <?php elseif ($tela === 'resultado'): ?>

            <?php require __DIR__ . '/../includes/views/prova_resultado.php'; ?>

        <?php else: ?>

            <?php require __DIR__ . '/../includes/views/prova_inicio.php'; ?>

        <?php endif; ?>

    </main>

<?php

require __DIR__ . '/../includes/footer.php';
require __DIR__ . '/../includes/scripts.php';
