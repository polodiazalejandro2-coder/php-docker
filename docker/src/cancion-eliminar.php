<?php

require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/canciones.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/vista.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET' && $method !== 'POST') {
    header('Allow: GET, POST');
    http_response_code(405);
    exit('Método no permitido.');
}

$idInput = $method === 'POST' ? ($_POST['id'] ?? null) : ($_GET['id'] ?? null);
$id = is_string($idInput) ? filter_var($idInput, FILTER_VALIDATE_INT) : false;
if ($id === false || $id < 1) {
    http_response_code(400);
    exit('Identificador no válido.');
}

if ($method === 'POST') {
    exigirCsrf($_POST['csrf'] ?? null);
}

try {
    $songs = leerCancionesJson();
} catch (JsonException | RuntimeException $error) {
    registrarErrorAplicacion('Reading a song before deletion', $error);
    http_response_code(500);
    exit('No se pudo leer la canción.');
}

$song = null;
foreach ($songs as $candidate) {
    if ($candidate['id'] === $id) {
        $song = $candidate;
        break;
    }
}
if ($song === null) {
    http_response_code(404);
    exit('Canción no encontrada.');
}

if ($method === 'POST') {
    try {
        $deleted = actualizarCancionesJson(
            static function (array &$records) use ($id): bool {
                foreach ($records as $position => $record) {
                    if ($record['id'] === $id) {
                        unset($records[$position]);
                        $records = array_values($records);
                        return true;
                    }
                }
                return false;
            }
        );
        if (!$deleted) {
            http_response_code(404);
            exit('Canción no encontrada.');
        }
        header('Location: canciones.php', true, 303);
        exit;
    } catch (JsonException | RuntimeException $error) {
        registrarErrorAplicacion('Deleting a song', $error);
        http_response_code(500);
        exit('No se pudo eliminar la canción.');
    }
}

iniciarPagina('Confirmar eliminación');
?>
<p>¿Quieres eliminar la ficha «<?= escapar($song['title']) ?>»?</p>
<p>El archivo de audio no se borrará.</p>
<form method="post">
    <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
    <input type="hidden" name="id" value="<?= escapar($id) ?>">
    <button type="submit">Eliminar ficha</button>
    <a href="canciones.php">Cancelar</a>
</form>
<?php finalizarPagina(); ?>
