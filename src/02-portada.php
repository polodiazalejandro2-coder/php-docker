<?php
$saludo = 'El catálogo musical está en preparación.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Jukebox de la asociación</title>
</head>
<body>
    <main>
        <h1>Jukebox de la asociación</h1>
        <p><?= htmlspecialchars($saludo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <a href="01-bienvenida.php">Ver el saludo inicial</a>
    </main>
</body>
</html>
