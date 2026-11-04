

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SIGE - Gerenciamento de Eventos Acadêmicos</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<div class="app-layout">

    <aside class="sidebar">

        <div class="sidebar-brand">
            <div class="brand-icon">S</div>
            <div>
                <h2>SIGE</h2>
                <span>Gestão Acadêmica</span>
            </div>
        </div>

        <div class="sidebar-menu">

            <p class="menu-title">MENU PRINCIPAL</p>

            <a href="<?= BASE_URL ?>/index.php">
                <span>⌂</span> Início
            </a>

            <a href="<?= BASE_URL ?>/eventos/index.php">
                <span>▣</span> Eventos
            </a>

            <a href="<?= BASE_URL ?>/participantes/index.php">
                <span>♙</span> Participantes
            </a>

            <a href="<?= BASE_URL ?>/inscricoes/index.php">
                <span>▤</span> Inscrições
            </a>

            <a href="<?= BASE_URL ?>/relatorios/index.php">
                <span>▥</span> Relatórios
            </a>

            <a href="<?= BASE_URL ?>/logout.php">
                <span>↪</span> Sair do sistema
            </a>

        </div>

        <div class="sidebar-footer">
            <p>Organizando eventos,</p>
            <p>conectando pessoas.</p>
        </div>

    </aside>

    <div class="app-main">

        <header class="topbar">
            <div>
                <span class="topbar-subtitle">SISTEMA DE GERENCIAMENTO</span>
                <h2>Eventos Acadêmicos</h2>
            </div>

            <div class="topbar-label">
                SIGE
            </div>
        </header>

        <main class="container">