
<?php

// Configurações iniciais do sistema

// Iniciar sessão, caso ainda não esteja iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carregar conexão com o banco de dados
require_once __DIR__ . '/../config/Database.php';

// Carregar classes principais
require_once __DIR__ . '/../classes/Evento.php';
require_once __DIR__ . '/../classes/Participante.php';
require_once __DIR__ . '/../classes/Inscricao.php';

// Configuração de fuso horário
date_default_timezone_set('America/Sao_Paulo');

// Nome do sistema
define('NOME_SISTEMA', 'SIGE');

// Endereço principal do projeto
define('BASE_URL', '/projetoSIGE');
