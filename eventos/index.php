
<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$evento = new Evento();
$eventos = $evento->listar();

$busca = trim($_GET['busca'] ?? '');

if ($busca !== '') {
    $eventos = array_values(array_filter($eventos, function ($item) use ($busca) {
        $nome = (string) ($item['nome'] ?? '');

        if (function_exists('mb_stripos')) {
            return mb_stripos($nome, $busca, 0, 'UTF-8') !== false;
        }

        return stripos($nome, $busca) !== false;
    }));
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">

    <h2>Gerenciamento de Eventos</h2>

    <p>Consulte e gerencie os eventos acadêmicos cadastrados no sistema.</p>

    <br>

    <a href="<?= BASE_URL ?>/eventos/cadastrar.php">
        <button type="button">+ Cadastrar Evento</button>
    </a>

    <br><br>

    <!-- CAMPO DE PESQUISA -->
    <form method="GET" action="<?= BASE_URL ?>/eventos/index.php"
          class="sige-search-form">

        <div class="sige-search-field">

            <input
                type="search"
                name="busca"
                placeholder="Pesquisar eventos pelo nome..."
                value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>"
                class="sige-search-input"
                aria-label="Pesquisar eventos pelo nome"
            >

            <button
                type="submit"
                class="sige-search-button"
                aria-label="Pesquisar"
                title="Pesquisar eventos"
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
            <a href="<?= BASE_URL ?>/eventos/index.php"
               class="sige-search-clear">
                Limpar pesquisa
            </a>
        <?php endif; ?>

    </form>

    <br>

    <?php if (count($eventos) > 0): ?>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Evento</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th>Local</th>
                    <th>Vagas</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($eventos as $item): ?>

                    <tr>
                        <td><?= htmlspecialchars($item['id']) ?></td>

                        <td><?= htmlspecialchars($item['nome']) ?></td>

                        <td><?= date('d/m/Y', strtotime($item['data_evento'])) ?></td>

                        <td><?= htmlspecialchars($item['horario']) ?></td>

                        <td><?= htmlspecialchars($item['local']) ?></td>

                        <td><?= htmlspecialchars($item['vagas']) ?></td>

                        <td>
                            <a href="<?= BASE_URL ?>/eventos/editar.php?id=<?= $item['id'] ?>">
                                Editar
                            </a>

                            |

                            <a href="<?= BASE_URL ?>/eventos/excluir.php?id=<?= $item['id'] ?>">
                                Excluir
                            </a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php elseif ($busca !== ''): ?>

        <p>Nenhum evento encontrado para:
            <strong><?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?></strong>
        </p>

    <?php else: ?>

        <p>Nenhum evento cadastrado até o momento.</p>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
