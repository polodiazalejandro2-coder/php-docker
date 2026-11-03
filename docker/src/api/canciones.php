<?php

require_once __DIR__ . '/../lib/sesion.php';
require_once __DIR__ . '/../lib/canciones.php';
require_once __DIR__ . '/../lib/almacen-json.php';
require_once __DIR__ . '/../lib/vista.php';

header('Content-Type: application/json; charset=utf-8');
if (
    !isset($_SESSION['usuario_id'])
    || !is_int($_SESSION['usuario_id'])
    || $_SESSION['usuario_id'] < 1
) {
    http_response_code(401);
    echo json_encode(['error' => 'Autenticación requerida.'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    exit;
}

try {
    $songs = leerCancionesJson();
    echo json_encode($songs, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
} catch (JsonException | RuntimeException $error) {
    registrarErrorAplicacion('Serving the song API', $error);
    http_response_code(500);
    echo json_encode(['error' => 'No se pudo leer el catálogo.'], JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
}
