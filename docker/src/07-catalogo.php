<?php

require_once __DIR__ . '/lib/canciones.php';
$songs = require __DIR__ . '/lib/catalogo-inicial.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Catálogo de canciones</title>
</head>
<body>
    <h1>Catálogo de canciones</h1>
    <?php if ($songs === []) { ?>
        <p>Todavía no hay canciones.</p>
    <?php } else { ?>
        <ul>
            <?php foreach ($songs as $song) { ?>
                <li>
                    <strong><?= escapar($song['title']) ?></strong>
                    — <?= escapar($song['artist']) ?>,
                    <?= escapar(duracionLegible($song['duration'])) ?>
                </li>
            <?php } ?>
        </ul>
    <?php } ?>
</body>
</html>
