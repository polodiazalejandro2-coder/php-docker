# Bloque 5 — Funciones y datos comunes

`src/lib/canciones.php` contiene `duracionLegible`, `escapar` y la validación compartida
de canciones. `src/08-funciones.php` usa `require_once`; `src/07-catalogo.php` carga
por separado la colección devuelta por `src/lib/catalogo-inicial.php`.

Comprueba 60 y 185 segundos: la función devuelve 1 minuto y 0 segundos, y 3 minutos
y 5 segundos, respectivamente. La función devuelve texto; no imprime HTML.
