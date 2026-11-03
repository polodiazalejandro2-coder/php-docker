<?php

require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/canciones.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/vista.php';

try {
    $songs = leerCancionesJson();
} catch (JsonException | RuntimeException $error) {
    registrarErrorAplicacion('Reading the JSON catalog', $error);
    http_response_code(500);
    exit('No se pudo leer el catálogo.');
}

iniciarPagina('Catálogo JSON');
if ($songs === []) {
    echo '<p>Todavía no hay canciones.</p>';
} else {
    echo '<ul>';
    foreach ($songs as $song) {
        echo '<li><strong>' . escapar($song['title']) . '</strong> — '
            . escapar($song['artist']) . ', '
            . escapar(duracionLegible($song['duration'])) . '</li>';
    }
    echo '</ul>';
}
finalizarPagina();
