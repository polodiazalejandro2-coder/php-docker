# Informe de prácticas — Jukebox JSON

> Borrador para exportar a PDF después de ejecutar las pruebas. Completa las secciones
> personales y adjunta capturas propias; no marques como probado lo que no hayas
> ejecutado.

## Índice

1. Arquitectura y entorno
2. Bloques 1–6: PHP, catálogo y formularios
3. Bloques 7–8: Docker y JSON privado
4. Bloques 9–10: autenticación y CRUD
5. Bloque 11: API y reproductor
6. Bloque 12: aceptación y presentación

## Arquitectura

```text
Navegador -- HTTP 127.0.0.1:8090 --> Compose inicial --> Apache/PHP --> src/
Navegador -- HTTP 127.0.0.1:8091 --> Apache/PHP --> docker/src/
                                                |--> docker/data/*.json
                                                |--> PostgreSQL (solo prueba PDO)
                                                `--> MariaDB (solo prueba PDO)
```

Los bind mounts exponen el código de cada etapa al contenedor. Los JSON se montan en
`/var/www/data`, fuera de `DocumentRoot`; los motores tienen volúmenes Docker propios.
El catálogo y las cuentas de la aplicación se guardan en JSON.

## Bloques de la práctica

Para cada sección, añade la petición del cliente, archivos modificados, una prueba válida,
una prueba de error o límite, URL/comando, resultado esperado y resultado observado.
Usa capturas propias y no incluyas información sensible.

### Bloque 1 — [Entorno](./01-entorno.md)

### Bloque 2 — [Portada y datos](./02-portada.md)

### Bloque 3 — [Decisiones y duración](./03-decisiones.md)

### Bloque 4 — [Catálogo](./04-catalogo.md)

### Bloque 5 — [Funciones](./05-funciones.md)

### Bloque 6 — [Peticiones y formularios](./06-peticiones.md)

### Bloque 7 — [Docker](./07-docker.md)

### Bloque 8 — [Almacenamiento JSON](./08-json.md)

### Bloque 9 — [Cuentas y sesiones](./09-cuentas.md)

### Bloque 10 — [CRUD](./10-crud.md)

### Bloque 11 — [Integración](./11-integracion.md)

### Bloque 12 — [Verificación](./12-verificacion.md)

## Evidencias y resultados

El diagrama editable está en [arquitectura.drawio](./arquitectura.drawio). La matriz de
pruebas está en `12-verificacion.md`. En este entorno no se pudo iniciar
Docker porque el motor Linux de Docker Desktop no estaba disponible; por tanto, no se
atribuyen resultados HTTP, PDO, autenticación ni CRUD a una ejecución real. Repite las
pruebas con Docker activo y completa la matriz antes de exportar este informe.

Adjunta capturas legibles del navegador y terminal con pies explicativos. Oculta
contraseñas, hashes, cookies, tokens y datos personales. Añade tu diagrama, reparto de
intervenciones, decisión técnica y aprendizaje de la presentación de grupo.

Preguntas de repaso sugeridas, con respuestas y evidencias, están en `12-verificacion.md`.
