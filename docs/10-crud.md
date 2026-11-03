# Bloque 10 — CRUD de canciones

`canciones.php` requiere sesión. `cancion-nueva.php`, `cancion-editar.php` y
`cancion-eliminar.php` procesan cambios únicamente por POST con token CSRF. La
validación común exige título y artista de hasta 80 caracteres, categoría válida, duración
de 1 a 1800 y uno de los dos recursos de audio admitidos.

El id se calcula como máximo existente más uno. Editar y borrar vuelven a buscar el id
bajo bloqueo; borrar usa `unset` y `array_values` y no elimina el archivo de audio.
Identificadores inválidos devuelven 400, inexistentes 404 y métodos no permitidos 405.
Un POST con token no válido devuelve 403 y no escribe.

Demuestra alta, lectura, edición tras eliminar un id intermedio y borrado. Verifica que
GET y Cancelar no cambian el JSON y que la lista serializada conserva índices.
