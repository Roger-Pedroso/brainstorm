<?php

session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para cadastrar um tópico');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $user_id = $_SESSION['user_id'];

    $url = 'http://localhost:3000/topicos';

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
        header('Location: /menu/menu.php'); // Redirecionar para a lista de tópicos
        exit;
    } else {
        echo "Erro ao cadastrar o tópico.";
    }
}
?>

