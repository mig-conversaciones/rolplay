RolPlay EDU - Plataforma Gamificada SENA

Plataforma educativa gamificada para el desarrollo de competencias transversales en aprendices del SENA, con simulaciones interactivas y generación de escenarios asistida por IA (Puter.js).

## 🎯 Características Principales

- ✅ Sistema de autenticación con roles (Admin, Instructor, Aprendiz)
- ✅ Gestión de escenarios de simulación
- ✅ Generación de escenarios con IA (Puter.js)
- ✅ Análisis de programas de formación (documento)
- ✅ Sistema de logros y gamificación
- ✅ Seguimiento de progreso de aprendices
- ✅ Panel de administración completo
- ✅ Interfaz moderna con Tailwind CSS

## 📁 Estructura del Proyecto

```
rolplay/
├── app/                    # Aplicación MVC
│   ├── controllers/        # Controladores
│   ├── core/              # Núcleo del framework
│   ├── middleware/        # Middleware de autenticación
│   ├── models/            # Modelos de datos
│   ├── routes.php         # Definición de rutas
│   ├── services/          # Servicios (IA, análisis)
│   └── views/             # Vistas (PHP templates)
├── config/                # Configuración
│   ├── app.php           # Configuración general
│   └── database.php      # Configuración de BD
├── database/             # Base de datos
│   ├── migrations/       # Migraciones SQL
│   ├── seeders/          # Datos iniciales
│   ├── schema.sql        # Esquema completo
│   └── seed_scenarios.sql # Escenarios base
├── public/               # Document Root (punto de entrada web)
│   ├── .htaccess        # Configuración Apache
│   ├── index.php        # Front controller
│   ├── assets/          # CSS, JS, imágenes
│   └── uploads/         # Archivos subidos
├── storage/             # Almacenamiento
│   ├── cache/          # Cache
│   ├── logs/           # Logs
│   └── uploads/        # Uploads temporales
├── .env                # Configuración de entorno (NO INCLUIR EN GIT)
├── .env.example        # Plantilla de configuración
├── .gitignore          # Archivos ignorados por Git
├── composer.json       # Dependencias PHP
└── README.md          # Este archivo
```

## 🔧 Requisitos del Sistema

- **PHP**: 8.1 o superior
- **MySQL**: 5.7 o superior
- **Servidor Web**: Apache 2.4+ con mod_rewrite
- **Composer**: Para gestión de dependencias
- **Extensiones PHP requeridas**:
  - PDO
  - pdo_mysql
  - mbstring
  - openssl
  - fileinfo
  - json
- **Opcional**: `extractor_texto` para extracción de texto de PDFs

## 🚀 Instalación y Configuración

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/rolplay-edu.git
cd rolplay-edu
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar Variables de Entorno

Copia el archivo `.env.example` a `.env` y configura tus credenciales:

```bash
cp .env.example .env
```

Edita el archivo `.env`:

```env
# Entorno de la aplicación
APP_ENV=production          # local | production
APP_DEBUG=false            # true en desarrollo, false en producción
APP_TIMEZONE=America/Bogota
APP_URL=https://tu-dominio.com

# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rolplay_edu
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña_segura
```

### 4. Crear la Base de Datos

Crea la base de datos en MySQL:

```sql
CREATE DATABASE rolplay_edu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Importa el esquema completo:

```bash
mysql -u root -p rolplay_edu < database/schema.sql
```

Carga los escenarios base:

```bash
mysql -u root -p rolplay_edu < database/seed_scenarios.sql
```

Carga los logros del sistema:

```bash
mysql -u root -p rolplay_edu < database/seeders/seed_achievements.sql
```

Aplica migraciones adicionales si las hay:

```bash
mysql -u root -p rolplay_edu < database/migrations/add_is_active_to_scenarios.sql
```

### 5. Configurar Permisos

Asegúrate de que el servidor web tenga permisos de escritura en:

```bash
chmod -R 775 storage/
chmod -R 775 public/uploads/
```

### 6. Configurar Apache

#### Opción A: Desarrollo Local (XAMPP/WAMP)

Si estás usando XAMPP, edita `httpd.conf` o `httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName rolplay.local
    DocumentRoot "C:/xampp/htdocs/rolplay/public"

    <Directory "C:/xampp/htdocs/rolplay/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Edita `public/.htaccess` y descomenta la línea de desarrollo:

```apache
# Development (XAMPP local): Descomenta la siguiente línea
RewriteBase /rolplay/public/
```

#### Opción B: Producción (Servidor Linux)

Configura el Virtual Host apuntando a `/public`:

