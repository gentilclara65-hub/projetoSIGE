
<?php

require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$inscricao = new Inscricao();
$inscricoes = $inscricao->listar();

$busca = isset($_GET['busca']) && is_string($_GET['busca'])
    ? trim($_GET['busca'])
    : '';

if ($busca !== '') {

    $inscricoes = array_values(array_filter($inscricoes, function ($item) use ($busca) {

        $participante = (string) ($item['participante'] ?? '');
        $evento = (string) ($item['evento'] ?? '');

        if (function_exists('mb_stripos')) {
            return mb_stripos($participante, $busca, 0, 'UTF-8') !== false
                || mb_stripos($evento, $busca, 0, 'UTF-8') !== false;
        }

        return stripos($participante, $busca) !== false
            || stripos($evento, $busca) !== false;
    }));
}

require_once __DIR__ . '/../includes/header.php';

?>

<div class="card">

    <h2>Gerenciamento de Inscrições</h2>

    <p>
        Nesta área você pode consultar as inscrições dos participantes
        nos eventos acadêmicos.
    </p>

    <a href="<?= BASE_URL ?>/inscricoes/cadastrar.php">
        <button type="button">+ Nova Inscrição</button>
    </a>

</div>

<div class="card">

    <h2>Lista de Inscrições</h2>

    <!-- CAMPO DE PESQUISA -->
    <form method="GET"
          action="<?= BASE_URL ?>/inscricoes/index.php"
          class="sige-search-form">

        <div class="sige-search-field">

            <input
                type="search"
                name="busca"
                placeholder="Pesquisar por participante ou evento..."
                value="<?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?>"
                class="sige-search-input"
                aria-label="Pesquisar inscrições"
            >

            <button
                type="submit"
                class="sige-search-button"
                aria-label="Pesquisar"
                title="Pesquisar inscrições"
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

            <a href="<?= BASE_URL ?>/inscricoes/index.php"
               class="sige-search-clear">
                Limpar pesquisa
            </a>

        <?php endif; ?>

    </form>

    <br>

    <?php if (empty($inscricoes)): ?>

        <?php if ($busca !== ''): ?>

            <p>
                Nenhuma inscrição encontrada para:
                <strong><?= htmlspecialchars($busca, ENT_QUOTES, 'UTF-8') ?></strong>
            </p>

        <?php else: ?>

            <p>Nenhuma inscrição cadastrada.</p>

        <?php endif; ?>

    <?php else: ?>

        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Participante</th>
                    <th>Evento</th>
                    <th>Data da Inscrição</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($inscricoes as $item): ?>

                    <tr>

                        <td>
                            <?= htmlspecialchars((string)$item['id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)$item['participante']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)$item['evento']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)$item['data_inscricao']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars((string)$item['status']) ?>
                        </td>

                        <td>

                            <?php if ($item['status'] === 'Ativa'): ?>

                                <a href="<?= BASE_URL ?>/inscricoes/cancelar.php?id=<?= (int)$item['id'] ?>">
                                    Cancelar
                                </a>

                            <?php else: ?>

                                <span>Sem ações</span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            </tbody>

        </table>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
