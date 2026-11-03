# Jukebox JSON

Aplicación educativa en PHP 8.5 y Apache. El registro, las sesiones y el CRUD de
canciones usan archivos JSON; PostgreSQL y MariaDB solo sirven para comprobar PDO.

## Requisitos

- Docker Desktop con el motor Linux activo y Docker Compose.
- Puertos locales 8090 y 8091 disponibles.
- PHP no es necesario en Windows: las páginas se ejecutan dentro de Docker.

## Etapa inicial (puerto 8090)

Desde la raíz del proyecto:

```powershell
docker compose config --quiet
docker compose up -d
docker compose ps
curl.exe -i http://localhost:8090/01-bienvenida.php
```

Los ejercicios numerados `01` a `10` están en `src/`. El servicio monta esa carpeta
como raíz pública de Apache.

## Aplicación completa (puerto 8091)

Desde la carpeta `docker/`, prepara la configuración local una sola vez:

```powershell
Copy-Item .env.example .env
New-Item -ItemType Directory -Force data | Out-Null
if (-not (Test-Path data\canciones.json)) { Copy-Item ..\data-ejemplo\canciones.json data\canciones.json }
if (-not (Test-Path data\usuarios.json)) { Copy-Item ..\data-ejemplo\usuarios.json data\usuarios.json }
docker compose config --quiet
docker compose up -d --build
docker compose ps
```

Abre <http://localhost:8091/register.php> para crear la primera cuenta ficticia.
Después inicia sesión en <http://localhost:8091/login.php>; la home privada está en
<http://localhost:8091/home.php>. La cuenta no se crea automáticamente. Usa una
contraseña de laboratorio, nunca una contraseña personal.

Comprobaciones útiles, también desde `docker/`:

```powershell
docker compose exec web apache2ctl -t
docker compose exec web apache2ctl -S
docker compose exec web php -m
curl.exe -i http://localhost:8091/11-conexiones.php
```

Para apagar los servicios sin borrar los volúmenes de las bases de datos:

```powershell
docker compose down
```

No uses `docker compose down -v` si quieres conservar los volúmenes. El catálogo y
las cuentas viven en `docker/data/`, no en las bases de datos. Esa carpeta y `.env`
están excluidos de Git y del contexto de construcción. En una copia nueva, los datos
ficticios opcionales están en `data-ejemplo/`; los comandos de preparación solo copian
cada JSON que todavía no existe y nunca sobrescriben datos que quieras conservar.

## Estructura

- `compose.yaml`, `src/`: ejercicios y servidor inicial en 8090.
- `docker/compose.yaml`, `docker/Dockerfile`, `docker/apache/`: etapa completa en 8091.
- `docker/src/`: páginas, sesión, almacenamiento JSON, API y reproductor.
- `docker/data/`: datos locales persistentes, fuera del DocumentRoot.
- `data-ejemplo/`: JSON ficticios para preparar una copia limpia.
- `docs/`: resumen de los bloques y matriz de pruebas para completar.

La carpeta `docker/src/audio/` no incluye audios. Añade ahí únicamente archivos
locales con permiso de uso que correspondan a `audio/demo-01.mp3` o
`audio/demo-02.mp3`. El reproductor HTML5 muestra un mensaje si falta el archivo.

## Datos de conexión de prueba

Los valores de `docker/.env.example` son marcadores locales ficticios. El servicio web
usa `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD` para PostgreSQL y
las variables `MARIADB_*` para MariaDB. El endpoint de comprobación no devuelve
contraseñas. El CRUD no crea tablas ni guarda canciones o usuarios en esos motores.

## Seguridad y alcance

- Las cuentas se almacenan con `password_hash`; la sesión no contiene contraseñas.
- Las acciones privadas comprueban la sesión; los POST mutables validan CSRF.
- El almacenamiento usa bloqueo compartido para lectura y exclusivo para actualizar.
- El catálogo JSON y las cuentas no se sirven por HTTP.
- Los audios son recursos estáticos locales y, como advierte la práctica, no tienen
  autorización individual.
- Es un prototipo didáctico local, no debe publicarse en Internet.

## Verificación y entrega académica

La matriz de aceptación está en `docs/12-verificacion.md`. Completa resultado real,
capturas sin cookies ni tokens y las observaciones personales después de ejecutar el
proyecto. `docs/informe-pruebas.md` sirve como base del informe exportable a PDF;
no contiene resultados o evidencias inventados.
