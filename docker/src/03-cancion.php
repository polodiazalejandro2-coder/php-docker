<?php

$title = 'Sesión de prueba';
$artist = 'Grupo del aula';
$duration = 185;
$volume = 0.5;
$available = true;
const APP_NAME = 'Jukebox de la asociación';

$safeTitle = htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= APP_NAME ?></title>
</head>
<body>
    <h1><?= $safeTitle ?></h1>
    <p>La canción <?= $safeTitle ?> es de <?= htmlspecialchars($artist, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>.</p>
    <p>Duración de prueba: <?= $duration ?> segundos.</p>
</body>
</html>
