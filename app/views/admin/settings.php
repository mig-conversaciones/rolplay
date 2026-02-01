<?php

declare(strict_types=1);

use App\Core\Router;

$settings = $settings ?? [];
$status = $status ?? [];
$appName = $settings['app_name'] ?? ($_ENV['APP_NAME'] ?? 'RolPlay EDU');
$maintenanceMode = ($settings['maintenance_mode'] ?? '0') === '1';
$achievementsEnabled = ($settings['gamification_achievements_enabled'] ?? '1') === '1';
$rankingEnabled = ($settings['gamification_ranking_enabled'] ?? '1') === '1';
$notificationsEnabled = ($settings['gamification_notifications_enabled'] ?? '1') === '1';
$puterEnabled = ($settings['puter_enabled'] ?? '1') === '1';
$puterAutoLogin = ($settings['puter_auto_login'] ?? '0') === '1';
$puterLoginHint = (string) ($settings['puter_login_hint'] ?? '');
$puterPromptMode = (string) ($settings['puter_prompt_mode'] ?? 'login');
$puterPasswordSet = (string) ($settings['puter_password'] ?? '') !== '';
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-cog text-sena-violet mr-2"></i>
                Configuración del Sistema
            </h1>
            <p class="text-neu-text-light">
                Administra la configuración general de RolPlay EDU.
            </p>
        </div>
        <a href="<?= Router::url('/admin') ?>" class="neu-flat px-6 py-2 rounded-full inline-flex items-center gap-2 hover:shadow-lg transition-all duration-200">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>
</div>

