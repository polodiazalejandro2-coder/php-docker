<?php

require_once __DIR__ . '/sesion.php';

if (
    !isset($_SESSION['usuario_id'], $_SESSION['alias'])
    || !is_int($_SESSION['usuario_id'])
    || $_SESSION['usuario_id'] < 1
    || !is_string($_SESSION['alias'])
) {
    header('Location: login.php', true, 303);
    exit;
}
