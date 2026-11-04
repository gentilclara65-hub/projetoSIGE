
<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$evento = new Evento();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id <= 0) {
    header('Location: ' . BASE_URL . '/eventos/index.php');
    exit;
}

$dadosEvento = $evento->buscarPorId($id);

if (!$dadosEvento) {
    header('Location: ' . BASE_URL . '/eventos/index.php');
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        if ($evento->excluir($id)) {
            header('Location: ' . BASE_URL . '/eventos/index.php');
            exit;
        }

        $erro = 'Não foi possível excluir o evento.';

    } catch (PDOException $e) {
        $erro = 'Não foi possível excluir este evento. Verifique se existem inscrições vinculadas a ele.';
    }
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">

    <h2>Excluir Evento</h2>

    <p>Tem certeza de que deseja excluir este evento?</p>

    <br>

    <h3>
        <?= htmlspecialchars($dadosEvento['nome']) ?>
    </h3>

    <?php if ($erro !== ''): ?>
        <p style="color: red;">
            <?= htmlspecialchars($erro) ?>
        </p>
    <?php endif; ?>

    <br>

    <form method="POST">

        <button type="submit">
            Confirmar Exclusão
        </button>

        <a href="<?= BASE_URL ?>/eventos/index.php">
            <button type="button">
                Cancelar
            </button>
        </a>

    </form>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
