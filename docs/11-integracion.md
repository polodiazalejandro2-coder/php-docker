# Bloque 11 — API y reproductor

`docker/src/api/canciones.php` devuelve una lista JSON sin envolverla en otra clave.
Sin sesión responde 401 JSON, sin redirigir a HTML. `home.php` solicita esa API y
`scripts/reproductor.js` crea la lista usando `textContent`; no mantiene un segundo
archivo de canciones en JavaScript. El reproductor usa los controles nativos de
`<audio>` y permite cambiar de pista.

El workspace original no contenía CSS, JavaScript ni audio del reproductor de cliente.
Por eso se implementó una interfaz HTML5 autónoma, sin sustituir ni afirmar que se
integró un reproductor externo. Añade en `docker/src/audio/` los archivos autorizados
`demo-01.mp3` y `demo-02.mp3`; sin ellos, la interfaz informa del error de audio.

Comprueba API con y sin sesión, respuesta vacía, audio disponible, reproducción por
acción del usuario, pausa y cambio de pista. Los archivos estáticos de audio no tienen
autorización individual.
