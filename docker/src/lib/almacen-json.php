<?php

const ARCHIVO_CANCIONES = '/var/www/data/canciones.json';
const ARCHIVO_USUARIOS = '/var/www/data/usuarios.json';

function validarListaJson(mixed $records, string $kind): array
{
    if (!is_array($records) || !array_is_list($records)) {
        throw new UnexpectedValueException('El JSON debe contener una lista.');
    }

    $ids = [];
    $emails = [];
    foreach ($records as $record) {
        if (!is_array($record) || !isset($record['id']) || !is_int($record['id']) || $record['id'] < 1) {
            throw new UnexpectedValueException('Un registro JSON no tiene un identificador válido.');
        }
        if (isset($ids[$record['id']])) {
            throw new UnexpectedValueException('El archivo JSON contiene identificadores duplicados.');
        }
        $ids[$record['id']] = true;

        if ($kind === 'songs') {
            if (
                !isset($record['title'], $record['artist'], $record['category'], $record['duration'], $record['src'])
                || !is_string($record['title'])
                || !is_string($record['artist'])
                || !is_string($record['category'])
                || !is_int($record['duration'])
                || !is_string($record['src'])
                || trim($record['title']) === ''
                || trim($record['artist']) === ''
                || contarCaracteres($record['title']) > 80
                || contarCaracteres($record['artist']) > 80
                || !in_array($record['category'], CATEGORIAS_CANCION, true)
                || $record['duration'] < 1
                || $record['duration'] > 1800
                || !in_array($record['src'], FUENTES_AUDIO_ADMITIDAS, true)
            ) {
                throw new UnexpectedValueException('El catálogo contiene una ficha no válida.');
            }
        } elseif (
            !isset($record['alias'], $record['email'], $record['password_hash'])
            || !is_string($record['alias'])
            || !is_string($record['email'])
            || !is_string($record['password_hash'])
            || contarCaracteres($record['alias']) < 2
            || contarCaracteres($record['alias']) > 30
            || filter_var($record['email'], FILTER_VALIDATE_EMAIL) === false
            || strtolower($record['email']) !== $record['email']
            || password_get_info($record['password_hash'])['algo'] === null
            || isset($emails[$record['email']])
        ) {
            throw new UnexpectedValueException('El archivo de usuarios contiene un registro no válido.');
        }
        if ($kind === 'users') {
            $emails[$record['email']] = true;
        }
    }

    return $records;
}

function leerListaJson(string $path, string $kind): array
{
    $file = fopen($path, 'r');
    if ($file === false) {
        throw new RuntimeException('No se pudo abrir el archivo JSON para lectura.');
    }

    $locked = false;
    $error = null;
    $records = null;
    try {
        if (!flock($file, LOCK_SH)) {
            throw new RuntimeException('No se pudo bloquear el archivo JSON para lectura.');
        }
        $locked = true;

        if (!rewind($file)) {
            throw new RuntimeException('No se pudo volver al inicio del archivo JSON.');
        }
        $contents = stream_get_contents($file);
        if ($contents === false) {
            throw new RuntimeException('No se pudo leer el archivo JSON.');
        }

        $records = validarListaJson(
            json_decode($contents, true, 512, JSON_THROW_ON_ERROR),
            $kind
        );
    } catch (Throwable $caught) {
        $error = $caught;
    }

    $unlockSucceeded = !$locked || flock($file, LOCK_UN);
    $closeSucceeded = fclose($file);
    if ($error !== null) {
        throw $error;
    }
    if (!$unlockSucceeded || !$closeSucceeded) {
        throw new RuntimeException('No se pudo liberar correctamente el archivo JSON.');
    }

    return $records;
}

function actualizarListaJson(string $path, string $kind, callable $operation): bool
{
    $file = fopen($path, 'r+');
    if ($file === false) {
        throw new RuntimeException('No se pudo abrir el archivo JSON para escritura.');
    }

    $locked = false;
    $error = null;
    $changed = false;
    try {
        // El bloqueo abarca lectura, cambio y guardado para evitar actualizaciones perdidas.
        if (!flock($file, LOCK_EX)) {
            throw new RuntimeException('No se pudo bloquear el archivo JSON para escritura.');
        }
        $locked = true;

        if (!rewind($file)) {
            throw new RuntimeException('No se pudo volver al inicio del archivo JSON.');
        }
        $contents = stream_get_contents($file);
        if ($contents === false) {
            throw new RuntimeException('No se pudo leer el archivo JSON bloqueado.');
        }
        $records = validarListaJson(
            json_decode($contents, true, 512, JSON_THROW_ON_ERROR),
            $kind
        );

        $changed = $operation($records);
        if (!is_bool($changed)) {
            throw new LogicException('La operación JSON debe indicar si modificó los registros.');
        }

        if ($changed) {
            validarListaJson($records, $kind);
            $json = json_encode(
                $records,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            );
            if (!rewind($file)) {
                throw new RuntimeException('No se pudo preparar el archivo JSON para escritura.');
            }

            $length = strlen($json);
            $written = 0;
            while ($written < $length) {
                $bytes = fwrite($file, substr($json, $written));
                if ($bytes === false || $bytes === 0) {
                    throw new RuntimeException('No se pudo escribir el archivo JSON completo.');
                }
                $written += $bytes;
            }
            if (!ftruncate($file, $written)) {
                throw new RuntimeException('No se pudo recortar el archivo JSON.');
            }
            if (!fflush($file)) {
                throw new RuntimeException('No se pudo confirmar la escritura del archivo JSON.');
            }
        }
    } catch (Throwable $caught) {
        $error = $caught;
    }

    $unlockSucceeded = !$locked || flock($file, LOCK_UN);
    $closeSucceeded = fclose($file);
    if ($error !== null) {
        throw $error;
    }
    if (!$unlockSucceeded || !$closeSucceeded) {
        throw new RuntimeException('No se pudo liberar correctamente el archivo JSON.');
    }

    return $changed;
}

function leerCancionesJson(): array
{
    return leerListaJson(ARCHIVO_CANCIONES, 'songs');
}

function leerUsuariosJson(): array
{
    return leerListaJson(ARCHIVO_USUARIOS, 'users');
}

function actualizarCancionesJson(callable $operation): bool
{
    return actualizarListaJson(ARCHIVO_CANCIONES, 'songs', $operation);
}

function actualizarUsuariosJson(callable $operation): bool
{
    return actualizarListaJson(ARCHIVO_USUARIOS, 'users', $operation);
}

function siguienteId(array $records): int
{
    $maximum = 0;
    foreach ($records as $record) {
        $maximum = max($maximum, $record['id']);
    }
    if ($maximum === PHP_INT_MAX) {
        throw new OverflowException('No quedan identificadores disponibles.');
    }

    return $maximum + 1;
}
