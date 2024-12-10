<?php
session_start();  // Inicia a sessão

// Verificar se o usuário está logado
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    // Se o usuário for admin
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin.php");  // Redireciona para o painel de admin
    } else {
        header("Location: quiz.php");  // Redireciona para o quiz do usuário
    }
    exit;
} else {
    // Se o usuário não estiver logado, redireciona para login.php
    header("Location: login.php");  
    exit;
}
?>
