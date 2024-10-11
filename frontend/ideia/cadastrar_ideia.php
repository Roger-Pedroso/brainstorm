<?php
session_start();

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para cadastrar um tópico');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topico_id = $_POST['topico_id'];
    $titulo = $_POST['titulo'];
    $user_id = $_SESSION['user_id'];

    $url = 'http://api:3000/ideias';

    $data = json_encode(['topico_id' => $topico_id, 'titulo' => $titulo, 'user_id' => $user_id]);

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
        header("Location: /topico/topico.php?id=$topico_id"); // Redirecionar para a lista de ideias do tópico
        exit;
    } else {
        echo "Erro ao cadastrar a ideia.";
    }
}
?>
