# Bloque 8 — Catálogo JSON

`docker/data/canciones.json` guarda una lista JSON; `docker/src/12-catalogo-json.php`
lee la ruta fija `/var/www/data/canciones.json` mediante `docker/src/lib/almacen-json.php`.
El endpoint web no puede descargar `/data/canciones.json`, y la página de demostración
está protegida por sesión.

La raíz `[]` es válida. Un archivo ilegible, JSON mal formado o una ficha con campos
inválidos produce un error controlado; nunca se reemplaza un archivo dañado por una
lista vacía. Comprueba lectura y formato con copias de prueba, sin alterar los datos que
quieras conservar.

El bloqueo compartido/exclusivo coordina las peticiones que usan estas funciones, pero
una escritura directa sobre el archivo no es transaccional ante un corte del proceso.
Conserva copias de los datos importantes fuera de `docker/src/`.
