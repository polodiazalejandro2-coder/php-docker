# Bloque 12 — Matriz de aceptación y verificación

Todas las pruebas han sido ejecutadas sobre el entorno Docker real en ejecución (Etapa inicial en puerto 8090 y Etapa multiservicio en puerto 8091).

| Prueba | Preparación | Resultado esperado | Resultado real | Evidencia |
|---|---|---|---|---|
| **Etapa inicial** | Arrancar Compose raíz (`docker compose up -d`) | PHP responde en puerto 8090 | HTTP/1.1 200 OK con saludo oficial del jukebox | `b01_01_navegador_bienvenida.png`, `b01_02_terminal_curl_200.png` |
| **Etapa ampliada** | Copiar `.env.example` a `.env`, `docker compose up -d --build` en `docker/` | Servicio web en 8091 y 2 bases de datos saludables | 3 contenedores activos, postgres:17 y mariadb:11.4 en estado healthy | `b07_01_terminal_compose_multiservicio.png`, `b07_02_terminal_apache_diag.png` |
| **Conexión PDO** | Abrir `http://localhost:8091/11-conexiones.php` | Dos estados `ok` en JSON, sin exponer contraseñas | HTTP 200 OK, `{"postgres":{"status":"ok"},"mariadb":{"status":"ok"}}` | `b07_03_terminal_pdo_conexiones.png` |
| **Registro de usuario** | Formulario en `/register.php` con token CSRF | Alta única en `usuarios.json` con hash protegido | Usuario registrado con `password_hash` bcrypt y redirección 303 a `login.php?registered=1` | `b09_01_navegador_registro.png`, `b09_03_navegador_registro_exito.png` |
| **Registro inválido** | Repetir correo, claves distintas o campo como array | Rechazo sin crear usuario en JSON | HTTP 400 Bad Request y mensajes contextuales de error | `b09_02_navegador_registro_errores.png` |
| **Privacidad de datos** | Pedir `http://localhost:8091/data/usuarios.json` | Denegar descarga de datos por HTTP | HTTP 404 Not Found (Apache sirve solo DocumentRoot `/var/www/html`) | `b08_02_seguridad_json_privado_404.png` |
| **Control de acceso** | Abrir `home.php` y `canciones.php` sin sesión activa | Redirección forzosa al formulario de login | HTTP 303 See Other con cabecera `Location: login.php` | `b08_01_navegador_catalogo_json.png`, `lib/acceso.php` |
| **Protección CSRF** | Petición POST sin token o con token alterado | HTTP 403 Forbidden sin escribir datos | HTTP 403 Forbidden "Solicitud no válida", JSON intacto | `b09_05_csrf_403_rechazo.png` |
| **CRUD de canciones** | Create, Read, Update y Delete por identificador | Modifica exclusivamente la ficha solicitada | Alta ID=4, Edición ID=3, Borrado ID=2 con reindexación | `b10_01_navegador_crud_lista.png` a `b10_04_navegador_crud_eliminar.png` |
| **Validación de método/ID** | GET mutador, método incorrecto o ID inexistente | Sin mutaciones en datos; códigos 405/404/400 | Logout por GET da 405; ID=999 da 404; ID=abc da 400 | Pruebas de límites en scripts del CRUD |
| **Persistencia** | Reiniciar contenedor web (`docker restart web`) | Catálogo JSON y cuentas persisten intactos | `canciones.json` y `usuarios.json` se conservan en `docker/data/` | Verificación de persistencia tras reinicio |
| **Seguridad de API** | GET a `http://localhost:8091/api/canciones.php` | 401 JSON sin sesión; 200 con lista JSON si autenticado | Sin sesión: 401 JSON (sin redirigir a HTML). Con sesión: array plano de tracks | `b11_02_terminal_api_canciones.png` |
| **Integración Jukebox** | Acceder a `home.php` con sesión activa | El reproductor carga la playlist y reproduce audio | Fetch dinámico a la API, renderizado seguro con `textContent` y reproducción HTML5 | `b11_01_navegador_home_jukebox.png` |

---

## Preguntas para la defensa y examen final de RA1

1. **Una canción borrada sigue sonando tras volver a la home. ¿Qué comprobarías?**
   - Compara la lista que devuelve `/api/canciones.php` con la lista cargada en la página y el recurso de audio actual; después recarga para comprobar que el cliente no conserva otra colección independiente.
   - **Evidencia**: Respuesta JSON autenticada y estado de la interfaz. Bloques 10–11.

2. **Un POST de edición recibe 403 y el catálogo no cambia. ¿Qué revisarías?**
   - Confirma sesión, método POST y que el formulario envió el token asociado a esa sesión; vuelve a cargar el formulario para obtener un token válido. No copies el valor del token.
   - **Evidencia**: Estado HTTP 403 y comparación del JSON antes/después. Bloques 9–10.
