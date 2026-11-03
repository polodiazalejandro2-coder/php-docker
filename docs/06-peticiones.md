# Bloque 6 — GET y POST

`src/09-filtro.php` valida el tipo y el valor de `category` antes de filtrar; categorías
desconocidas y parámetros array reciben HTTP 400. `src/10-formulario.php` valida por
servidor título, artista, categoría, duración de 1 a 1800 y una ruta de audio permitida.
Solo prepara una vista previa: no modifica el catálogo.

Prueba GET sin parámetro, `?category=rock`, `?category=desconocida` y
`?category[]=rock`. En el formulario prueba texto vacío, duración no entera, límites,
texto HTML y un campo enviado como array. No conviertas los valores recibidos
directamente a entero ni los insertes en HTML sin escapar.
