<?php
session_start();
ob_start(); // Inicia o buffer de saída para evitar problemas com headers

// Verifique se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    die('Você precisa estar logado para cadastrar um tópico');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ideia_id = $_POST['ideia_id'];
    $topico_id = $_POST['topico_id'];
    $user_id = $_SESSION['user_id'];

    
    $url = "http://localhost:3000/ideias/{$ideia_id}/like";
    
    $data = json_encode(['ideia_id' => $ideia_id, 'user_id' => $user_id]);
    
    echo $data;
    
    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => $data,
        ],
    ];

    $context = stream_context_create($options);

    // Tentar fazer a requisição HTTP e capturar o erro, se houver
    $result = @file_get_contents($url, false, $context);

    if ($result === FALSE) {
        // Trate o erro de requisição
        error_log('Erro ao acessar a URL: ' . $url);
        error_log('Resposta do servidor: ' . print_r($http_response_header, true));
        echo 'Erro ao acessar a URL: ' . $url;
        echo 'Resposta do servidor: ' . print_r($http_response_header, true);
        // Redireciona mesmo assim ou exibe uma mensagem de erro adequada
        // header("Location: /topico/topico.php?id=$topico_id&error=1");
        exit;
    } else {
        // Se a requisição foi bem-sucedida, redireciona
        header("Location: /topico/topico.php?id=$topico_id");
        exit;
    }
}

ob_end_flush(); // Envia o buffer de saída e finaliza
?>
