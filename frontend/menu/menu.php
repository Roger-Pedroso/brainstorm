<?php
// Função para buscar tópicos
function buscarTopicos() {
    $url = 'http://localhost:3000/topicos'; // URL da API para buscar tópicos
    $response = file_get_contents($url);
    return json_decode($response, true);
}

$topicos = buscarTopicos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tópicos</title>
    <link rel="stylesheet" href="sad.css">
</head>
<body>
    <h1>Lista de Tópicos</h1>

    <form action="/topico/cadastrar_topico.php" method="POST">
        <input class="register-topic-input" type="text" name="titulo" placeholder="Título do Tópico" required>
        <button class="register-topic"type="submit">Cadastrar</button>
    </form>

    <ul>
        <?php foreach ($topicos as $topico): ?>
            <li>
                <a href="/topico/topico.php?id=<?= $topico['id'] ?>&titulo=<?= $topico['titulo'] ?>">
                    <div class="topic-card">
                        <h2><?= htmlspecialchars($topico['titulo']) ?></h2>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
