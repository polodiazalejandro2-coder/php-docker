# Documentación de ejecución

El procedimiento completo está en el [README del proyecto](../README.md). Este archivo
resume la diferencia entre las dos etapas y cómo diagnosticar problemas sin confundir
un fallo de conexión con un 404 o un error de sintaxis.

## Comprobaciones Docker

Desde la raíz valida y arranca la etapa inicial, disponible en `localhost:8090`. Desde
`docker/`, copia `.env.example` a `.env`, ejecuta `docker compose config --quiet` y
arranca la etapa completa en `localhost:8091`.

- Error de conexión: el servicio o el motor Docker no está disponible.
- HTTP 404: Apache contestó, pero no encontró la ruta.
- Error PHP: consulta la salida del servicio y valida el archivo con
  `docker compose exec web php -l /var/www/html/nombre.php`.
- Sintaxis Apache: `docker compose exec web apache2ctl -t`.
- Controladores PDO: `docker compose exec web php -m`.

No publiques los puertos de las bases de datos ni ejecutes `docker compose down -v`
cuando quieras preservar sus volúmenes.
