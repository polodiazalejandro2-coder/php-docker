<?php

require_once __DIR__ . '/lib/canciones.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Funciones de duración</title>
</head>
<body>
    <p>60 segundos: <?= escapar(duracionLegible(60)) ?></p>
    <p>185 segundos: <?= escapar(duracionLegible(185)) ?></p>
</body>
</html>
