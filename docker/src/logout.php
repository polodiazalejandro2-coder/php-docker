<?php

require_once __DIR__ . '/lib/sesion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Método no permitido.');
}

exigirCsrf($_POST['csrf'] ?? null);
$_SESSION = [];
$cookie = session_get_cookie_params();
if (!setcookie(session_name(), '', [
    'expires' => time() - 42000,
    'path' => $cookie['path'],
    'domain' => $cookie['domain'],
    'secure' => $cookie['secure'],
    'httponly' => $cookie['httponly'],
    'samesite' => $cookie['samesite'],
])) {
    throw new RuntimeException('No se pudo eliminar la cookie de sesión.');
}
if (!session_destroy()) {
    throw new RuntimeException('No se pudo cerrar la sesión.');
}

header('Location: login.php', true, 303);
exit;
