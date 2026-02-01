# 🔐 Documentación del Rol de Administrador - RolPlay EDU

## 📋 Descripción General

El rol de **Administrador** es un rol de sistema especial diseñado para usuarios técnicos responsables del despliegue, configuración y gestión de datos maestros de toda la aplicación RolPlay EDU.

---

## ⚠️ Seguridad y Acceso

### **NO SE PUEDE REGISTRAR PÚBLICAMENTE**

El rol de administrador **NO está disponible** en el formulario de registro público (`/register`). Esta medida de seguridad asegura que:

- Solo personal autorizado tenga acceso administrativo
- Los administradores se creen directamente en la base de datos
- Se mantenga la integridad y seguridad del sistema

### Cómo Crear un Administrador

Los administradores se crean directamente en la base de datos MySQL:

```sql
-- Ejemplo de creación de administrador
-- Contraseña: admin123 (CAMBIAR EN PRODUCCIÓN)
INSERT INTO users (name, email, password, role, email_verified, active)
VALUES (
    'Administrador Sistema',
    'admin@rolplayedu.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'admin',
    TRUE,
    TRUE
);
```

**Nota:** Ya existe un usuario administrador por defecto:
- **Email:** `admin@rolplayedu.com`
- **Contraseña:** `admin123` (CAMBIAR EN PRODUCCIÓN)

---

## 🎛️ Funcionalidades del Panel de Administración

### 1. **Dashboard Principal** (`/admin`)

**Archivo:** `app/views/admin/dashboard.php`

#### KPIs Disponibles:
- 👥 **Usuarios Totales**: Cantidad de usuarios registrados
- 🎮 **Escenarios Activos**: Escenarios disponibles para los usuarios
- 📊 **Sesiones Totales**: Simulaciones realizadas
- 🏆 **Logros Configurados**: Sistema de gamificación

#### Información Adicional:
- **Distribución por Rol**: Cantidad de admins, instructores y aprendices
- **Usuarios Recientes**: Últimos registros en el sistema
- **Actividad del Sistema**: Sesiones activas y tasa de completitud

#### Acciones Rápidas:
- Gestionar Usuarios
- Gestionar Escenarios
- Gestionar Logros
- Configuración del Sistema

---

### 2. **Gestión de Usuarios** (`/admin/users`)

**Archivo:** `app/views/admin/users/index.php`

#### Funcionalidades:
- ✅ **Ver todos los usuarios** del sistema
- ➕ **Crear nuevos usuarios** (incluyendo administradores)
- ✏️ **Editar información** de usuarios existentes
- 🗑️ **Eliminar usuarios** (con confirmación modal)
- 🔍 **Filtrar y buscar** por:
  - Rol (admin, instructor, aprendiz)
  - Nombre
  - Email

#### Vistas Relacionadas:
- `app/views/admin/users/create.php` - Formulario de creación
- `app/views/admin/users/edit.php` - Formulario de edición

#### Datos Maestros Gestionados:
- Nombre del usuario
- Email
- Rol (admin, instructor, aprendiz)
- Contraseña (encriptada con bcrypt)
- Ficha (para aprendices)
- Estado activo/inactivo

---

### 3. **Gestión de Escenarios** (`/admin/scenarios`)

**Archivo:** `app/views/admin/scenarios/index.php`

#### Funcionalidades:
- 📋 **Listar todos los escenarios** (activos e inactivos)
- ✅ **Activar/Desactivar escenarios** con un clic
- 👁️ **Previsualizar escenarios** en nueva ventana
- 🔍 **Filtrar por**:
  - Área (tecnología, comercio, salud, etc.)
  - Dificultad (básico, intermedio, avanzado)
  - Estado (activo/inactivo)

#### Estadísticas Mostradas:
- Total de escenarios
- Escenarios activos
- Escenarios generados con IA
- Escenarios inactivos

#### Datos Maestros Gestionados:
- **Escenarios base** del sistema
- **Estado de activación** (is_active)
- **Clasificación** por área y dificultad

**Nota:** Los instructores pueden crear nuevos escenarios desde su panel. El admin solo activa/desactiva.

---

### 4. **Configuración del Sistema** (`/admin/settings`)

**Archivo:** `app/views/admin/settings.php`

#### Secciones:

**a) Configuración General:**
- Nombre de la aplicación
- Versión del sistema
- Modo de mantenimiento (toggle on/off)

**b) Integraciones:**
- 🤖 **OpenAI API**: Generación de escenarios con IA
- 🗄️ **Base de Datos**: Conexión MySQL
- 📄 **motor de reportes**: Generación de reportes documento

**c) Seguridad:**
- Hashing de contraseñas (bcrypt)
- Sesiones PHP
- Protección contra SQL Injection (PDO prepared statements)

**d) Gamificación:**
- Sistema de logros (toggle on/off)
- Ranking global (toggle on/off)
- Notificaciones de logros (toggle on/off)

**e) Información del Servidor:**
- Versión de PHP
- Servidor web (Apache/Nginx)
- Sistema operativo

**f) Acciones del Sistema:**
- 🔄 Limpiar caché
- 💾 Backup de base de datos
- 📤 Exportar datos
- 📊 Ver logs del sistema

**Estado:** ⚠️ Algunas opciones son interfaces preliminares (en desarrollo)

---

## 📊 Datos Maestros Gestionados por el Administrador

### 1. **Usuarios del Sistema**
- Creación de administradores adicionales
- Gestión de instructores e aprendices
- Asignación de roles
- Activación/desactivación de cuentas

### 2. **Escenarios Base**
- Activación/desactivación de escenarios
- Visibilidad en la plataforma
- Control de contenido disponible

