<?php

require_once __DIR__ . '/lib/sesion.php';
require_once __DIR__ . '/lib/canciones.php';
require_once __DIR__ . '/lib/almacen-json.php';
require_once __DIR__ . '/lib/vista.php';

$method = $_SERVER['REQUEST_METHOD'];
if ($method !== 'GET' && $method !== 'POST') {
    header('Allow: GET, POST');
    http_response_code(405);
    exit('Método no permitido.');
}

$errorMessage = null;
$emailValue = '';

if ($method === 'POST') {
    exigirCsrf($_POST['csrf'] ?? null);
    $emailInput = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $emailValue = is_string($emailInput) ? strtolower(trim($emailInput)) : '';
    $validInput = is_string($emailInput)
        && filter_var($emailValue, FILTER_VALIDATE_EMAIL) !== false
        && is_string($password);

    if (!$validInput) {
        $errorMessage = 'Credenciales no válidas.';
    } else {
        try {
            $users = leerUsuariosJson();
            $user = null;
            foreach ($users as $candidate) {
                if ($candidate['email'] === $emailValue) {
                    $user = $candidate;
                    break;
                }
            }

            if ($user === null || !password_verify($password, $user['password_hash'])) {
                $errorMessage = 'Credenciales no válidas.';
            } else {
                if (!session_regenerate_id(true)) {
                    throw new RuntimeException('No se pudo renovar el identificador de sesión.');
                }
                $_SESSION['usuario_id'] = $user['id'];
                $_SESSION['alias'] = $user['alias'];
                renovarTokenCsrf();
                header('Location: home.php', true, 303);
                exit;
            }
        } catch (JsonException | RuntimeException $error) {
            registrarErrorAplicacion('Authenticating a user', $error);
            http_response_code(500);
            $errorMessage = 'No se pudo iniciar sesión. Inténtalo más tarde.';
        }
    }
}

iniciarPagina('Iniciar sesión');
if ($errorMessage !== null) {
    if (http_response_code() < 400) {
        http_response_code(401);
    }
    echo '<p role="alert">' . escapar($errorMessage) . '</p>';
} elseif (($_GET['registered'] ?? null) === '1') {
    echo '<p role="status">Cuenta creada. Ahora puedes iniciar sesión.</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
    <label for="email">Correo</label>
    <input id="email" name="email" type="email" required autocomplete="username" value="<?= escapar($emailValue) ?>">
    <label for="password">Contraseña</label>
    <input id="password" name="password" type="password" required autocomplete="current-password">
    <button type="submit">Entrar</button>
</form>
<p><a href="register.php">Crear una cuenta de prueba</a></p>
<?php finalizarPagina(); ?>
