# Bloque 9 — Sesión, CSRF y cuentas

`docker/src/lib/sesion.php` inicia `JUKEBOXSESSID` con modo estricto, cookie
`HttpOnly`, `SameSite=Lax` y `Secure` si la petición usa HTTPS. Crea el token CSRF
aleatorio de la sesión y lo valida con `hash_equals`. `lib/acceso.php` exige
`usuario_id` en las páginas privadas.

`register.php` valida alias, correo normalizado, contraseña de 12 a 64 caracteres y
confirmación; guarda solo el resultado de `password_hash`. La detección de correo
duplicado y el alta ocurren bajo el mismo bloqueo exclusivo. `login.php` verifica con
`password_verify`, regenera el id de sesión y conserva solo id y alias. `logout.php`
acepta POST con CSRF e invalida la cookie y la sesión.

Prueba envío duplicado, arrays como campos, confirmación distinta, token ausente y
credenciales incorrectas. No captures ni copies contraseñas, hashes, cookies o tokens.
