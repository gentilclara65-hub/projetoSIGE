
<?php
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/proteger.php';

$database = new Database();
$conexao = $database->conectar();

$totalEventos = 0;
$totalParticipantes = 0;
$totalInscricoes = 0;
$inscricoesAtivas = 0;
$inscricoesCanceladas = 0;

$relatoriosEventos = [];
$relatorioParticipantes = [];
$mensagem = '';

try {

    // Resumo geral
    $totalEventos = $conexao->query(
        "SELECT COUNT(*) FROM eventos"
    )->fetchColumn();

    $totalParticipantes = $conexao->query(
        "SELECT COUNT(*) FROM participantes"
    )->fetchColumn();

    $totalInscricoes = $conexao->query(
        "SELECT COUNT(*) FROM inscricoes"
    )->fetchColumn();

    $inscricoesAtivas = $conexao->query(
        "SELECT COUNT(*) FROM inscricoes WHERE status = 'Ativa'"
    )->fetchColumn();

    $inscricoesCanceladas = $conexao->query(
        "SELECT COUNT(*) FROM inscricoes WHERE status = 'Cancelada'"
    )->fetchColumn();

    // Relatório de inscrições e ocupação por evento
    $sqlEventos = "
        SELECT
            e.id,
            e.nome,
            e.vagas,
            COUNT(CASE WHEN i.status = 'Ativa' THEN 1 END) AS ocupadas,
            COUNT(CASE WHEN i.status = 'Cancelada' THEN 1 END) AS canceladas,
            GREATEST(
                COALESCE(e.vagas, 0) -
                COUNT(CASE WHEN i.status = 'Ativa' THEN 1 END),
                0
            ) AS disponiveis
        FROM eventos e
        LEFT JOIN inscricoes i ON i.evento_id = e.id
        GROUP BY e.id, e.nome, e.vagas
        ORDER BY e.id DESC
    ";

    $relatoriosEventos = $conexao->query($sqlEventos)->fetchAll(PDO::FETCH_ASSOC);

    // Relação de participantes inscritos
    $sqlParticipantes = "
        SELECT
            p.nome AS participante,
            p.email,
            e.nome AS evento,
            i.data_inscricao,
            i.status
        FROM inscricoes i
        INNER JOIN participantes p ON i.participante_id = p.id
        INNER JOIN eventos e ON i.evento_id = e.id
        ORDER BY i.id DESC
    ";

    $relatorioParticipantes = $conexao->query($sqlParticipantes)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $erro) {
    $mensagem = 'Não foi possível carregar os relatórios.';
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="card">
    <h2>Relatórios do Sistema</h2>

    <p>
        Consulte os dados gerais dos eventos acadêmicos,
        participantes e inscrições cadastradas no SIGE.
    </p>
</div>

<?php if ($mensagem): ?>

    <div class="card">
        <p><?= htmlspecialchars($mensagem) ?></p>
    </div>

<?php else: ?>

    <div class="card">
        <h3>Resumo Geral</h3>

        <table>
            <tr>
                <th>Informação</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>Eventos cadastrados</td>
                <td><?= (int) $totalEventos ?></td>
            </tr>
            <tr>
                <td>Participantes cadastrados</td>
                <td><?= (int) $totalParticipantes ?></td>
            </tr>
            <tr>
                <td>Total de inscrições</td>
                <td><?= (int) $totalInscricoes ?></td>
            </tr>
            <tr>
                <td>Inscrições ativas</td>
                <td><?= (int) $inscricoesAtivas ?></td>
            </tr>
            <tr>
                <td>Inscrições canceladas</td>
                <td><?= (int) $inscricoesCanceladas ?></td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h3>Inscrições e Ocupação por Evento</h3>

        <?php if (empty($relatoriosEventos)): ?>

            <p>Nenhum evento cadastrado até o momento.</p>

        <?php else: ?>

            <table>
                <tr>
                    <th>Evento</th>
                    <th>Vagas</th>
                    <th>Ocupadas</th>
                    <th>Disponíveis</th>
                    <th>Canceladas</th>
                </tr>

                <?php foreach ($relatoriosEventos as $evento): ?>
                    <tr>
                        <td><?= htmlspecialchars($evento['nome']) ?></td>
                        <td><?= (int) $evento['vagas'] ?></td>
                        <td><?= (int) $evento['ocupadas'] ?></td>
                        <td><?= (int) $evento['disponiveis'] ?></td>
                        <td><?= (int) $evento['canceladas'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>

        <?php endif; ?>
    </div>

    <div class="card">
        <h3>Participantes Inscritos</h3>

        <?php if (empty($relatorioParticipantes)): ?>

            <p>Nenhuma inscrição registrada até o momento.</p>

        <?php else: ?>

            <table>
                <tr>
                    <th>Participante</th>
                    <th>E-mail</th>
                    <th>Evento</th>
                    <th>Data da Inscrição</th>
                    <th>Status</th>
                </tr>

                <?php foreach ($relatorioParticipantes as $registro): ?>
                    <tr>
                        <td><?= htmlspecialchars($registro['participante']) ?></td>
                        <td><?= htmlspecialchars($registro['email']) ?></td>
                        <td><?= htmlspecialchars($registro['evento']) ?></td>
                        <td><?= htmlspecialchars($registro['data_inscricao'] ?? '') ?></td>
                        <td><?= htmlspecialchars($registro['status']) ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>

        <?php endif; ?>
    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
