# Informe de Prácticas — Jukebox PHP y JSON

Informe técnico y bitácora de ejecución de las prácticas acumulativas de DWES (RA1 y RA2).
El informe formal completo con todas las capturas incrustadas, diagramas y tablas se encuentra en el archivo:
👉 **`Informe_Practica_Jukebox_PHP.docx`** (generado en la raíz del proyecto).

## Resumen Ejecutivo de la Arquitectura

```text
Navegador -- HTTP 127.0.0.1:8090 --> Compose inicial --> Apache 2.4 / PHP 8.5 --> src/
Navegador -- HTTP 127.0.0.1:8091 --> Apache 2.4 / PHP 8.5 --> docker/src/
                                                |--> docker/data/*.json (Privado, fuera de DocumentRoot)
                                                |--> PostgreSQL 17 (Prueba PDO interna)
                                                `--> MariaDB 11.4 (Prueba PDO interna)
```

## Resumen por Bloques

- **Bloque 1 — Entorno Inicial**: Servicio levantado en 8090 con `php:8.5-apache-bookworm`. HTTP 200 en `01-bienvenida.php`. Diagnóstico de fallos (404 frente a caída de socket) y sintaxis estática con `php -l`.
- **Bloque 2 — Portada y Ficha**: Portada HTML5 en `02-portada.php`. Ficha en `03-cancion.php` con variables, tipos numéricos, constantes y escape HTML mediante `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`.
- **Bloque 3 — Duración y Decisiones**: Formateo de tiempo mediante `intdiv` y `%` en `04-duracion.php`. Clasificación en 3 ramas en `05-estado-cancion.php` evaluando los límites 240 s y 241 s. Igualdad estricta `===` frente a asignación `=`.
- **Bloque 4 — Catálogo Dinámico**: Bucles `for`, `while` y `foreach` en `06-listas.php`. Catálogo en array multidimensional asociativo en `07-catalogo.php`. Distinción entre ID persistente y posición física de array. Manejo de catálogo vacío.
- **Bloque 5 — Funciones y Modularización**: Biblioteca común `src/lib/canciones.php` con función tipada `duracionLegible(int $total): string`. Extracción de datos en `src/lib/catalogo-inicial.php` con `return`. Diagnóstico de dependencias y comportamiento de `php -l`.
- **Bloque 6 — Peticiones GET y Formulario POST**: Filtrado por categoría en `09-filtro.php` validando con `is_string` e `in_array` (rechazo 400 ante arrays o valores no permitidos). Formulario POST en `10-formulario.php` con validación estricta (`FILTER_VALIDATE_INT`, rango 1 a 1800, select de audio) y vista previa sin persistencia.
- **Bloque 7 — Segunda Etapa Docker**: Proyecto multiservicio en 8091 (`docker/compose.yaml`). Dockerfile personalizado con controladores `pdo_pgsql`, `pdo_mysql` y `mbstring`. VirtualHost Apache `jukebox.test` en `docker/apache/jukebox.conf`. Conectividad PDO en `11-conexiones.php` y aislamiento de `/var/www/data` fuera de `DocumentRoot` (HTTP 404).
- **Bloque 8 — Catálogo JSON Privado**: Persistencia en `docker/data/canciones.json`. Lectura centralizada con `flock(LOCK_SH)` y `json_decode(..., JSON_THROW_ON_ERROR)` en `almacen-json.php`.
- **Bloque 9 — Sesiones, CSRF y Autenticación**: Sesión segura en modo estricto con cookie `JUKEBOXSESSID` (`HttpOnly`, `SameSite=Lax`). Tokens CSRF validados con `hash_equals` (HTTP 403 ante omisión). Registro en `register.php` con `password_hash` bcrypt. Procedimiento atómico de actualización bajo `flock(LOCK_EX)`. Verificación en `login.php` con `password_verify` y `session_regenerate_id`. Control de acceso en `lib/acceso.php` y logout por POST.
- **Bloque 10 — CRUD Completo**: Lectura en `canciones.php`, alta en `cancion-nueva.php` con ID máximo autoincremental, edición en `cancion-editar.php?id=...`, y borrado en `cancion-eliminar.php?id=...` con `unset` y `array_values` para preservar el formato de lista JSON sin alterar archivos MP3.
- **Bloque 11 — API REST y Reproductor**: Endpoint `api/canciones.php` con control de sesión (HTTP 401 JSON sin sesión; HTTP 200 con array plano si autenticado). Integración del reproductor HTML5 en `home.php` consumiendo la API mediante `fetch()`, manipulación segura con `textContent` y reproducción de audios locales.
- **Bloque 12 — Verificación y Presentación**: Matriz de aceptación con 13 pruebas ejecutadas y superadas. Preparación de copia compartible (`data-ejemplo/`, `.env.example`). Argumentos técnicos para la defensa y preguntas elaboradas para el examen final.
