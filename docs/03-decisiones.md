# Bloque 3 — Duración y decisiones

`src/04-duracion.php` calcula minutos con `intdiv` y segundos con `%`.
`src/05-estado-cancion.php` distingue duración cero, hasta 240 segundos y más de
240 segundos mediante `if`, `elseif` y `else`.

Casos de prueba recomendados: 0, 1, 59, 60, 185, 239, 240 y 241. El valor 240 queda en
«Hasta cuatro minutos»; 241 pasa a «Más de cuatro minutos». `===` compara valor y
tipo, a diferencia de `=`, que asigna.