<!-- Secciones de configuración -->
<form method="POST" action="<?= Router::url('/admin/settings') ?>" class="space-y-6">
<div class="grid md:grid-cols-2 gap-6">
    <!-- Configuración General -->
    <div class="neu-flat p-6">
        <h2 class="text-xl font-bold text-neu-text-main mb-4">
            <i class="fas fa-sliders-h text-sena-blue mr-2"></i>
            Configuración General
        </h2>
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <label for="app_name" class="font-semibold text-neu-text-main block mb-2">Nombre de la Aplicación</label>
                <input type="text"
                       id="app_name"
                       name="app_name"
                       value="<?= htmlspecialchars($appName) ?>"
                       class="neu-input w-full"
                       maxlength="80">
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Versión del Sistema</p>
                    <p class="text-sm text-neu-text-light">v1.0.0</p>
                </div>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-check-circle mr-1"></i> Estable
                </span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Modo de Mantenimiento</p>
                    <p class="text-sm text-neu-text-light">
                        <?= $maintenanceMode ? 'Mantenimiento activado' : 'Sistema operativo normal' ?>
                    </p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="maintenance_mode" value="1" class="sr-only peer" <?= $maintenanceMode ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sena-green/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                </label>
            </div>
        </div>
    </div>

    <!-- Integraciones -->
    <div class="neu-flat p-6">
        <h2 class="text-xl font-bold text-neu-text-main mb-4">
            <i class="fas fa-plug text-sena-green mr-2"></i>
            Integraciones
        </h2>
        <div class="space-y-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <i class="fas fa-robot text-emerald-700"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-neu-text-main">Integracion con Puter</p>
                            <p class="text-sm text-neu-text-light">Gestiona el acceso de IA (Puter.js)</p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="puter_enabled" value="1" class="sr-only peer" <?= $puterEnabled ? 'checked' : '' ?>>
                        <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sena-green/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                    </label>
                </div>
                <div class="mt-4 grid gap-3">
                    <div>
                        <label for="puter_login_hint" class="block text-sm font-semibold text-neu-text-main mb-2">Usuario / correo Puter</label>
                        <input type="text"
                               id="puter_login_hint"
                               name="puter_login_hint"
                               value="<?= htmlspecialchars($puterLoginHint) ?>"
                               class="neu-input w-full"
                               placeholder="usuario@dominio.com">
                    </div>
                    <div>
                        <label for="puter_password" class="block text-sm font-semibold text-neu-text-main mb-2">Contrasena Puter</label>
                        <input type="password"
                               id="puter_password"
                               name="puter_password"
                               value=""
                               class="neu-input w-full"
                               placeholder="<?= $puterPasswordSet ? '********' : 'Ingresa la contrasena' ?>"
                               autocomplete="new-password">
                        <p class="text-xs text-neu-text-light mt-2">
                            Por seguridad no se muestra. Deja vacio para conservar la actual.
                        </p>
                    </div>
                    <div class="grid md:grid-cols-2 gap-3">
                        <div>
                            <label for="puter_prompt_mode" class="block text-sm font-semibold text-neu-text-main mb-2">Modo de acceso</label>
                            <select id="puter_prompt_mode" name="puter_prompt_mode" class="neu-input w-full">
                                <option value="login" <?= $puterPromptMode === 'login' ? 'selected' : '' ?>>Forzar login</option>
                                <option value="consent" <?= $puterPromptMode === 'consent' ? 'selected' : '' ?>>Consentimiento</option>
                                <option value="select_account" <?= $puterPromptMode === 'select_account' ? 'selected' : '' ?>>Seleccion de cuenta</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-white rounded-lg border border-gray-200">
                            <div>
                                <p class="font-semibold text-neu-text-main">Auto-login</p>
                                <p class="text-sm text-neu-text-light">Intentar conexion al cargar</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="puter_auto_login" value="1" class="sr-only peer" <?= $puterAutoLogin ? 'checked' : '' ?>>
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sena-green/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                            </label>
                        </div>
                    </div>
                    <p class="text-xs text-neu-text-light">
                        Nota: Puter puede solicitar login en ventana propia. El sistema usa este usuario como pista para el acceso.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-database text-sena-blue"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-neu-text-main">Base de Datos</p>
                        <p class="text-sm text-neu-text-light">MySQL 8.0+</p>
                    </div>
                </div>
                <?php $dbOk = !empty($status['db_connected']); ?>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $dbOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' ?>">
                    <i class="fas <?= $dbOk ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                    <?= $dbOk ? 'Conectado' : 'Sin conexión' ?>
                </span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                        <i class="fas fa-file-pdf text-red-600"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-neu-text-main">TCPDF</p>
                        <p class="text-sm text-neu-text-light">Generación de reportes PDF</p>
                    </div>
                </div>
                <?php $tcpdfOk = !empty($status['tcpdf_available']); ?>
                <span class="px-3 py-1 rounded-full text-xs font-semibold <?= $tcpdfOk ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-700' ?>">
                    <i class="fas <?= $tcpdfOk ? 'fa-check-circle' : 'fa-times-circle' ?> mr-1"></i>
                    <?= $tcpdfOk ? 'Disponible' : 'No disponible' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Seguridad -->
    <div class="neu-flat p-6">
        <h2 class="text-xl font-bold text-neu-text-main mb-4">
            <i class="fas fa-shield-alt text-sena-violet mr-2"></i>
            Seguridad
        </h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Hashing de Contraseñas</p>
                    <p class="text-sm text-neu-text-light">PASSWORD_BCRYPT</p>
                </div>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-lock mr-1"></i> Seguro
                </span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Sesiones PHP</p>
                    <p class="text-sm text-neu-text-light">Sistema de autenticación</p>
                </div>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-check-circle mr-1"></i> Activo
                </span>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">SQL Injection Protection</p>
                    <p class="text-sm text-neu-text-light">Prepared statements PDO</p>
                </div>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                    <i class="fas fa-shield-alt mr-1"></i> Protegido
                </span>
            </div>
        </div>
    </div>

    <!-- Gamificación -->
    <div class="neu-flat p-6">
        <h2 class="text-xl font-bold text-neu-text-main mb-4">
            <i class="fas fa-trophy text-yellow-600 mr-2"></i>
            Gamificación
        </h2>
        <div class="space-y-4">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Sistema de Logros</p>
                    <p class="text-sm text-neu-text-light">Desbloqueo automático</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="gamification_achievements_enabled" value="1" class="sr-only peer" <?= $achievementsEnabled ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sena-green/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                </label>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Ranking Global</p>
                    <p class="text-sm text-neu-text-light">Tabla de posiciones</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="gamification_ranking_enabled" value="1" class="sr-only peer" <?= $rankingEnabled ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sena-green/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                </label>
            </div>
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-semibold text-neu-text-main">Notificaciones de Logros</p>
                    <p class="text-sm text-neu-text-light">Avisos al desbloquear</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="gamification_notifications_enabled" value="1" class="sr-only peer" <?= $notificationsEnabled ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sena-green/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sena-green"></div>
                </label>
            </div>
        </div>
    </div>
