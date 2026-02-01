# Manual de Administrador - RolPlay EDU

Version: 2026-01-30

## 1. Objetivo
Este manual describe las tareas y pantallas disponibles para el rol **Administrador** en RolPlay EDU: gestion de usuarios, escenarios, configuracion del sistema, logs y acciones administrativas.

## 2. Acceso al panel
1. Inicia sesion con un usuario con rol **admin**.
2. Ingresa a **Dashboard** (menu superior) o directamente a `/admin`.

## 3. Panel de Administracion
En el dashboard se muestran indicadores generales:
- Total de usuarios y distribucion por rol.
- Total de escenarios.
- Sesiones: activas, completadas, dinamicas y promedio de puntaje.

## 4. Gestion de Usuarios
Ruta: **/admin/users**

Acciones:
- **Crear usuario**: nombre, correo, rol (aprendiz/instructor/admin) y ficha (opcional para aprendiz).
- **Editar usuario**: actualizar datos y rol.
- **Eliminar usuario**: desactiva el usuario (no elimina tu propio admin).

Buenas practicas:
- Verifica que el correo sea unico.
- Mantener fichas consistentes para asignacion de rutas.

## 5. Gestion de Escenarios
Ruta: **/admin/scenarios**

Acciones:
- Ver listado de escenarios con estado.
- **Activar/Desactivar** escenarios segun disponibilidad.

## 6. Configuracion del Sistema
Ruta: **/admin/settings**

### 6.1 Configuracion general
- Nombre de la aplicacion.
- Modo mantenimiento.

### 6.2 Integraciones
- Estado de base de datos.
- Estado de TCPDF (reportes).
- **Integracion con Puter**:
  - Activar/Desactivar la integracion.
  - Usuario/correo sugerido (login hint).
  - Modo de acceso (login/consent/seleccion de cuenta).
  - Auto-login al cargar.
  - Contrasena (no visible; si se deja vacio se conserva la anterior).

Nota: Puter requiere autenticacion mediante su ventana oficial; el sistema usa el usuario como pista.

### 6.3 Seguridad
- Hashing de contrasenas (PASSWORD_BCRYPT).
- Sesiones PHP activas.
- Proteccion SQL Injection via PDO.

### 6.4 Gamificacion
- Habilitar/deshabilitar logros.
- Habilitar/deshabilitar ranking.
- Habilitar/deshabilitar notificaciones.

## 7. Acciones del Sistema
En **/admin/settings**:
- Limpiar cache.
- Backup de base de datos.
- Exportar usuarios CSV.
- Ver logs.

## 8. Logs del sistema
Ruta: **/admin/logs**
- Permite revisar los ultimos eventos registrados.

## 9. Recomendaciones operativas
- Realiza backups periodicos.
- Verifica que Puter este habilitado antes de habilitar analisis IA.
- Desactiva escenarios obsoletos para evitar que se asignen.
