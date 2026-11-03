<?php

$duration = 185;

if ($duration === 0) {
    $status = 'Sin duración';
} elseif ($duration <= 240) {
    $status = 'Hasta cuatro minutos';
} else {
    $status = 'Más de cuatro minutos';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Estado de una canción</title>
</head>
<body>
    <p><?= htmlspecialchars($status, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
</body>
</html>
