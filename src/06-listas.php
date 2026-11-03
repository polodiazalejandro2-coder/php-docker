<?php
$titles = ['Sesión de prueba', 'Ritmo nocturno', 'Tema de película'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Listas del jukebox</title>
</head>
<body>
    <h1>Contador con for</h1>
    <ol>
        <?php for ($position = 1; $position <= 3; $position++) { ?>
            <li><?= $position ?></li>
        <?php } ?>
    </ol>
    <h2>Contador con while</h2>
    <ol>
        <?php
        $position = 1;
        while ($position <= 3) {
            ?>
            <li><?= $position ?></li>
            <?php
            $position++;
        }
        ?>
    </ol>
    <h2>Títulos</h2>
    <ul>
        <?php foreach ($titles as $title) { ?>
            <li><?= htmlspecialchars($title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></li>
        <?php } ?>
    </ul>
</body>
</html>
