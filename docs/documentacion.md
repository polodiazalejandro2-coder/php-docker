# Entorno y diagnóstico

Un error de conexión indica que el servicio web está caído o no acepta conexiones; el navegador no recibe una respuesta HTTP. Un código HTTP 404 significa que el servidor si respondio, pero no encontró el archivo solicitado. Un error de sintaxis PHP indica que el archivo existe, pero contiene código mal formado que PHP no puede interpretar

# Comandos utilizados para realizar este primer bloque de docker con php

    docker compose config -q
    docker compose up -d
    docker compose ps
    curl.exe -i http://127.0.0.1:8090/01-bienvenida.php

# Simular un fallo de conexión, reiniciar y consultar registros:

    docker compose stop web
    docker compose start web
    docker compose logs --tail=20 web

# Validar la sintaxis de PHP dentro del contenedor:

    docker compose exec web php -l /var/www/html/01-bienvenida.php

