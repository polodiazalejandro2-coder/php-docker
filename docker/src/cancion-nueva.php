<?php

require_once __DIR__ . '/lib/acceso.php';
require_once __DIR__ . '/lib/canciones.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/vista.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: GET, POST');
    http_response_code(405);
    exit('Método no permitido.');
}

$values = ['title' => '', 'artist' => '', 'category' => '', 'duration' => '', 'src' => ''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf($_POST['csrf'] ?? null);
    $result = validarDatosCancion($_POST);
    $values = $result['values'];
    $errors = $result['errors'];

    if ($errors === []) {
        try {
            actualizarCancionesJson(
                static function (array &$songs) use ($values): bool {
                    $songs[] = [
                        'id' => siguienteId($songs),
                        'title' => $values['title'],
                        'artist' => $values['artist'],
                        'category' => $values['category'],
                        'duration' => (int) $values['duration'],
                        'src' => $values['src'],
                    ];
                    return true;
                }
            );
            header('Location: canciones.php', true, 303);
            exit;
        } catch (JsonException | RuntimeException $error) {
            registrarErrorAplicacion('Creating a song', $error);
            http_response_code(500);
            $errors['form'] = 'No se pudo guardar la canción.';
        }
    } else {
        http_response_code(400);
    }
}

iniciarPagina('Añadir canción');
if (isset($errors['form'])) {
    echo '<p role="alert">' . escapar($errors['form']) . '</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
    <?php renderizarCamposCancion($values, $errors) ?>
    <button type="submit">Guardar canción</button>
    <a href="canciones.php">Cancelar</a>
</form>
<?php finalizarPagina(); ?>
