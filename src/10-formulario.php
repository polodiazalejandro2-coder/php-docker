<?php

require_once __DIR__ . '/lib/canciones.php';

$values = [
    'title' => '',
    'artist' => '',
    'category' => '',
    'duration' => '',
    'src' => '',
];
$errors = [];
$validPreview = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = validarDatosCancion($_POST);
    $values = $result['values'];
    $errors = $result['errors'];
    $validPreview = $errors === [];

    if (!$validPreview) {
        http_response_code(400);
    }
} elseif ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET, POST');
    http_response_code(405);
    exit('Método no permitido.');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Vista previa de canción</title>
</head>
<body>
    <main>
        <h1>Vista previa de canción</h1>
        <form method="post">
            <?php renderizarCamposCancion($values, $errors) ?>
            <button type="submit">Revisar ficha</button>
        </form>

        <?php if ($validPreview) { ?>
            <section aria-labelledby="preview-title">
                <h2 id="preview-title">Vista previa, canción no guardada</h2>
                <p><strong><?= escapar($values['title']) ?></strong> — <?= escapar($values['artist']) ?></p>
                <p><?= escapar($values['category']) ?> · <?= escapar(duracionLegible((int) $values['duration'])) ?></p>
                <p>Audio: <?= escapar($values['src']) ?></p>
            </section>
        <?php } ?>
    </main>
</body>
</html>
