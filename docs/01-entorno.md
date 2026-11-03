# Bloque 1 — Primer servicio

La etapa inicial usa `php:8.5-apache-bookworm`, publica Apache solo en
`127.0.0.1:8090` y monta `./src` en `/var/www/html`. El saludo ejecutable está en
`src/01-bienvenida.php`.

Pruebas reproducibles desde la raíz:

```powershell
docker compose config --quiet
docker compose up -d
docker compose ps
curl.exe -i http://localhost:8090/01-bienvenida.php
```

Esperado: respuesta HTTP 200 y saludo del jukebox. Una ruta inexistente activa da
404; detener `web` causa un error de conexión, no un 404. Registra aquí el resultado y
adjunta capturas propias tras probarlo.
