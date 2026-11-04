<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$inscricao = new Inscricao();
$participante = new Participante();
$evento = new Evento();

$participantes = $participante->listar();
$eventos = $evento->listar();

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participanteId = filter_input(INPUT_POST, 'participante_id', FILTER_VALIDATE_INT);
    $eventoId = filter_input(INPUT_POST, 'evento_id', FILTER_VALIDATE_INT);

    if (!$participanteId || !$eventoId) {
        $mensagem = 'Selecione um participante e um evento.';
    } else {
        try {
            $resultado = $inscricao->cadastrar($participanteId, $eventoId);

            if ($resultado) {
                header('Location: index.php');
                exit;
            }

            $mensagem = 'Não foi possível realizar a inscrição.';

        } catch (RuntimeException $erro) {
            $mensagem = $erro->getMessage();

        } catch (PDOException $erro) {
            $mensagem = 'Ocorreu um erro ao realizar a inscrição. Verifique os dados selecionados.';
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Nova Inscrição</h2>

    <?php if ($mensagem): ?>
        <p><?= htmlspecialchars($mensagem) ?></p>
    <?php endif; ?>

    <?php if (empty($participantes) || empty($eventos)): ?>

        <p>
            É necessário cadastrar pelo menos um participante
            e um evento antes de realizar uma inscrição.
        </p>

    <?php else: ?>

        <form method="POST">

            <label for="participante_id">Participante:</label>

            <select name="participante_id" id="participante_id" required>
                <option value="">Selecione um participante</option>

                <?php foreach ($participantes as $item): ?>
                    <option
                        value="<?= (int) $item['id'] ?>"
                        <?= (isset($participanteId) && (int) $participanteId === (int) $item['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($item['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="evento_id">Evento:</label>

            <select name="evento_id" id="evento_id" required>
                <option value="">Selecione um evento</option>

                <?php foreach ($eventos as $item): ?>
                    <option
                        value="<?= (int) $item['id'] ?>"
                        <?= (isset($eventoId) && (int) $eventoId === (int) $item['id']) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($item['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Realizar Inscrição</button>

            <a href="index.php">Voltar</a>

        </form>

    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>