```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
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

En producción, asegúrate de que `public/.htaccess` tenga:

```apache
# Production (Document Root apunta a /public): Descomenta la siguiente línea
RewriteBase /
```

### 7. Configurar HTTPS (Producción)

Para producción, **siempre** usa HTTPS con Let's Encrypt:

```bash
sudo certbot --apache -d tu-dominio.com
```

## 👤 Usuario Administrador por Defecto

El sistema incluye un usuario administrador por defecto:

- **Email**: `admin@rolplayedu.com`
- **Contraseña**: `admin123`

⚠️ **IMPORTANTE**: Cambia esta contraseña inmediatamente en producción.

## 🔑 Roles del Sistema

| Rol | Descripción | Registro Público |
|-----|-------------|------------------|
| **Admin** | Administrador del sistema. Gestiona usuarios, escenarios y configuración global. | ❌ No (se crea en BD) |
| **Instructor** | Carga programas documento, genera escenarios con IA, evalúa aprendices. | ✅ Sí |
| **Aprendiz** | Completa escenarios, gana logros, visualiza su progreso. | ✅ Sí |

## 📚 Documentación Adicional

- **[Funcionalidades Implementadas](docs/FUNCIONALIDADES_IMPLEMENTADAS.md)**: Lista completa de características
- **[Documentación del Administrador](docs/ADMINISTRADOR_README.md)**: Guía completa del rol admin
- **[SRS - Especificación de Requisitos](docs/SRS_RolPlay_EDU.md)**: Documentación técnica completa
- **[Guía Visual de Branding](docs/GUIA_VISUAL_BRANDING.md)**: Colores y estilos SENA 2025

(Nota: Los archivos de documentación se encuentran en el historial del repositorio si fueron eliminados en la limpieza).

## 🤖 Integración con IA (Puter.js)

La plataforma utiliza **Puter.js** para potenciar las funcionalidades de Inteligencia Artificial de forma gratuita y segura desde el navegador.

### Funcionalidades Potenciadas por AI:

1. **Análisis de Programas**: Extrae competencias transversales de PDFs de programas SENA.
2. **Generación de Escenarios**: Crea escenarios de simulación personalizados y dinámicos.
3. **Feedback Inteligente**: Proporciona retroalimentación contextual a las decisiones de los aprendices.

### Stack de IA:

- **Frontend**: `Puter.js` (Biblioteca cliente)
- **Backend**: Servicios auxiliares en `app/services/` para orquestación.

No se requiere configuración de API Keys en el backend para la funcionalidad básica de Puter.js, ya que opera del lado del cliente.

## 🧪 Pruebas

Para probar la instalación:

1. Accede a: `http://tu-dominio.com` (o `http://localhost/rolplay/public` en desarrollo)
2. Registra un usuario con rol "Aprendiz" o "Instructor"
3. Inicia sesión con el admin: `admin@rolplayedu.com` / `admin123`
4. Explora el panel de administración

## 🛠️ Solución de Problemas Comunes

### Error 403 Forbidden

- Verifica que Apache tenga `AllowOverride All` en la configuración del directorio
- Verifica que el módulo `mod_rewrite` esté habilitado: `sudo a2enmod rewrite`

### Error de Conexión a Base de Datos

- Verifica las credenciales en `.env`
- Asegúrate de que MySQL esté corriendo: `sudo service mysql status`
- Verifica que la base de datos `rolplay_edu` exista

### Archivos No Se Suben

- Verifica permisos de escritura: `chmod -R 775 public/uploads storage/`
- Verifica `upload_max_filesize` y `post_max_size` en `php.ini`

### Error "Column 'is_active' not found"

- Ejecuta la migración: `mysql -u root -p rolplay_edu < database/migrations/add_is_active_to_scenarios.sql`

## 📊 Despliegue en Producción

### Checklist de Producción:

- [ ] Configurar `.env` con `APP_ENV=production` y `APP_DEBUG=false`
- [ ] Usar contraseñas seguras para DB y usuarios admin
- [ ] Configurar HTTPS con certificado SSL
- [ ] Configurar permisos correctos (775 para storage, 755 para public)
- [ ] Configurar backups automáticos de la base de datos
- [ ] Configurar firewall (UFW en Linux)
- [ ] Configurar límites de PHP (`memory_limit`, `upload_max_filesize`)
- [ ] Habilitar log de errores de Apache
- [ ] Configurar un dominio real con DNS
- [ ] Probar todas las funcionalidades críticas

### Optimización de Rendimiento:

```bash
# Habilitar OPcache en php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000

# Configurar cache de aplicación
php artisan cache:clear  # Si usas Laravel-style cache
```

## 📝 Licencia

Este proyecto fue desarrollado como parte de un proyecto de formación del SENA.

## 👨‍💻 Soporte y Contacto

Para reportar problemas o solicitar nuevas funcionalidades:

- **Email**: soporte@rolplayedu.com
- **Issues**: Crear issue en el repositorio

---

**RolPlay EDU © 2025 - SENA (Servicio Nacional de Aprendizaje)**

Plataforma desarrollada para la gamificación del aprendizaje de competencias transversales.
