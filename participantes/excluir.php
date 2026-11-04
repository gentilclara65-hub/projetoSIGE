<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$participante = new Participante();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

$dados = $participante->buscarPorId($id);

if (!$dados) {
    header('Location: index.php');
    exit;
}

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $participante->excluir($id);

        header('Location: index.php');
        exit;
    } catch (PDOException $erro) {
        $mensagem = 'Não foi possível excluir este participante. Verifique se ele possui inscrições vinculadas.';
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Excluir Participante</h2>

    <?php if ($mensagem): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php else: ?>
        <p>
            Tem certeza de que deseja excluir o participante
            <strong><?= htmlspecialchars($dados['nome']) ?></strong>?
        </p>

        <form method="POST">
            <button type="submit">Confirmar Exclusão</button>
            <a href="index.php">Cancelar</a>
        </form>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>