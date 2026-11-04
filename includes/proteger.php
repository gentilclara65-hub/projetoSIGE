<?php
// Proteção de login desativada para acesso direto
if (!isset($_SESSION)) {
    session_start();
}

// Se quiseres forçar um utilizador fictício ativo para evitar erros de $_SESSION['id'] ou $_SESSION['nome']:
if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = 1;
    $_SESSION['nome'] = 'Administrador (Modo Livre)';
    $_SESSION['email'] = 'claracalcantework@gmail.com';
}
?>