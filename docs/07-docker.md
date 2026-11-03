# Bloque 7 — Segunda etapa Docker

La configuración inicial permanece en 8090. `docker/compose.yaml` crea la etapa
multiservicio en 8091, monta el código y `docker/data/` por separado y espera a que
PostgreSQL y MariaDB estén saludables. Sus puertos no se publican en Windows; cada
motor usa un volumen nombrado.

`docker/Dockerfile` instala `mbstring`, `pdo_pgsql` y `pdo_mysql`, y activa el sitio
Apache `jukebox`. `docker/apache/jukebox.conf` sirve `/var/www/html`, declara
`jukebox.test` y `localhost`, y deniega `/var/www/data`. `.env` y los datos privados
quedan excluidos del repositorio y del contexto Docker.

Desde `docker/`, copia `.env.example` a `.env`, valida Compose, arranca con
`docker compose up -d --build` y revisa `apache2ctl -t`, `apache2ctl -S` y `php -m`.
No ejecutes `down -v` si quieres conservar datos.
