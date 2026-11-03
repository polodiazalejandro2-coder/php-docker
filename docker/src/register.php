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

$values = ['alias' => '', 'email' => ''];
$errors = [];

if ($method === 'POST') {
    exigirCsrf($_POST['csrf'] ?? null);
    $aliasInput = $_POST['alias'] ?? null;
    $emailInput = $_POST['email'] ?? null;
    $password = $_POST['password'] ?? null;
    $confirmation = $_POST['password_confirm'] ?? null;

    $alias = is_string($aliasInput) ? trim($aliasInput) : '';
    $email = is_string($emailInput) ? strtolower(trim($emailInput)) : '';
    $values = ['alias' => $alias, 'email' => $email];

    if (!is_string($aliasInput)) {
        $errors['alias'] = 'El alias debe ser texto.';
    } elseif (mb_strlen($alias, 'UTF-8') < 2 || mb_strlen($alias, 'UTF-8') > 30) {
        $errors['alias'] = 'El alias debe tener entre 2 y 30 caracteres.';
    }
    if (!is_string($emailInput) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Introduce un correo válido.';
    }
    if (!is_string($password)) {
        $errors['password'] = 'La contraseña debe ser texto.';
    } elseif (
        mb_strlen($password, 'UTF-8') < 12
        || mb_strlen($password, 'UTF-8') > 64
        || strlen($password) > 72
    ) {
        $errors['password'] = 'La contraseña debe tener entre 12 y 64 caracteres y no superar 72 bytes.';
    }
    if (!is_string($confirmation)) {
        $errors['password_confirm'] = 'Confirma la contraseña.';
    } elseif (is_string($password) && !hash_equals($password, $confirmation)) {
        $errors['password_confirm'] = 'Las contraseñas no coinciden.';
    }

    if ($errors === []) {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            if ($hash === false) {
                throw new RuntimeException('No se pudo proteger la contraseña.');
            }

            $duplicate = false;
            actualizarUsuariosJson(
                static function (array &$users) use ($email, $alias, $hash, &$duplicate): bool {
                    foreach ($users as $user) {
                        if ($user['email'] === $email) {
                            $duplicate = true;
                            return false;
                        }
                    }
                    $users[] = [
                        'id' => siguienteId($users),
                        'alias' => $alias,
                        'email' => $email,
                        'password_hash' => $hash,
                    ];
                    return true;
                }
            );

            if ($duplicate) {
                $errors['email'] = 'Ya existe una cuenta con ese correo.';
            } else {
                header('Location: login.php?registered=1', true, 303);
                exit;
            }
        } catch (JsonException | RuntimeException $error) {
            registrarErrorAplicacion('Registering a user', $error);
            http_response_code(500);
            $errors['form'] = 'No se pudo guardar la cuenta. Inténtalo más tarde.';
        }
    }

    if ($errors !== [] && http_response_code() < 400) {
        http_response_code(400);
    }
}

iniciarPagina('Crear cuenta');
if (isset($errors['form'])) {
    echo '<p role="alert">' . escapar($errors['form']) . '</p>';
}
?>
<form method="post">
    <input type="hidden" name="csrf" value="<?= escapar(tokenCsrf()) ?>">
    <label for="alias">Alias</label>
    <input id="alias" name="alias" type="text" minlength="2" maxlength="30" required value="<?= escapar($values['alias']) ?>">
    <?php if (isset($errors['alias'])) { ?><small><?= escapar($errors['alias']) ?></small><?php } ?>

    <label for="email">Correo</label>
    <input id="email" name="email" type="email" required value="<?= escapar($values['email']) ?>">
    <?php if (isset($errors['email'])) { ?><small><?= escapar($errors['email']) ?></small><?php } ?>

    <label for="password">Contraseña (12 a 64 caracteres)</label>
    <input id="password" name="password" type="password" minlength="12" maxlength="64" required autocomplete="new-password">
    <?php if (isset($errors['password'])) { ?><small><?= escapar($errors['password']) ?></small><?php } ?>

    <label for="password_confirm">Repetir contraseña</label>
    <input id="password_confirm" name="password_confirm" type="password" minlength="12" maxlength="64" required autocomplete="new-password">
    <?php if (isset($errors['password_confirm'])) { ?><small><?= escapar($errors['password_confirm']) ?></small><?php } ?>

    <button type="submit">Crear cuenta</button>
</form>
<p><a href="login.php">Ya tengo una cuenta</a></p>
<?php finalizarPagina(); ?>
