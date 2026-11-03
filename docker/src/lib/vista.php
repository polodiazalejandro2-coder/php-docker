<?php

function iniciarPagina(string $title): void
{
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= escapar($title) ?> · Jukebox</title>
    </head>
    <body>
    <main>
        <h1><?= escapar($title) ?></h1>
    <?php
}

function finalizarPagina(): void
{
    ?>
    </main>
    </body>
    </html>
    <?php
}

function registrarErrorAplicacion(string $operation, Throwable $error): void
{
    error_log($operation . ' failed (' . get_class($error) . '): ' . $error->getMessage());
}
