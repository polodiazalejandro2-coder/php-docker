<?php

require_once __DIR__ . '/lib/canciones.php';
$songs = require __DIR__ . '/lib/catalogo-inicial.php';
$allowedCategories = ['todas', ...CATEGORIAS_CANCION];
$category = $_GET['category'] ?? 'todas';

if (!is_string($category) || !in_array($category, $allowedCategories, true)) {
    http_response_code(400);
    $invalidCategory = true;
} else {
    $invalidCategory = false;
    if ($category !== 'todas') {
        $songs = array_values(array_filter(
            $songs,
            static fn (array $song): bool => $song['category'] === $category
        ));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Filtrar el catálogo</title>
</head>
<body>
    <h1>Catálogo por categoría</h1>
    <?php if ($invalidCategory) { ?>
        <p>Categoría no válida.</p>
    <?php } elseif ($songs === []) { ?>
        <p>Todavía no hay canciones para esta categoría.</p>
    <?php } else { ?>
        <ul>
            <?php foreach ($songs as $song) { ?>
                <li><?= escapar($song['title']) ?> — <?= escapar($song['artist']) ?></li>
            <?php } ?>
        </ul>
    <?php } ?>
</body>
</html>
