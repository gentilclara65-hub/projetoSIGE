
<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => trim($_POST['telefone'] ?? '')
        ];

        if (
            $dados['nome'] === '' ||
            $dados['email'] === '' ||
            $dados['telefone'] === ''
        ) {
            throw new Exception('Preencha todos os campos.');
        }

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Informe um e-mail válido.');
        }

        $participante = new Participante();

        if ($participante->cadastrar($dados)) {
            header('Location: ' . BASE_URL . '/participantes/index.php');
            exit;
        }

        $erro = 'Não foi possível cadastrar o participante.';

    } catch (Throwable $e) {
        $erro = $e instanceof PDOException
            ? 'Não foi possível salvar. Verifique se o e-mail já está cadastrado.'
            : $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">

    <h2>Cadastrar Participante</h2>

    <p>Preencha os dados para registrar um novo participante.</p>

    <br>

    <?php if ($erro !== ''): ?>
        <p style="color: red;">
            <?= htmlspecialchars($erro) ?>
        </p>
        <br>
    <?php endif; ?>

    <form method="POST">

        <label>Nome completo:</label>
        <input
            type="text"
            name="nome"
            placeholder="Digite o nome completo"
            required
        >

        <label>E-mail:</label>
        <input
            type="email"
            name="email"
            placeholder="exemplo@email.com"
            required
        >

        <label>Telefone:</label>
        <input
            type="tel"
            name="telefone"
            placeholder="(00) 00000-0000"
            required
        >

        <br>

        <button type="submit">Salvar Participante</button>

        <a href="<?= BASE_URL ?>/participantes/index.php">
            <button type="button">Voltar</button>
        </a>

    </form>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
