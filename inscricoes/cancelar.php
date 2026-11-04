<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$inscricao = new Inscricao();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$dados = $inscricao->buscarPorId($id);

if (!$dados) {
    header('Location: index.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($dados['status'] === 'Ativa') {
        $inscricao->cancelar($id);
    }

    header('Location: index.php');
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Cancelar Inscrição</h2>

    <p>Deseja realmente cancelar esta inscrição?</p>

    <p>
        <strong>ID da inscrição:</strong>
        <?= (int) $dados['id'] ?>
    </p>

    <p>
        <strong>Status atual:</strong>
        <?= htmlspecialchars($dados['status']) ?>
    </p>

    <?php if ($dados['status'] === 'Ativa'): ?>

        <form method="POST">
            <button type="submit">Confirmar Cancelamento</button>
            <a href="index.php">Voltar</a>
        </form>

    <?php else: ?>

        <p>Esta inscrição já está cancelada.</p>
        <a href="index.php">Voltar</a>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>