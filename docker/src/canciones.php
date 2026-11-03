<?php

require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/canciones.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/vista.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    http_response_code(405);
    exit('Método no permitido.');
}

try {
    $songs = leerCancionesJson();
} catch (JsonException | RuntimeException $error) {
    registrarErrorAplicacion('Reading the song catalog', $error);
    http_response_code(500);
    exit('No se pudo leer el catálogo.');
}

iniciarPagina('Catálogo de canciones');
?>
<nav>
    <a href="home.php">Inicio</a>
    <a href="cancion-nueva.php">Añadir canción</a>
</nav>
<?php if ($songs === []) { ?>
    <p>Todavía no hay canciones.</p>
<?php } else { ?>
    <ul>
        <?php foreach ($songs as $song) { ?>
            <li>
                <strong><?= escapar($song['title']) ?></strong>
                — <?= escapar($song['artist']) ?> · <?= escapar($song['category']) ?>
                · <?= escapar(duracionLegible($song['duration'])) ?>
                <a href="cancion-editar.php?id=<?= escapar($song['id']) ?>">Editar</a>
                <a href="cancion-eliminar.php?id=<?= escapar($song['id']) ?>">Eliminar</a>
            </li>
        <?php } ?>
    </ul>
<?php } ?>
<?php finalizarPagina(); ?>
