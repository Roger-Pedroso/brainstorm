<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login com Google</title>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
        <h1>Bem-vindo, <?php echo $_SESSION['user_name']; ?>!</h1>
        <p>Email: <?php echo $_SESSION['user_email']; ?></p>
        <img src="<?php echo $_SESSION['user_profile_picture']; ?>" alt="Avatar" width="100">
    <?php else: ?>
        <h1>Você não está logado</h1>
        <a href="http://localhost:3000/auth/google">
            <button>Login com Google</button>
        </a>
    <?php endif; ?>
</body>
</html>
