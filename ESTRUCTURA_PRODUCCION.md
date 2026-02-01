# Estructura del Proyecto RolPlay EDU - Listo para Producción

## ✅ Reorganización Completada

Este documento describe la reorganización del proyecto para despliegue en producción.

**Fecha de reorganización**: 27 de Enero de 2026
**Respaldo creado en**: `../rolplay_backup_20260127_231116/`

---

## 📁 Nueva Estructura

```
rolplay/                        # Raíz del proyecto
├── .env                        # Configuración de entorno (NO INCLUIR EN GIT)
├── .env.example                # Plantilla de configuración
├── .gitignore                  # Archivos ignorados por Git
├── composer.json               # Dependencias PHP
├── README.md                   # Documentación principal
├── ESTRUCTURA_PRODUCCION.md    # Este archivo
│
├── app/                        # Aplicación MVC
│   ├── controllers/            # Controladores (AdminController, AuthController, etc.)
│   ├── core/                   # Núcleo del framework (Router, Request, Session)
│   ├── middleware/             # Middleware (AdminMiddleware, AuthMiddleware)
│   ├── models/                 # Modelos de datos (User, Scenario, Achievement, etc.)
│   ├── routes.php              # Definición de rutas de la aplicación
│   ├── services/               # Servicios (GeminiAIService, ProgramAnalysisService)
│   └── views/                  # Vistas PHP
│       ├── admin/              # Vistas del panel de administración
│       ├── auth/               # Login y registro
│       ├── instructor/         # Panel de instructor
│       ├── layouts/            # Layouts compartidos (main.php)
│       ├── profile/            # Perfil de usuario
│       ├── programs/           # Gestión de programas
│       ├── routes/             # Rutas de aprendizaje
│       ├── scenarios/          # Escenarios de simulación
│       └── sessions/           # Sesiones de juego
│
├── config/                     # Configuración
│   ├── app.php                 # Configuración general de la app
│   ├── database.php            # Configuración de base de datos
│   └── gemini.php              # Configuración de Gemini AI
│
├── database/                   # Base de datos
│   ├── migrations/             # Migraciones SQL
│   │   └── add_is_active_to_scenarios.sql
│   ├── seeders/                # Datos iniciales
│   │   ├── seed_achievements.sql
│   │   └── seed_programs.sql
│   ├── schema.sql              # Esquema completo de la BD
│   └── seed_scenarios.sql      # Escenarios base del sistema
│
├── docs/                       # Documentación
│   ├── ADMINISTRADOR_README.md              # Guía del rol administrador
│   ├── CORRECCIONES_CRITICAS.md             # Correcciones aplicadas
│   ├── FUNCIONALIDADES_IMPLEMENTADAS.md     # Características del sistema
│   ├── GUIA_VISUAL_BRANDING.md              # Guía de estilos SENA 2025
│   ├── README.md                            # Índice de documentación
│   └── SRS_RolPlay_EDU.md                   # Especificación de requisitos
│
├── img/                        # Recursos gráficos
│   ├── LogoRP2.png             # Logo RolPlay EDU (alternativo)
│   ├── LogoRP3.png             # Logo RolPlay EDU (principal)
│   ├── logoSena.png            # Logo SENA oficial
│   └── README.md               # Descripción de imágenes
│
├── public/                     # Document Root (punto de entrada web)
│   ├── .htaccess               # Configuración Apache (mod_rewrite)
│   ├── index.php               # Front controller de la aplicación
│   ├── assets/                 # Recursos estáticos (CSS, JS)
│   │   ├── css/
│   │   │   └── styles.css
│   │   └── js/
│   │       └── components/
│   │           └── toast.js
│   └── uploads/                # Archivos subidos por usuarios
│       └── programs/           # PDFs de programas de formación
│
└── storage/                    # Almacenamiento temporal y logs
    ├── cache/                  # Cache de la aplicación
    ├── logs/                   # Logs del sistema
    │   └── app.log
    └── uploads/                # Uploads temporales
```

---

## 🗑️ Archivos Eliminados

Los siguientes archivos de desarrollo fueron eliminados para producción:

### Carpetas:
- `.claude/` - Herramienta de desarrollo de IA
- `online-version/` - Estructura antigua (contenido movido a raíz)

### Archivos en raíz:
- `.htaccess` - Ya no necesario (redirigía a online-version)
- `COMANDOS_UTILES.md` - Documentación de desarrollo
- `ejemplo_api.html` - Ejemplo de prueba de API
- `index.php` - Landing page de desarrollo
- `nul` - Archivo temporal/error
- `ORGANIZACION_PROYECTO.md` - Documentación de desarrollo
- `SOLUCION_ERROR_403.md` - Guía de solución de errores
- `verificar_instalacion.bat` - Script de verificación

### Archivos en public/:
- `public/test.php` - Archivo de pruebas

---

## 🔧 Cambios de Configuración

### 1. `.htaccess` en `public/`

**Antes**:
```apache
RewriteBase /rolplay/online-version/public/
```

**Después**:
```apache
# Development (XAMPP local): Descomenta la siguiente línea
# RewriteBase /rolplay/public/

# Production (Document Root apunta a /public): Descomenta la siguiente línea
RewriteBase /
```

### 2. Document Root del Servidor

**Desarrollo Local (XAMPP)**:
```
DocumentRoot "C:/xampp/htdocs/rolplay/public"
```

**Producción (Linux)**:
```
DocumentRoot /var/www/rolplay/public
```

---

## 🚀 Pasos para Despliegue