</div>

<div class="flex justify-end">
    <button type="submit" class="neu-btn-primary px-6 py-3 rounded-full font-semibold">
        <i class="fas fa-save mr-2"></i> Guardar cambios
    </button>
</div>
</form>

<!-- Información del servidor -->
<div class="neu-flat p-6 mt-6">
    <h2 class="text-xl font-bold text-neu-text-main mb-4">
        <i class="fas fa-server text-sena-blue mr-2"></i>
        Información del Servidor
    </h2>
    <div class="grid md:grid-cols-3 gap-4">
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-neu-text-light mb-1">Versión PHP</p>
            <p class="text-lg font-bold text-neu-text-main"><?= PHP_VERSION ?></p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-neu-text-light mb-1">Servidor Web</p>
            <p class="text-lg font-bold text-neu-text-main"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></p>
        </div>
        <div class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-neu-text-light mb-1">Sistema Operativo</p>
            <p class="text-lg font-bold text-neu-text-main"><?= PHP_OS ?></p>
        </div>
    </div>
</div>

<!-- Acciones del sistema -->
<div class="neu-flat p-6 mt-6">
    <h2 class="text-xl font-bold text-neu-text-main mb-6">
        <i class="fas fa-tools text-sena-violet mr-2"></i>
        Acciones del Sistema
    </h2>
    <form method="POST" action="<?= Router::url('/admin/settings/action') ?>">
    <div class="grid md:grid-cols-4 gap-4">
        <button type="submit" name="action" value="clear_cache" class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-sena-blue">
            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-blue-100 flex items-center justify-center transition-all duration-300 group-hover:bg-sena-blue">
                <i class="fas fa-sync text-xl text-sena-blue group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Limpiar Caché</p>
        </button>
        <button type="submit" name="action" value="backup_db" class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-sena-green">
            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-green-100 flex items-center justify-center transition-all duration-300 group-hover:bg-sena-green">
                <i class="fas fa-database text-xl text-sena-green group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Backup DB</p>
        </button>
        <button type="submit" name="action" value="export_data" class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-sena-violet">
            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-purple-100 flex items-center justify-center transition-all duration-300 group-hover:bg-sena-violet">
                <i class="fas fa-file-export text-xl text-sena-violet group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Exportar Datos</p>
        </button>
        <a href="<?= Router::url('/admin/logs') ?>" class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-yellow-500">
            <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-yellow-100 flex items-center justify-center transition-all duration-300 group-hover:bg-yellow-500">
                <i class="fas fa-chart-bar text-xl text-yellow-600 group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Ver Logs</p>
        </a>
    </div>
    </form>
</div>

<!-- Nota informativa -->
<div class="neu-flat p-6 mt-6 border-l-4 border-yellow-500">
    <h3 class="text-lg font-bold text-neu-text-main mb-2">
        <i class="fas fa-info-circle text-yellow-500 mr-2"></i>
        Nota Importante
    </h3>
    <p class="text-neu-text-light">
        Esta sección de configuración está en desarrollo. Algunas opciones mostradas son interfaces preliminares
        y serán implementadas completamente en futuras actualizaciones del sistema.
    </p>
</div>
