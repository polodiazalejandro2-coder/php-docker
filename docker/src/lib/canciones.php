<?php

const CATEGORIAS_CANCION = ['electronica', 'rock', 'bandas-sonoras'];
const FUENTES_AUDIO_ADMITIDAS = ['audio/demo-01.mp3', 'audio/demo-02.mp3'];

function escapar(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function contarCaracteres(string $value): int
{
    return mb_strlen($value, 'UTF-8');
}

function duracionLegible(int $total): string
{
    if ($total < 0) {
        throw new InvalidArgumentException('La duración no puede ser negativa.');
    }

    $minutes = intdiv($total, 60);
    $seconds = $total % 60;

    return $minutes . ' minutos y ' . $seconds . ' segundos';
}

function validarDatosCancion(array $input): array
{
    $errors = [];
    $values = [];

    foreach (['title' => 'El título', 'artist' => 'El artista'] as $field => $label) {
        $value = $input[$field] ?? null;
        if (!is_string($value)) {
            $errors[$field] = $label . ' debe ser texto.';
            $values[$field] = '';
            continue;
        }

        $value = trim($value);
        $values[$field] = $value;
        if ($value === '') {
            $errors[$field] = $label . ' es obligatorio.';
        } elseif (contarCaracteres($value) > 80) {
            $errors[$field] = $label . ' no puede superar 80 caracteres.';
        }
    }

    $category = $input['category'] ?? null;
    if (!is_string($category) || !in_array($category, CATEGORIAS_CANCION, true)) {
        $errors['category'] = 'Selecciona una categoría válida.';
        $values['category'] = '';
    } else {
        $values['category'] = $category;
    }

    $durationInput = $input['duration'] ?? null;
    if (!is_string($durationInput)) {
        $errors['duration'] = 'La duración debe ser un número entero.';
        $values['duration'] = '';
    } else {
        $duration = filter_var($durationInput, FILTER_VALIDATE_INT);
        $values['duration'] = $duration === false ? $durationInput : (string) $duration;
        if ($duration === false || $duration < 1 || $duration > 1800) {
            $errors['duration'] = 'La duración debe ser un entero entre 1 y 1800 segundos.';
        }
    }

    $src = $input['src'] ?? null;
    if (!is_string($src) || !in_array($src, FUENTES_AUDIO_ADMITIDAS, true)) {
        $errors['src'] = 'Selecciona un audio admitido.';
        $values['src'] = '';
    } else {
        $values['src'] = $src;
    }

    return ['values' => $values, 'errors' => $errors];
}

function renderizarCamposCancion(array $values, array $errors): void
{
    ?>
    <label for="title">Título</label>
    <input id="title" name="title" type="text" maxlength="80" required value="<?= escapar($values['title'] ?? '') ?>">
    <?php if (isset($errors['title'])) { ?><small><?= escapar($errors['title']) ?></small><?php } ?>

    <label for="artist">Artista</label>
    <input id="artist" name="artist" type="text" maxlength="80" required value="<?= escapar($values['artist'] ?? '') ?>">
    <?php if (isset($errors['artist'])) { ?><small><?= escapar($errors['artist']) ?></small><?php } ?>

    <label for="category">Categoría</label>
    <select id="category" name="category" required>
        <option value="">Selecciona una categoría</option>
        <?php foreach (CATEGORIAS_CANCION as $category) { ?>
            <option value="<?= escapar($category) ?>" <?= ($values['category'] ?? '') === $category ? 'selected' : '' ?>>
                <?= escapar($category) ?>
            </option>
        <?php } ?>
    </select>
    <?php if (isset($errors['category'])) { ?><small><?= escapar($errors['category']) ?></small><?php } ?>

    <label for="duration">Duración en segundos</label>
    <input id="duration" name="duration" type="number" min="1" max="1800" required value="<?= escapar($values['duration'] ?? '') ?>">
    <?php if (isset($errors['duration'])) { ?><small><?= escapar($errors['duration']) ?></small><?php } ?>

    <label for="src">Audio</label>
    <select id="src" name="src" required>
        <option value="">Selecciona un audio</option>
        <?php foreach (FUENTES_AUDIO_ADMITIDAS as $src) { ?>
            <option value="<?= escapar($src) ?>" <?= ($values['src'] ?? '') === $src ? 'selected' : '' ?>>
                <?= escapar(basename($src)) ?>
            </option>
        <?php } ?>
    </select>
    <?php if (isset($errors['src'])) { ?><small><?= escapar($errors['src']) ?></small><?php } ?>
    <?php
}
