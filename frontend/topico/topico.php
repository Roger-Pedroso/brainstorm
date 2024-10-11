<?php
session_start();

function buscarIdeias($topico_id) {
    $url = "http://localhost:3000/ideias/$topico_id"; // URL da API para buscar ideias
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function buscarMeusLikes($topico_id, $user_id) {
    $url = "http://localhost:3000/ideias/liked?user_id=$user_id&topico_id=$topico_id"; // URL da API para buscar ideias
    $response = file_get_contents($url);
    return json_decode($response, true);
}

function participantesDoTopico($topico_id) {
    $url = "http://localhost:3000/topicos/$topico_id/participantes"; // URL da API para buscar ideias
    $response = file_get_contents($url);
    return json_decode($response, true);
}

if (!isset($_GET['id'])) {
    die("ID do tópico não fornecido.");
}

$topico_id = $_GET['id'];

function topicoInfo ($topico_id) {
    $url = "http://localhost:3000/topicos/$topico_id"; // URL da API para buscar ideias
    $response = file_get_contents($url);
    return json_decode($response, true);
}
$topico_info = topicoInfo($topico_id);

$ideias = buscarIdeias($topico_id);
$ideiasCurtidas = buscarMeusLikes($topico_id, $_SESSION['user_id']);
$participantes = participantesDoTopico($topico_id);

$ideiasFiltered = [];

function comparaArrays($id, $segundo_array) {
    foreach ($segundo_array as $item) {
        if ($item['ideia_id'] == $id) {
            return true; // Encontrado
        }
    }
    return false; // Não encontrado
}

foreach ($ideias as $ideia) {
    if (comparaArrays($ideia['id'], $ideiasCurtidas)) {
        $ideia['liked'] = true;
    } else {
        $ideia['liked'] = false;
    }
    
    $ideiasFiltered[] = $ideia;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ideias do Tópico</title>
    <link rel="stylesheet" href="topico_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <button><a href="/menu/menu.php">Voltar</a></button>
    <h1>Ideias do Tópico <?= htmlspecialchars($topico_info['titulo']) ?></h1>

    <h2>Cadastrar Nova Ideia</h2>
    <form action="/ideia/cadastrar_ideia.php" method="POST">
        <input type="hidden" name="topico_id" value="<?= $topico_id ?>">
        <input type="text" name="titulo" placeholder="Título da Ideia" required>
        <button type="submit">Cadastrar Ideia</button>
    </form>

    <div class="ideas">
        <div class="last-ideas">
            <h2>Últimas Ideias Adicionadas</h2>
            <ul>
                <?php 
                $ideias_ultimas = $ideias;
        
                usort($ideias_ultimas, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
        
                foreach ($ideias_ultimas as $ideia): ?>
                    <li>
                        <?= htmlspecialchars($ideia['titulo']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="ranking">
            <h2>Ranking das Ideias Mais Votadas</h2>
            <ul>
                <?php 

                usort($ideiasFiltered, function($a, $b) {
                    return $b['likes'] <=> $a['likes'];
                });
                
                foreach ($ideiasFiltered as $ideia): ?>
                    <li>
                        <div class="like_button">
                            <?= htmlspecialchars($ideia['titulo']) ?>
                            <?php if (isset($ideia['liked']) && $ideia['liked'] === true): ?>
                                <form action="/ideia/like_ideia.php" method="POST">
                                <input type="hidden" name="ideia_id" value="<?= $ideia['id'] ?>">
                                <input type="hidden" name="topico_id" value="<?= $ideia['topico_id'] ?>">
                                    <button type="submit">
                                        Descurtir
                                        <i class="fa-solid fa-thumbs-down"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="/ideia/like_ideia.php" method="POST">
                                <input type="hidden" name="ideia_id" value="<?= $ideia['id'] ?>">
                                <input type="hidden" name="topico_id" value="<?= $ideia['topico_id'] ?>">
                                    <button type="submit">
                                        Curtir
                                        <i class="fa-regular fa-thumbs-up"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            Likes: <?= htmlspecialchars($ideia['likes']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div> 
        <div class="liked">
            <h2>Ideias Votadas</h2>
            <ul>
                <?php 
                $ideias_curtidas = $ideiasCurtidas;
                
                foreach ($ideias_curtidas as $ideia): ?>
                    <li>
                        <div class="like_button">
                            <?= htmlspecialchars($ideia['titulo']) ?>
                                <form action="/ideia/like_ideia.php" method="POST">
                                <input type="hidden" name="ideia_id" value="<?= $ideia['ideia_id'] ?>">
                                <input type="hidden" name="topico_id" value="<?= $ideia['topico_id'] ?>">
                                    <button type="submit">
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    </button>
                                </form>
                            <?= htmlspecialchars($ideia['likes']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div> 
        <div class="participants">
            <h2>Participantes</h2>
            <ul>
                <?php 
                $_participantes = $participantes;
                
                foreach ($_participantes as $user): ?>
                    <li>
                        <div class="participant">
                            <?= htmlspecialchars($user['name']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div> 
    </div>
</body>
</html>
