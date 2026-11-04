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
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');

    if ($nome === '' || $email === '') {
        $mensagem = 'Preencha o nome e o e-mail.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagem = 'Informe um e-mail válido.';
    } else {
        $participante->editar($id, [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone
        ]);

        header('Location: index.php');
        exit;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Editar Participante</h2>

    <?php if ($mensagem): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label>
        <input
            type="text"
            name="nome"
            value="<?= htmlspecialchars($_POST['nome'] ?? $dados['nome']) ?>"
            required
        >

        <label>E-mail:</label>
        <input
            type="email"
            name="email"
            value="<?= htmlspecialchars($_POST['email'] ?? $dados['email']) ?>"
            required
        >

        <label>Telefone:</label>
        <input
            type="text"
            name="telefone"
            value="<?= htmlspecialchars($_POST['telefone'] ?? $dados['telefone']) ?>"
        >

        <button type="submit">Salvar Alterações</button>
        <a href="index.php">Voltar</a>
    </form>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>