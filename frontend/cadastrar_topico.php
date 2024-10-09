<?php

session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para cadastrar um tópico');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $user_id = $_SESSION['user_id'];

    $url = 'http://api:3000/topicos';

    $data = json_encode(['titulo' => $titulo, 'user_id' => $user_id]);

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => $data,
        ],
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result) {
        header('Location: menu.php'); // Redirecionar para a lista de tópicos
        exit;
    } else {
        echo "Erro ao cadastrar o tópico.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Tópico</title>
</head>
<body>
    <h1>Cadastrar Novo Tópico</h1>
    <form action="" method="POST">
        <input type="text" name="titulo" placeholder="Título do Tópico" required>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
