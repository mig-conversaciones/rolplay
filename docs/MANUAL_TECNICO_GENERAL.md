# Manual Tecnico General - RolPlay EDU

Version: 2026-01-30

## 1. Arquitectura general
- Backend: PHP (estructura MVC propia).
- Frontend: HTML + Tailwind CSS + componentes neumorfismo.
- BD: MySQL.
- IA: Puter.js con modelo `gemini-2.5-flash`.
- PDF: PDF.js para extraccion de texto.

## 2. Estructura de carpetas (resumen)
- `app/controllers`: logica de controladores.
- `app/models`: acceso a datos.
- `app/views`: vistas por modulo.
- `public/assets`: CSS/JS estaticos.
- `database/migrations`: scripts SQL.
- `storage`: logs, backups y cache.

## 3. Flujo IA con Puter.js
- Se autentica mediante popup oficial (signIn).
- Analisis de PDF en frontend con PDF.js.
- Respuestas JSON validadas y normalizadas.

## 4. Rutas principales del sistema
- Auth: `/login`, `/register`, `/logout`.
- Aprendiz: `/scenarios`, `/routes`, `/profile`.
- Instructor: `/instructor/programs`, `/instructor/routes`.
- Admin: `/admin`, `/admin/settings`.

## 5. Base de datos (resumen conceptual)
- `users`: usuarios, roles y ficha.
- `programs`: programas, analisis IA y PDF.
- `scenarios`: escenarios y pasos.
- `sessions`: sesiones de juego y puntajes.
- `routes`: rutas de aprendizaje.
- `system_settings`: configuraciones del sistema.

## 6. Configuracion del sistema
- Panel admin en `/admin/settings`.
- Integracion Puter configurable (habilitar, usuario, auto-login, modo).

## 7. Seguridad
- Hash de contrasenas: `password_hash`.
- Consultas parametrizadas PDO.
- Control de acceso por rol en controladores.

## 8. Operacion y mantenimiento
- Backup DB desde Admin > Settings.
- Logs accesibles en `/admin/logs`.
- Limpieza de cache disponible.

## 9. Consideraciones de despliegue
- Requiere PHP 8+ y MySQL 8+ (XAMPP local en desarrollo).
- Variables en `.env`.
- Permisos de escritura en `storage/`.
