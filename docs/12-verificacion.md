# Bloque 12 — Matriz de aceptación

Completa «Resultado real» y «Evidencia» únicamente después de ejecutar la prueba.
Estado inicial: pendiente. No se han marcado como satisfactorias pruebas que no se
hayan ejecutado.

| Prueba | Preparación | Resultado esperado | Resultado real | Evidencia |
|---|---|---|---|---|
| Etapa inicial | Arrancar Compose raíz | PHP responde en 8090 | Pendiente | Pendiente |
| Etapa ampliada | Copiar `.env.example`, construir Compose de `docker/` | Web 8091 y dos DB saludables | Pendiente | Pendiente |
| PDO | Abrir `11-conexiones.php` | Dos estados `ok`, sin claves | Pendiente | Pendiente |
| Registro | Crear usuario ficticio | Alta única con hash | Pendiente | Pendiente |
| Registro inválido | Repetir correo, enviar array y confirmar distinto | Rechazo sin nueva cuenta | Pendiente | Pendiente |
| Privacidad | Pedir `/data/usuarios.json` | No descargar los datos | Pendiente | Pendiente |
| Acceso | Abrir home y catálogo sin sesión | Redirección a login | Pendiente | Pendiente |
| CSRF | POST sin token o token incorrecto | HTTP 403, sin escritura | Pendiente | Pendiente |
| CRUD | Alta, edición y borrado por id | Solo cambia la ficha solicitada | Pendiente | Pendiente |
| Método/id | GET mutador, método incorrecto e id inexistente | Sin escritura; 405/404 según caso | Pendiente | Pendiente |
| Persistencia | Reiniciar solo `web` | JSON permanece | Pendiente | Pendiente |
| API | Probar con y sin sesión | Lista JSON / 401 JSON | Pendiente | Pendiente |
| Reproductor | Añadir audios autorizados y abrir home | Reproduce, cambia de pista y trata vacío | Pendiente | Pendiente |

No incluyas cookies, tokens, contraseñas, hashes ni datos personales en capturas.
La carpeta `docker/data/` queda ignorada por Git; revisa `git status` y `git diff` antes
de compartir el proyecto.

## Presentación

Abre [arquitectura.drawio](./arquitectura.drawio) en diagrams.net y adapta el esquema a
la ejecución real. Explica una petición desde navegador hasta Apache/PHP, identifica
estado y tipo de respuesta, y distingue arrays PHP, JSON privado, hashes, sesiones y
audios estáticos.

Preguntas de práctica para revisar con el grupo:

1. **Una canción borrada sigue sonando tras volver a la home. ¿Qué comprobarías?**
   Compara la lista que devuelve `/api/canciones.php` con la lista cargada en la página
   y el recurso de audio actual; después recarga para comprobar que el cliente no conserva
   otra colección independiente. Evidencia: respuesta JSON autenticada y estado de la
   interfaz. Bloques 10–11.
2. **Un POST de edición recibe 403 y el catálogo no cambia. ¿Qué revisarías?**
   Confirma sesión, método POST y que el formulario envió el token asociado a esa sesión;
   vuelve a cargar el formulario para obtener un token válido. No copies el valor del token.
   Evidencia: estado HTTP y comparación del JSON antes/después. Bloques 9–10.