### 3. **Configuración Global**
- Parámetros del sistema
- Integraciones con APIs externas
- Opciones de gamificación
- Configuración de seguridad

### 4. **Logros del Sistema** (Próximamente)
- Definición de achievements
- Requisitos para desbloquear
- Puntos y recompensas

---

## 🔒 Diferencias entre Roles

| Característica | Admin ⚙️ | Instructor 👨‍🏫 | Aprendiz 👨‍🎓 |
|---------------|----------|----------------|----------------|
| **Acceso** | Creado en BD | Registro público | Registro público |
| **Gestionar usuarios** | ✅ | ❌ | ❌ |
| **Gestionar escenarios** | ✅ (activar/desactivar) | ✅ (crear/editar) | ❌ |
| **Crear programas documento** | ❌ | ✅ | ❌ |
| **Generar escenarios IA** | ❌ | ✅ | ❌ |
| **Jugar escenarios** | ✅ | ✅ | ✅ |
| **Ver reportes** | ✅ (todos) | ✅ (sus alumnos) | ✅ (propios) |
| **Configuración sistema** | ✅ | ❌ | ❌ |
| **Gestionar logros** | ✅ | ❌ | ❌ |

---

## 🚀 Flujo de Trabajo del Administrador

### Al Desplegar el Sistema:

1. **Instalación Inicial:**
   ```bash
   # 1. Configurar base de datos
   mysql -u root -p < database/schema.sql

   # 2. Cargar escenarios base
   mysql -u root -p rolplay_edu < database/seed_scenarios.sql

   # 3. Cargar logros
   mysql -u root -p rolplay_edu < database/seeders/seed_achievements.sql
   ```

2. **Acceso Inicial:**
   - URL: `http://localhost/rolplay/online-version/public/login`
   - Email: `admin@rolplayedu.com`
   - Password: `admin123`

3. **Configuración Inicial:**
   - Cambiar contraseña del administrador
   - Revisar configuración en `/admin/settings`
   - Verificar integraciones (OpenAI API, etc.)

4. **Gestión de Contenido:**
   - Activar/desactivar escenarios base según necesidad
   - Crear usuarios instructores iniciales
   - Configurar logros del sistema

### Durante la Operación:

1. **Monitoreo:**
   - Revisar dashboard con KPIs
   - Verificar actividad de usuarios
   - Monitorear sesiones activas

2. **Gestión de Usuarios:**
   - Resolver problemas de acceso
   - Crear cuentas especiales
   - Desactivar usuarios si es necesario

3. **Control de Contenido:**
   - Activar nuevos escenarios generados por instructores
   - Desactivar escenarios problemáticos
   - Mantener calidad del contenido

4. **Mantenimiento:**
   - Realizar backups periódicos
   - Limpiar caché si es necesario
   - Revisar logs del sistema

---

## 🛡️ Mejores Prácticas de Seguridad

### Para Administradores:

1. **Contraseñas:**
   - ⚠️ CAMBIAR la contraseña por defecto (`admin123`) inmediatamente
   - Usar contraseñas fuertes (mínimo 12 caracteres)
   - No compartir credenciales

2. **Acceso:**
   - No crear administradores innecesarios
   - Usar rol de instructor para gestión de contenido
   - Mantener log de acciones administrativas

3. **Base de Datos:**
   - Hacer backups regulares
   - Restringir acceso directo a MySQL
   - No exponer credenciales en código

4. **Despliegue:**
   - Cambiar credenciales por defecto antes de producción
   - Configurar HTTPS en producción
   - Restringir acceso a `/admin` por IP si es posible

---

## 📁 Archivos Clave

### Controlador:
- `app/controllers/AdminController.php`

### Vistas:
- `app/views/admin/dashboard.php`
- `app/views/admin/users/index.php`
- `app/views/admin/users/create.php`
- `app/views/admin/users/edit.php`
- `app/views/admin/scenarios/index.php`
- `app/views/admin/settings.php`

### Modelos:
- `app/models/User.php`
- `app/models/Scenario.php`

### Middlewares:
- `app/middlewares/AdminMiddleware.php` (verifica rol admin)

---

## 🔄 Actualizaciones Futuras Planeadas

### Próximas Funcionalidades:

1. **Gestión Completa de Logros:**
   - CRUD completo de achievements
   - Configuración de requisitos
   - Asignación manual de logros

2. **Analytics Avanzado:**
   - Gráficos de uso por período
   - Métricas de engagement
   - Reportes exportables

3. **Gestión de Programas:**
   - Ver todos los programas cargados
   - Gestionar análisis de IA
   - Aprobar/rechazar programas

4. **Sistema de Logs:**
   - Historial de acciones administrativas
   - Auditoría de cambios
   - Alertas de actividad sospechosa

5. **Configuración Avanzada:**
   - Edición de parámetros del sistema
   - Personalización de emails
   - Configuración de notificaciones

6. **Gestión de Contenido:**
   - Editor de escenarios incorporado
   - Biblioteca de recursos compartidos
   - Sistema de categorías y etiquetas

---

## 📞 Soporte Técnico

Para soporte técnico o reportar problemas:

- **Repositorio:** [GitHub - RolPlay EDU](https://github.com/...)
- **Documentación:** `/docs/`
- **Issues:** Crear issue en GitHub

---

## 📝 Notas Adicionales

- El sistema está diseñado con arquitectura MVC
- Usa PDO con prepared statements para seguridad
- Las contraseñas se encriptan con `password_hash()` (bcrypt)
- Las sesiones se manejan con `$_SESSION` nativa de PHP
- Compatible con PHP 8.1+

---

**Última actualización:** 27 de Enero de 2026
**Versión del documento:** 1.0
**Sistema:** RolPlay EDU - SENA
