<?php

$duration = 185;
$minutes = intdiv($duration, 60);
$seconds = $duration % 60;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Duración de una canción</title>
</head>
<body>
    <p><?= $minutes ?> minutos y <?= $seconds ?> segundos.</p>
</body>
</html>
