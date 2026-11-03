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
    registrarErrorAplicacion('Reading the song for editing', $error);
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

$values = [
    'title' => $song['title'],
    'artist' => $song['artist'],
    'category' => $song['category'],
    'duration' => (string) $song['duration'],
    'src' => $song['src'],
];
$errors = [];

if ($method === 'POST') {
    $result = validarDatosCancion($_POST);
    $values = $result['values'];
    $errors = $result['errors'];
    if ($errors === []) {
        try {
            $updated = actualizarCancionesJson(
                static function (array &$records) use ($id, $values): bool {
                    foreach ($records as &$record) {
                        if ($record['id'] === $id) {
                            $record = [
                                'id' => $id,
                                'title' => $values['title'],
                                'artist' => $values['artist'],
                                'category' => $values['category'],
                                'duration' => (int) $values['duration'],
                                'src' => $values['src'],
                            ];
                            unset($record);
                            return true;
                        }
                    }
                    unset($record);
                    return false;
                }
            );
            if (!$updated) {
                http_response_code(404);
                exit('Canción no encontrada.');
            }
            header('Location: canciones.php', true, 303);
            exit;
        } catch (JsonException | RuntimeException $error) {
            registrarErrorAplicacion('Updating a song', $error);
            http_response_code(500);
            $errors['form'] = 'No se pudo actualizar la canción.';
        }
    } else {
        http_response_code(400);
    }
}

iniciarPagina('Editar canción');
if (isset($errors['form'])) {
    echo '<p role="alert">' . escapar($errors['form']) . '</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
    <input type="hidden" name="id" value="<?= escapar($id) ?>">
    <?php renderizarCamposCancion($values, $errors) ?>
    <button type="submit">Guardar cambios</button>
    <a href="canciones.php">Cancelar</a>
</form>
<?php finalizarPagina(); ?>
