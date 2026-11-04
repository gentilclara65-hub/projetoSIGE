
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

$dados = [
    'nome' => $dadosEvento['nome'],
    'descricao' => $dadosEvento['descricao'],
    'data_evento' => $dadosEvento['data_evento'],
    'horario' => $dadosEvento['horario'],
    'local' => $dadosEvento['local'],
    'vagas' => $dadosEvento['vagas']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {

        $dados['nome'] = trim($_POST['nome'] ?? '');
        $dados['descricao'] = trim($_POST['descricao'] ?? '');
        $dados['data_evento'] = $_POST['data_evento'] ?? '';
        $dados['horario'] = $_POST['horario'] ?? '';
        $dados['local'] = trim($_POST['local'] ?? '');

        $dados['vagas'] = filter_var(
            $_POST['vagas'] ?? '',
            FILTER_VALIDATE_INT
        );

        // Validar campos obrigatórios e quantidade de vagas
        if (
            $dados['nome'] === '' ||
            $dados['descricao'] === '' ||
            $dados['data_evento'] === '' ||
            $dados['horario'] === '' ||
            $dados['local'] === '' ||
            $dados['vagas'] === false ||
            $dados['vagas'] < 1 ||
            $dados['vagas'] > 300
        ) {
            throw new Exception(
                'Preencha todos os campos corretamente. A quantidade de vagas deve ser entre 1 e 300.'
            );
        }

        // Limite de caracteres
        if (
            mb_strlen($dados['nome']) > 150 ||
            mb_strlen($dados['local']) > 150
        ) {
            throw new Exception(
                'O nome e o local devem ter no máximo 150 caracteres.'
            );
        }

        // Validar data real
        $data = DateTime::createFromFormat(
            '!Y-m-d',
            $dados['data_evento']
        );

        if (
            !$data ||
            $data->format('Y-m-d') !== $dados['data_evento']
        ) {
            throw new Exception('Informe uma data válida.');
        }

        // Impedir datas anteriores a hoje
        $hoje = new DateTime('today');

        if ($data < $hoje) {
            throw new Exception(
                'A data do evento não pode ser anterior à data atual.'
            );
        }

        // Validar horário
        if (!preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', $dados['horario'])) {
            throw new Exception('Informe um horário válido.');
        }

        // Atualizar evento
        if ($evento->editar($id, $dados)) {
            header('Location: ' . BASE_URL . '/eventos/index.php');
            exit;
        }

        $erro = 'Não foi possível atualizar o evento.';

    } catch (Throwable $e) {

        $erro = $e instanceof PDOException
            ? 'Erro ao atualizar o evento. Verifique os dados e tente novamente.'
            : $e->getMessage();
    }
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">

    <h2>Editar Evento</h2>

    <p>Atualize as informações do evento abaixo.</p>

    <br>

    <?php if ($erro !== ''): ?>
        <p style="color: red;">
            <?= htmlspecialchars($erro) ?>
        </p>
        <br>
    <?php endif; ?>

    <form method="POST" action="">

        <label>Nome do evento:</label>
        <input
            type="text"
            name="nome"
            maxlength="150"
            value="<?= htmlspecialchars($dados['nome']) ?>"
            placeholder="Digite o nome do evento"
            required
        >

        <label>Descrição:</label>
        <textarea
            name="descricao"
            rows="4"
            placeholder="Descreva o evento"
            required
        ><?= htmlspecialchars($dados['descricao']) ?></textarea>

        <label>Data do evento:</label>
        <input
            type="date"
            name="data_evento"
            min="<?= date('Y-m-d') ?>"
            value="<?= htmlspecialchars($dados['data_evento']) ?>"
            required
        >

        <label>Horário:</label>
        <input
            type="time"
            name="horario"
            value="<?= htmlspecialchars(substr($dados['horario'], 0, 5)) ?>"
            required
        >

        <label>Local:</label>
        <input
            type="text"
            name="local"
            maxlength="150"
            value="<?= htmlspecialchars($dados['local']) ?>"
            placeholder="Digite o local do evento"
            required
        >

        <label>Quantidade de vagas:</label>
        <input
            type="number"
            name="vagas"
            min="1"
            max="300"
            value="<?= htmlspecialchars($dados['vagas'] === false ? '' : (string) $dados['vagas']) ?>"
            placeholder="Máximo de 300 vagas"
            required
        >

        <br>

        <button type="submit">Salvar Alterações</button>

        <a href="<?= BASE_URL ?>/eventos/index.php">
            <button type="button">Voltar</button>
        </a>

    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const formulario = document.querySelector('form');

    if (!formulario) return;

    formulario.setAttribute('novalidate', 'novalidate');

    formulario.addEventListener('submit', function (evento) {

        const campos = formulario.querySelectorAll('[required]');
        let primeiroInvalido = null;

        document.querySelectorAll('.mensagem-validacao').forEach(msg => msg.remove());

        campos.forEach(campo => {

            campo.classList.remove('campo-invalido');

            if (!campo.checkValidity() && !primeiroInvalido) {
                primeiroInvalido = campo;
            }

        });

        if (primeiroInvalido) {

            evento.preventDefault();

            primeiroInvalido.classList.add('campo-invalido');

            const mensagem = document.createElement('div');
            mensagem.className = 'mensagem-validacao visivel';

            mensagem.textContent = primeiroInvalido.validity.valueMissing
                ? 'Por favor, preencha este campo.'
                : primeiroInvalido.validity.rangeOverflow
                    ? 'A quantidade máxima permitida é de 300 vagas.'
                    : primeiroInvalido.validity.rangeUnderflow
                        ? 'A quantidade mínima permitida é de 1 vaga.'
                        : 'Verifique se o valor informado está correto.';

            primeiroInvalido.insertAdjacentElement('afterend', mensagem);

            primeiroInvalido.focus();
        }

    });

    formulario.querySelectorAll('[required]').forEach(campo => {

        campo.addEventListener('input', function () {

            this.classList.remove('campo-invalido');

            const mensagem = this.nextElementSibling;

            if (
                mensagem &&
                mensagem.classList.contains('mensagem-validacao')
            ) {
                mensagem.remove();
            }

        });

    });

});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
