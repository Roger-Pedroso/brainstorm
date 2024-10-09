<?php
// Função para buscar ideias por tópico
function buscarIdeias($topico_id) {
    $url = "http://api:3000/ideias/$topico_id"; // URL da API para buscar ideias
    $response = file_get_contents($url);
    return json_decode($response, true);
}

// Verifica se o ID do tópico foi passado na URL
if (!isset($_GET['id'])) {
    die("ID do tópico não fornecido.");
}

$topico_id = $_GET['id'];
$ideias = buscarIdeias($topico_id);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ideias do Tópico</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Ideias do Tópico <?= htmlspecialchars($topico_id) ?></h1>

    <h2>Cadastrar Nova Ideia</h2>
    <form action="cadastrar_ideia.php" method="POST">
        <input type="hidden" name="topico_id" value="<?= $topico_id ?>">
        <input type="text" name="titulo" placeholder="Título da Ideia" required>
        <button type="submit">Cadastrar Ideia</button>
    </form>

    <h2>Ranking das Ideias Mais Votadas</h2>
    <ul>
        <?php 
        // Copia o array original para ordenar por likes
        $ideias_votadas = $ideias;

        // Ordena as ideias por likes
        usort($ideias_votadas, function($a, $b) {
            return $b['likes'] <=> $a['likes'];
        });

        foreach ($ideias_votadas as $ideia): ?>
            <li>
                <?= htmlspecialchars($ideia['titulo']) ?> - Likes: <?= htmlspecialchars($ideia['likes']) ?>
            </li>
        <?php endforeach; ?>
    </ul>

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

</body>
</html>
