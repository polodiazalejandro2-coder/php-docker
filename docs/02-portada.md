# Bloque 2 — Portada y canción

`src/02-portada.php` presenta el proyecto y enlaza el saludo inicial. `src/03-cancion.php`
separa título, artista, duración, volumen y disponibilidad; escapa el título con
`htmlspecialchars` antes de insertarlo en HTML. La portada informa que el catálogo está
en preparación y no ofrece botones de acceso todavía inexistentes.

Comprueba el escape sustituyendo temporalmente el título por `<b>Demo</b>`: debe
mostrarse literalmente. No dejes `var_dump` ni la prueba temporal en la entrega.