### 1. Configuración del Servidor

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/rolplay-edu.git /var/www/rolplay

# 2. Configurar permisos
cd /var/www/rolplay
chmod -R 775 storage/
chmod -R 775 public/uploads/
chown -R www-data:www-data storage/ public/uploads/

# 3. Instalar dependencias
composer install --no-dev --optimize-autoloader
```

### 2. Configurar Variables de Entorno

```bash
# Copiar .env.example a .env
cp .env.example .env

# Editar .env con credenciales de producción
nano .env
```

**Configuración mínima requerida en `.env`**:
```env
APP_ENV=production
APP_DEBUG=false
DB_HOST=tu-servidor-mysql
DB_DATABASE=rolplay_edu
DB_USERNAME=tu-usuario-bd
DB_PASSWORD=contraseña-segura
GEMINI_API_KEY=tu-api-key
```

### 3. Configurar Base de Datos

```bash
# Crear base de datos
mysql -u root -p -e "CREATE DATABASE rolplay_edu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Importar esquema
mysql -u root -p rolplay_edu < database/schema.sql

# Cargar datos iniciales
mysql -u root -p rolplay_edu < database/seed_scenarios.sql
mysql -u root -p rolplay_edu < database/seeders/seed_achievements.sql

# Aplicar migraciones
mysql -u root -p rolplay_edu < database/migrations/add_is_active_to_scenarios.sql
```

### 4. Configurar Apache Virtual Host

Crear archivo `/etc/apache2/sites-available/rolplay.conf`:

```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    ServerAdmin admin@tu-dominio.com
    DocumentRoot /var/www/rolplay/public

    <Directory /var/www/rolplay/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/rolplay_error.log
    CustomLog ${APACHE_LOG_DIR}/rolplay_access.log combined
</VirtualHost>
```

```bash
# Habilitar sitio
sudo a2ensite rolplay.conf

# Habilitar mod_rewrite
sudo a2enmod rewrite

# Reiniciar Apache
sudo systemctl restart apache2
```

### 5. Configurar HTTPS

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-apache

# Obtener certificado SSL
sudo certbot --apache -d tu-dominio.com

# Renovación automática (se configura automáticamente)
```

---

## ✅ Checklist de Despliegue

### Seguridad:
- [ ] Cambiar contraseña del admin (`admin@rolplayedu.com`)
- [ ] Configurar `.env` con `APP_ENV=production` y `APP_DEBUG=false`
- [ ] Usar contraseñas seguras para MySQL
- [ ] Configurar HTTPS con certificado SSL válido
- [ ] Restringir acceso a archivos sensibles (.env, composer.json)
- [ ] Configurar firewall (UFW en Linux)

### Rendimiento:
- [ ] Habilitar OPcache en `php.ini`
- [ ] Configurar límites adecuados de PHP (memory_limit, upload_max_filesize)
- [ ] Habilitar compresión gzip en Apache
- [ ] Configurar cache de aplicación

### Monitoreo:
- [ ] Configurar logs de Apache
- [ ] Configurar logs de PHP
- [ ] Configurar backups automáticos de base de datos
- [ ] Configurar monitoreo de uptime

### Funcionalidad:
- [ ] Verificar que todas las rutas funcionan correctamente
- [ ] Probar login de admin, instructor y aprendiz
- [ ] Probar carga de programas documento
- [ ] Probar generación de escenarios con IA
- [ ] Verificar que los uploads funcionan
- [ ] Probar creación de sesiones de simulación

---

## 📊 Información Técnica

### Tecnologías Utilizadas:

- **Backend**: PHP 8.1+ (MVC custom)
- **Base de datos**: MySQL 5.7+
- **Frontend**: Tailwind CSS 3.x (vía CDN)
- **JavaScript**: Vanilla JS (componentes nativos)
- **IA**: Google Gemini 2.0 Flash
- **Servidor**: Apache 2.4+

### Dependencias PHP (composer.json):

```json
{
    "require": {
        "php": "^8.1",
        "vlucas/phpdotenv": "^5.5",
    }
}
```

### Rutas Principales:

| Ruta | Descripción | Acceso |
|------|-------------|--------|
| `/` | Dashboard (redirige según rol) | Autenticado |
| `/login` | Inicio de sesión | Público |
| `/register` | Registro de usuarios | Público |
| `/admin` | Panel de administración | Admin |
| `/instructor` | Panel de instructor | Instructor |
| `/scenarios` | Lista de escenarios | Autenticado |
| `/programs` | Gestión de programas | Instructor |
| `/profile` | Perfil de usuario | Autenticado |

---

## 🔄 Respaldo

Se creó un respaldo completo antes de la reorganización:

**Ubicación**: `../rolplay_backup_20260127_231116/`

Para restaurar el respaldo:
```bash
rm -rf /var/www/rolplay/*
cp -r /var/www/rolplay_backup_20260127_231116/* /var/www/rolplay/
```

---

## 📝 Notas Finales

- El proyecto está completamente organizado y listo para producción
- Todos los archivos innecesarios de desarrollo han sido eliminados
- La estructura sigue el patrón MVC estándar de PHP
- El document root debe apuntar siempre a la carpeta `public/`
- Nunca exponer las carpetas `app/`, `config/`, `database/` o `storage/` al público
- Mantener `.env` fuera del control de versiones (ya está en `.gitignore`)

---

**Última actualización**: 27 de Enero de 2026
**Versión**: 1.0 - Producción Ready
**Responsable**: Sistema de Reorganización Automatizada
