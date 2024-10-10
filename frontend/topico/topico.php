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

// Verifica se o ID do tópico foi passado na URL
if (!isset($_GET['id'])) {
    die("ID do tópico não fornecido.");
}

$topico_id = $_GET['id'];
$ideias = buscarIdeias($topico_id);
$ideiasCurtidas = buscarMeusLikes($topico_id, $_SESSION['user_id']);

$ideiasFiltered = [];

function comparaArrays($id, $segundo_array) {
    foreach ($segundo_array as $item) {
        if ($item['ideia_id'] == $id) {
            return true; // Encontrado
        }
    }
    return false; // Não encontrado
}

// Percorre o primeiro array
foreach ($ideias as $ideia) {
    // Verifica se o 'id' do primeiro array está presente no segundo array
    if (comparaArrays($ideia['id'], $ideiasCurtidas)) {
        // Adiciona o ideia com a propriedade 'liked' => true
        $ideia['liked'] = true;
    } else {
        // Adiciona o ideia com a propriedade 'liked' => false
        $ideia['liked'] = false;
    }
    
    // Adiciona o ideia ao terceiro array
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
    <h1>Ideias do Tópico <?= htmlspecialchars($topico_id) ?></h1>

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
                // Copia o array original novamente para ordenar por created_at
                $ideias_ultimas = $ideias;
        
                // Ordena as ideias pela data de criação
                usort($ideias_ultimas, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
        
                foreach ($ideias_ultimas as $ideia): ?>
                    <li>
                        <?= htmlspecialchars($ideia['titulo']) ?> - Likes: <?= htmlspecialchars($ideia['likes']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="ranking">
            <h2>Ranking das Ideias Mais Votadas</h2>
            <ul>
                <?php 
                // Copia o array original para ordenar por likes
                $ideias_votadas = $ideias;
                $jsonArray = json_encode($ideias_votadas);
                $jsonArray2 = json_encode($ideiasCurtidas);
                $jsonArray3 = json_encode($ideiasFiltered);
                echo "<script>console.log('$jsonArray')</script>";
                // echo "<script>console.log('$ideiasCurtidas')</script>";
                echo "<script>console.log('$jsonArray2')</script>";
                echo "<script>console.log('\n\n\n\n\n')</script>";
                echo "<script>console.log('$jsonArray3')</script>";
                // Ordena as ideias por likes
                usort($ideias_votadas, function($a, $b) {
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
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="/ideia/like_ideia.php" method="POST">
                                <input type="hidden" name="ideia_id" value="<?= $ideia['id'] ?>">
                                <input type="hidden" name="topico_id" value="<?= $ideia['topico_id'] ?>">
                                    <button type="submit">
                                        <i class="fa-regular fa-thumbs-up"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                            <?= htmlspecialchars($ideia['likes']) ?>
                            LIKED: <?= htmlspecialchars($ideia['liked']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>   
    </div>
</body>
</html>
