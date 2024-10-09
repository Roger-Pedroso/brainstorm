<?php
session_start();

// Receber os dados enviados via URL
if (isset($_GET['id']) && isset($_GET['name']) && isset($_GET['email'])) {
    $_SESSION['user_id'] = $_GET['id'];
    $_SESSION['user_name'] = urldecode($_GET['name']);
    $_SESSION['user_email'] = urldecode($_GET['email']);
    $_SESSION['user_profile_picture'] = urldecode($_GET['profile_picture']);
    
    // Redirecionar para a página principal ou de perfil
    header("Location: menu.php");
    exit();
} else {
    // Se faltar algum dado, redirecionar para a página de login
    header("Location: index.php");
    exit();
}
