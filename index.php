
<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/proteger.php';

$evento = new Evento();
$participante = new Participante();
$inscricao = new Inscricao();

$totalEventos = count($evento->listar());
$totalParticipantes = count($participante->listar());
$totalInscricoes = count($inscricao->listar());

require_once __DIR__ . '/includes/header.php';
?>

<section class="dashboard-welcome">
    <div>
        <span class="welcome-tag">PAINEL DE CONTROLE</span>
        <h1>Bem-vindo ao SIGE!</h1>
        <p>Gerencie seus eventos acadêmicos de forma simples e organizada.</p>
    </div>
    <div class="welcome-decoration">SIGE</div>
</section>

<section class="dashboard-stats">

    <div class="stat-card">
        <div class="stat-icon">▣</div>
        <div>
            <p>Total de eventos</p>
            <h2><?= $totalEventos ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">♙</div>
        <div>
            <p>Participantes</p>
            <h2><?= $totalParticipantes ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">▤</div>
        <div>
            <p>Inscrições</p>
            <h2><?= $totalInscricoes ?></h2>
        </div>
    </div>

</section>

<section class="dashboard-section">
    <div class="section-heading">
        <div>
            <span class="welcome-tag">ACESSO RÁPIDO</span>
            <h2>Gerenciamento do sistema</h2>
        </div>
        <p>Escolha um módulo para continuar.</p>
    </div>

    <div class="quick-actions">

        <a href="<?= BASE_URL ?>/eventos/index.php" class="action-card">
            <span class="action-icon">▣</span>
            <h3>Eventos</h3>
            <p>Cadastre, consulte, edite e exclua eventos acadêmicos.</p>
            <span class="action-link">Acessar módulo →</span>
        </a>

        <a href="<?= BASE_URL ?>/participantes/index.php" class="action-card">
            <span class="action-icon">♙</span>
            <h3>Participantes</h3>
            <p>Gerencie os dados dos participantes cadastrados.</p>
            <span class="action-link">Acessar módulo →</span>
        </a>

        <a href="<?= BASE_URL ?>/inscricoes/index.php" class="action-card">
            <span class="action-icon">▤</span>
            <h3>Inscrições</h3>
            <p>Consulte e gerencie as inscrições dos eventos.</p>
            <span class="action-link">Acessar módulo →</span>
        </a>

        <a href="<?= BASE_URL ?>/relatorios/index.php" class="action-card">
            <span class="action-icon">▥</span>
            <h3>Relatórios</h3>
            <p>Acompanhe os dados e indicadores do sistema.</p>
            <span class="action-link">Acessar módulo →</span>
        </a>

    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
