
<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$participante = new Participante();
$participantes = $participante->listar();

$busca = isset($_GET['busca']) && is_string($_GET['busca'])
    ? trim($_GET['busca'])
    : '';

if ($busca !== '') {

    $participantes = array_values(array_filter($participantes, function ($item) use ($busca) {

        $nome = (string) ($item['nome'] ?? '');
        $email = (string) ($item['email'] ?? '');

        if (function_exists('mb_stripos')) {
            return mb_stripos($nome, $busca, 0, 'UTF-8') !== false
                || mb_stripos($email, $busca, 0, 'UTF-8') !== false;
        }

        return stripos($nome, $busca) !== false
            || stripos($email, $busca) !== false;
    }));
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">

    <h2>Gerenciamento de Participantes</h2>

    <p>Consulte e gerencie os participantes cadastrados no sistema.</p>

    <br>

    <a href="<?= BASE_URL ?>/participantes/cadastrar.php">
        <button type="button">+ Cadastrar Participante</button>
    </a>

    <br><br>

    <!-- CAMPO DE PESQUISA -->
    <form method="GET"
          action="<?= BASE_URL ?>/participantes/index.php"
          class="sige-search-form">

        <div class="sige-search-field">

            <input
                type="search"
                name="busca"
                placeholder="Pesquisar participante por nome ou e-mail..."
                value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>"
                class="sige-search-input"
                aria-label="Pesquisar participantes"
            >

            <button
                type="submit"
                class="sige-search-button"
                aria-label="Pesquisar"
                title="Pesquisar participantes"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="21"
                     height="21"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">

                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.35-4.35"></path>

                </svg>
            </button>

        </div>

        <?php if ($busca !== ''): ?>
            <a href="<?= BASE_URL ?>/participantes/index.php"
               class="sige-search-clear">
                Limpar pesquisa
            </a>
        <?php endif; ?>

    </form>

    <br>

    <?php if (count($participantes) > 0): ?>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($participantes as $item): ?>

                    <tr>
                        <td><?= htmlspecialchars((string)$item['id']) ?></td>

                        <td><?= htmlspecialchars((string)$item['nome']) ?></td>

                        <td><?= htmlspecialchars((string)$item['email']) ?></td>

                        <td><?= htmlspecialchars((string)($item['telefone'] ?? '')) ?></td>

                        <td>
                            <a href="<?= BASE_URL ?>/participantes/editar.php?id=<?= (int)$item['id'] ?>">
                                Editar
                            </a>

                            |

                            <a href="<?= BASE_URL ?>/participantes/excluir.php?id=<?= (int)$item['id'] ?>">
                                Excluir
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php elseif ($busca !== ''): ?>

        <p>
            Nenhum participante encontrado para:
            <strong><?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?></strong>
        </p>

    <?php else: ?>

        <p>Nenhum participante cadastrado até o momento.</p>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
