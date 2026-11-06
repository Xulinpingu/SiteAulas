<?php

session_start();

$logado = isset($_SESSION['user']);
$admin = !empty($_SESSION['admin']);

if (!$logado || !$admin) {
    header("Location: index.php");
    exit;
}

$mensagem = '';

require_once '../config/connDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $num_aula = filter_var($_POST['num_aula'] ?? null, FILTER_VALIDATE_INT);
    $tags_selecionadas = $_POST['tags'] ?? [];

    if (
        $titulo === '' ||
        $descricao === '' ||
        $num_aula === false ||
        $num_aula === null ||
        $num_aula < 1
    ) {
        $mensagem = 'Preencha todos os campos corretamente.';

    } elseif (mb_strlen($titulo, 'UTF-8') > 50) {
        $mensagem = 'O título deve ter no máximo 50 caracteres.';

    } elseif (mb_strlen($descricao, 'UTF-8') > 100) {
        $mensagem = 'A descrição deve ter no máximo 100 caracteres.';

    } elseif (!is_array($tags_selecionadas)) {
        $mensagem = 'Tags inválidas.';

    } else {

        $nomes_tags = [];

        foreach ($tags_selecionadas as $nome) {

            if (!is_string($nome)) {
                continue;
            }

            $nome = trim($nome);

            if ($nome !== '' && mb_strlen($nome, 'UTF-8') <= 50) {
                $nomes_tags[] = $nome;
            }
        }

        $tags_unicas = [];

        foreach ($nomes_tags as $nome) {
            $tags_unicas[mb_strtolower($nome, 'UTF-8')] = $nome;
        }

        $nomes_tags = array_values($tags_unicas);

        try {

            $pdo->beginTransaction();

            $sql = "INSERT INTO card_aulas (title, num_aula, descricao)
                    VALUES (:titulo, :num_aula, :descricao)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':titulo' => $titulo,
                ':num_aula' => $num_aula,
                ':descricao' => $descricao
            ]);

            $id_aula = $pdo->lastInsertId();

            $sql_buscar = "SELECT id_tag FROM tag WHERE tag = :tag";
            $stmt_buscar = $pdo->prepare($sql_buscar);

            $sql_inserir = "INSERT INTO tag (tag) VALUES (:tag)";
            $stmt_inserir = $pdo->prepare($sql_inserir);

            $sql_relacionar = "INSERT INTO aula_tag (id_card, id_tag)
                               VALUES (:id_card, :id_tag)";
            $stmt_relacionar = $pdo->prepare($sql_relacionar);

            foreach ($nomes_tags as $nome_tag) {

                $stmt_buscar->execute([
                    ':tag' => $nome_tag
                ]);

                $id_tag = $stmt_buscar->fetchColumn();

                if (!$id_tag) {

                    $stmt_inserir->execute([
                        ':tag' => $nome_tag
                    ]);

                    $id_tag = $pdo->lastInsertId();
                }

                $stmt_relacionar->execute([
                    ':id_card' => $id_aula,
                    ':id_tag' => $id_tag
                ]);
            }

            $pdo->commit();

            $_POST = [];

            $mensagem = 'Aula cadastrada com sucesso!';

        } catch (PDOException $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            error_log($e->getMessage());

            $mensagem = 'Erro ao cadastrar a aula. Verifique os dados e tente novamente.';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Adicionar Aulas</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <link rel="icon" type="image/png" href="../assets/imgs/favicon.ico">

    <link
        rel="stylesheet"
        type="text/css"
        href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.1/src/regular/style.css"
    />

</head>

<body class="admin-page">

    <header class="header">

        <nav class="navbar container">

            <div class="admin-navbar-left">

                <a href="index.php" class="brand">
                    <img src="../assets/imgs/logo.png" alt="Learn AI">
                    <span>Learn<span class="brand-highlight">AI</span></span>
                </a>

                <a href="index.php#aulas" class="nav-link">Explorar aulas</a>

            </div>

            <?php if (!$logado): ?>

                <a href="login.php" class="btn-primary">
                    Login
                </a>

            <?php else: ?>

                <div class="lesson-counter" onclick="ToggleLogout()" style="cursor: pointer;">

                    <button class="username">
                        <?= htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8') ?>
                    </button>

                    <div class="popup-conta hidden">

                        <a href="../actions/logout_action.php" class="btn-secondary btn-red">
                            Logout
                            <i class="ph ph-sign-out"></i>
                        </a>

                    </div>

                </div>

            <?php endif; ?>

        </nav>

    </header>

    <main class="admin-main">

        <section class="admin-form-section">

            <div class="section-heading">

                <span class="eyebrow">ÁREA ADMINISTRATIVA</span>

                <h2>Adicionar nova aula</h2>

                <p>Preencha as informações para disponibilizar uma nova aula.</p>

            </div>

            <?php if (!empty($mensagem)): ?>

                <div class="alerta">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                </div>

            <?php endif; ?>

            <form method="POST" class="admin-form">

                <div>

                    <label for="titulo">Título da aula</label>

                    <input
                        type="text"
                        name="titulo"
                        id="titulo"
                        maxlength="50"
                        placeholder="Ex.: Introdução à IA"
                        value="<?= htmlspecialchars($_POST['titulo'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>

                <div>

                    <label for="descricao">Descrição</label>

                    <textarea
                        name="descricao"
                        id="descricao"
                        maxlength="100"
                        placeholder="Descreva o conteúdo da aula..."
                        required
                    ><?= htmlspecialchars($_POST['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

                </div>

                <div>

                    <label for="num_aula">Número da aula</label>

                    <input
                        type="number"
                        name="num_aula"
                        id="num_aula"
                        min="1"
                        placeholder="Ex.: 1"
                        value="<?= htmlspecialchars($_POST['num_aula'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required
                    >

                </div>

                <div class="tags-container">

                    <label for="nova_tag">Tags da aula</label>

                    <div class="tag-input-container">

                        <input
                            type="text"
                            id="nova_tag"
                            placeholder="Digite uma tag..."
                            maxlength="50"
                        >

                        <button type="button" id="adicionar_tag">
                            Adicionar
                            <i class="ph ph-plus"></i>
                        </button>

                    </div>

                    <div class="tags-list" id="tags-list">

                        <!-- Tags adicionadas pelo JavaScript -->

                    </div>

                </div>

                <button type="submit" class="btn-primary">

                    Cadastrar aula

                    <i class="ph ph-plus"></i>

                </button>

            </form>

        </section>

    </main>

    <script>

        const campoTag = document.querySelector('#nova_tag');
        const botaoAdicionar = document.querySelector('#adicionar_tag');
        const listaTags = document.querySelector('#tags-list');

        const tagsAdicionadas = new Set();

        function adicionarTag() {

            const nomeTag = campoTag.value.trim();

            if (nomeTag === '') {
                return;
            }

            if (nomeTag.length > 50) {
                alert('A tag deve ter no máximo 50 caracteres.');
                return;
            }

            const tagNormalizada = nomeTag.toLocaleLowerCase('pt-BR');

            // Impedir tags duplicadas
            if (tagsAdicionadas.has(tagNormalizada)) {
                campoTag.value = '';
                return;
            }

            tagsAdicionadas.add(tagNormalizada);

            // Criar elemento da tag
            const tag = document.createElement('div');
            tag.classList.add('tag-option');

            const texto = document.createElement('span');
            texto.textContent = nomeTag;

            // Campo enviado pelo formulário
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'tags[]';
            input.value = nomeTag;

            // Botão para remover
            const remover = document.createElement('button');
            remover.type = 'button';
            remover.classList.add('remover-tag');
            remover.textContent = '×';

            remover.addEventListener('click', () => {

                tagsAdicionadas.delete(tagNormalizada);

                tag.remove();

            });

            tag.appendChild(texto);
            tag.appendChild(input);
            tag.appendChild(remover);

            listaTags.appendChild(tag);

            campoTag.value = '';
            campoTag.focus();

        }

        // Adicionar pelo botão
        botaoAdicionar.addEventListener('click', adicionarTag);

        // Adicionar apertando Enter
        campoTag.addEventListener('keydown', (event) => {

            if (event.key === 'Enter') {

                event.preventDefault();

                adicionarTag();

            }

        });

    </script>

    <script>
        function ToggleLogout() {
            const popup = document.querySelector('.popup-conta');
            if (popup) popup.classList.toggle('hidden');
        }
    </script>

</body>

</html>
