<?php

if (ini_set('session.use_strict_mode', '1') === false) {
    throw new RuntimeException('No se pudo activar el modo estricto de sesión.');
}

if (session_name('JUKEBOXSESSID') === false) {
    throw new RuntimeException('No se pudo configurar el nombre de sesión.');
}
$isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== '' && $_SERVER['HTTPS'] !== 'off';
if (!session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
])) {
    throw new RuntimeException('No se pudieron configurar los parámetros de la cookie.');
}

if (session_status() !== PHP_SESSION_ACTIVE && !session_start()) {
    throw new RuntimeException('No se pudo iniciar la sesión.');
}

if (!isset($_SESSION['csrf']) || !is_string($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function tokenCsrf(): string
{
    return $_SESSION['csrf'];
}

function csrfValido(mixed $received): bool
{
    return is_string($received)
        && isset($_SESSION['csrf'])
        && is_string($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $received);
}

function renovarTokenCsrf(): void
{
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

function exigirCsrf(mixed $received): void
{
    if (!csrfValido($received)) {
        http_response_code(403);
        exit('Solicitud no válida.');
    }
}
