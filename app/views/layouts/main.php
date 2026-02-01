<?php

declare(strict_types=1);

use App\Core\Router;
use App\Models\Notification;
use App\Models\SystemSetting;

$appName = $_ENV['APP_NAME'] ?? 'RolPlay EDU';
$appNameSetting = SystemSetting::get('app_name');
if (is_string($appNameSetting) && $appNameSetting !== '') {
    $appName = $appNameSetting;
}
$user = $_SESSION['user'] ?? null;
$pageTitle = $title ?? $appName;
$unreadNotifications = 0;

$normalizeEncoding = static function (string $text): string {
    $markers = ['Ã', 'Â', 'â', '�', '├', '│'];
    $hasMarkers = false;
    foreach ($markers as $marker) {
        if (strpos($text, $marker) !== false) {
            $hasMarkers = true;
            break;
        }
    }
    if (!$hasMarkers) {
        return $text;
    }

    $candidates = ['ISO-8859-1', 'Windows-1252', 'CP850', 'CP437'];
    $mbEncodings = function_exists('mb_list_encodings') ? array_map('strtoupper', mb_list_encodings()) : [];
    $best = $text;
    $bestScore = PHP_INT_MAX;

    foreach ($candidates as $from) {
        $converted = null;
        $useMb = function_exists('mb_convert_encoding') && !empty($mbEncodings);
        $mbAllowed = $useMb && in_array(strtoupper($from), $mbEncodings, true);
        if ($mbAllowed) {
            $converted = @mb_convert_encoding($text, 'UTF-8', $from);
        } else {
            $converted = @iconv($from, 'UTF-8//IGNORE', $text);
        }

        if (!is_string($converted) || $converted === '') {
            continue;
        }

        $score = 0;
        foreach ($markers as $marker) {
            $score += substr_count($converted, $marker);
        }

        if ($score < $bestScore) {
            $bestScore = $score;
            $best = $converted;
        }
    }

    return $best;
};

$appName = $normalizeEncoding($appName);
$pageTitle = $normalizeEncoding($pageTitle);
if ($user) {
    $notificationModel = new Notification();
    $unreadNotifications = $notificationModel->countUnreadByUser((int) ($user['id'] ?? 0));
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plataforma gamificada para desarrollo de competencias transversales - SENA">
    <title><?= htmlspecialchars($pageTitle) ?> | <?= htmlspecialchars($appName) ?></title>
    <link rel="icon" type="image/png" href="<?= Router::url('/assets/images/LogoRP3.png') ?>">

    <!-- Google Fonts: Roboto (Tipografía SENA oficial) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Outfit:wght@400;600&display=swap" rel="stylesheet">

    <!-- Font Awesome 6.4.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Tailwind CSS 3.x -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'sena-green': '#39A900',
                        'sena-green-dark': '#007832',
                        'sena-blue': '#00304D',
                        'sena-violet': '#71277A',
                        'sena-yellow': '#FDC300',
                    },
                    fontFamily: {
                        sans: ['Roboto', 'ui-sans-serif', 'system-ui'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <!-- RolPlay EDU - Estilos Externos -->
    <link rel="stylesheet" href="<?= Router::url('/assets/css/design-system.css') ?>">
    <link rel="stylesheet" href="<?= Router::url('/assets/css/components.css') ?>">
    <link rel="stylesheet" href="<?= Router::url('/assets/css/animations.css') ?>">
    <link rel="stylesheet" href="<?= Router::url('/assets/css/neumorphism.css') ?>">
    <link rel="stylesheet" href="<?= Router::url('/assets/css/modal.css') ?>">

    <style>
        /* Estilos específicos que complementan Tailwind */
        body {
            background-color: var(--bg-color);
        }
        .mobile-menu {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-menu.open {
            transform: translateX(0);
        }

        /* Flash messages - usar clases .alert del components.css */
        .flash-message {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 16px;
            border-left: 4px solid;
            animation: fadeInUp 0.5s ease-out;
        }

        .flash-success {
            background: #ecfdf5;
            border-color: var(--sena-green);
            color: #065f46;
        }

        .flash-error {
            background: #fef2f2;
            border-color: var(--color-danger);
            color: #991b1b;
        }

        .flash-info {
            background: #eff6ff;
            border-color: var(--sena-blue);
            color: #1e3a8a;
        }

        .flash-warning {
            background: #fffbeb;
            border-color: var(--sena-yellow);
            color: #92400e;
        }
    </style>
    
    <!-- Puter.js v2 -->
    <script src="https://js.puter.com/v2/"></script>
</head>
<body class="font-outfit">

<!-- Header mejorado -->
<header class="neu-nav sticky top-0 z-50">
    <nav class="container mx-auto px-6 py-3">
        <div class="flex justify-between items-center">
            <!-- Logo section -->
            <div class="flex items-center space-x-4">
                <a href="<?= Router::homeUrl() ?>" class="flex items-center space-x-3">
                    <img src="https://www.sena.edu.co/Style%20Library/alayout/images/logoSena.png" alt="Logo SENA" class="h-12">
                    <div class="border-l-2 border-gray-200 h-10 mx-1"></div>
                    <img src="<?= Router::url('/assets/images/LogoRP2.png') ?>" alt="Logo RolPlay EDU" class="h-10" onerror="this.style.display='none'">
                    <span class="text-xl md:text-2xl font-bold text-gray-800 hidden sm:inline"><?= htmlspecialchars($appName) ?></span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center space-x-6">
                <?php if ($user): ?>
                    <?php
                    // Dashboard según rol
                    $dashboardUrl = match ($user['role'] ?? 'aprendiz') {
                        'admin' => Router::url('/admin'),
                        'instructor' => Router::url('/instructor'),
                        default => Router::url('/scenarios'),
                    };
                    $dashboardIcon = match ($user['role'] ?? 'aprendiz') {
                        'admin' => 'fa-cogs',
                        'instructor' => 'fa-chalkboard-teacher',
                        default => 'fa-home',
                    };
                    ?>
                    <a href="<?= $dashboardUrl ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                        <i class="fas <?= $dashboardIcon ?> mr-1"></i> Dashboard
                    </a>
                <?php endif; ?>

                <a href="<?= Router::url('/scenarios') ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                    <i class="fas fa-briefcase mr-1"></i> Escenarios
                </a>

                <?php if ($user): ?>
                    <a href="<?= Router::url('/profile') ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                        <i class="fas fa-user mr-1"></i> Mi Perfil
                    </a>

                    <?php if (($user['role'] ?? '') === 'aprendiz'): ?>
                        <a href="<?= Router::url('/routes') ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                            <i class="fas fa-route mr-1"></i> Mis Rutas
                        </a>
                    <?php endif; ?>

                    <?php if (in_array($user['role'], ['instructor', 'admin'], true)): ?>
                        <a href="<?= Router::url('/instructor/programs') ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                            <i class="fas fa-folder-open mr-1"></i> Programas
                        </a>
                        <a href="<?= Router::url('/instructor/routes') ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                            <i class="fas fa-route mr-1"></i> Rutas
                        </a>
                    <?php endif; ?>

                    <div class="border-l-2 border-gray-200 h-6 mx-2"></div>

                    <span class="text-gray-700 text-sm">
                        <i class="fas fa-user-circle mr-1"></i>
                        <strong><?= htmlspecialchars($user['name']) ?></strong>
                        <span class="text-gray-500 ml-1">(<?= htmlspecialchars($user['role']) ?>)</span>
                    </span>
                    <a href="<?= Router::url('/notifications') ?>" class="relative text-gray-600 hover:text-sena-green transition duration-300" title="Notificaciones">
                        <i class="fas fa-bell"></i>
                        <?php if ($unreadNotifications > 0): ?>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full px-1.5 py-0.5">
                                <?= $unreadNotifications > 99 ? '99+' : (int) $unreadNotifications ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <form method="post" action="<?= Router::url('/logout') ?>" class="inline">
                        <button type="submit" class="text-gray-600 hover:text-red-600 font-medium transition duration-300">
                            <i class="fas fa-sign-out-alt mr-1"></i> Salir
                        </button>
                    </form>
                <?php else: ?>
                    <a href="<?= Router::url('/login') ?>" class="text-gray-600 hover:text-sena-green font-medium transition duration-300">
                        <i class="fas fa-sign-in-alt mr-1"></i> Entrar
                    </a>
                    <a href="<?= Router::url('/register') ?>"
                       class="neu-btn-primary px-6 py-2 rounded-full font-semibold transform hover:scale-[1.02] transition-all duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Registrarse
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile menu button -->
            <button id="mobile-menu-button" class="lg:hidden text-gray-600 hover:text-sena-green transition duration-300">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </nav>
</header>

<!-- Mobile Menu -->
<div id="mobile-menu" class="mobile-menu fixed top-0 right-0 h-full w-64 bg-white shadow-2xl z-50 lg:hidden">
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <span class="font-bold text-xl text-gray-800">Menú</span>
            <button id="close-mobile-menu" class="text-gray-600 hover:text-sena-green transition duration-300">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <nav class="space-y-4">
            <?php if ($user): ?>
                <?php
                // Dashboard según rol (mobile)
                $mobileDashboardUrl = match ($user['role'] ?? 'aprendiz') {
                    'admin' => Router::url('/admin'),
                    'instructor' => Router::url('/instructor'),
                    default => Router::url('/scenarios'),
                };
                $mobileDashboardIcon = match ($user['role'] ?? 'aprendiz') {
                    'admin' => 'fa-cogs',
                    'instructor' => 'fa-chalkboard-teacher',
                    default => 'fa-home',
                };
                ?>
                <a href="<?= $mobileDashboardUrl ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                    <i class="fas <?= $mobileDashboardIcon ?> mr-2"></i> Dashboard
                </a>
            <?php endif; ?>

            <a href="<?= Router::url('/scenarios') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                <i class="fas fa-briefcase mr-2"></i> Escenarios
            </a>

            <?php if ($user): ?>
                <a href="<?= Router::url('/profile') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                    <i class="fas fa-user mr-2"></i> Mi Perfil
                </a>
                <a href="<?= Router::url('/notifications') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                    <i class="fas fa-bell mr-2"></i> Notificaciones
                    <?php if ($unreadNotifications > 0): ?>
                        <span class="ml-2 inline-flex items-center bg-red-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                            <?= $unreadNotifications > 99 ? '99+' : (int) $unreadNotifications ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if (($user['role'] ?? '') === 'aprendiz'): ?>
                    <a href="<?= Router::url('/routes') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                        <i class="fas fa-route mr-2"></i> Mis Rutas
                    </a>
                <?php endif; ?>

                <?php if (in_array($user['role'], ['instructor', 'admin'], true)): ?>
                    <a href="<?= Router::url('/instructor/routes') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                        <i class="fas fa-route mr-2"></i> Rutas
                    </a>
                    <a href="<?= Router::url('/instructor/programs') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                        <i class="fas fa-folder-open mr-2"></i> Programas
                    </a>
                <?php endif; ?>

                <hr class="my-4">

                <div class="py-2 px-4 text-sm text-gray-600">
                    <i class="fas fa-user-circle mr-2"></i>
                    <strong><?= htmlspecialchars($user['name']) ?></strong><br>
                    <span class="text-gray-500"><?= htmlspecialchars($user['role']) ?></span>
                </div>

                <form method="post" action="<?= Router::url('/logout') ?>">
                    <button type="submit" class="w-full text-left py-2 px-4 text-red-600 hover:bg-red-50 rounded-lg transition duration-300">
                        <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                    </button>
                </form>
            <?php else: ?>
                <a href="<?= Router::url('/login') ?>" class="block py-2 px-4 text-gray-600 hover:bg-green-50 hover:text-sena-green rounded-lg transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i> Entrar
                </a>
                <a href="<?= Router::url('/register') ?>" class="block w-full text-center py-2 px-4 bg-sena-green text-white rounded-lg hover:bg-sena-green-dark transition duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Registrarse
                </a>
            <?php endif; ?>
        </nav>
    </div>
</div>

<!-- Overlay for mobile menu -->
<div id="mobile-menu-overlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

<!-- Main content -->
<main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-7xl">

    <!-- Flash messages mejorados -->
    <?php if (!empty($_SESSION['flash']) && is_array($_SESSION['flash'])): ?>
        <?php
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $flashType = (string) ($flash['type'] ?? 'info');
        $flashMsg = (string) ($flash['message'] ?? '');
        $flashMsg = $normalizeEncoding($flashMsg);

        $flashConfig = [
            'success' => ['class' => 'flash-success', 'icon' => 'fa-check-circle'],
            'error' => ['class' => 'flash-error', 'icon' => 'fa-times-circle'],
            'warning' => ['class' => 'flash-warning', 'icon' => 'fa-exclamation-triangle'],
            'info' => ['class' => 'flash-info', 'icon' => 'fa-info-circle'],
        ];

        $config = $flashConfig[$flashType] ?? $flashConfig['info'];
        ?>
        <?php if ($flashMsg !== ''): ?>
            <div class="flash-message <?= $config['class'] ?>">
                <i class="fas <?= $config['icon'] ?> text-xl"></i>
                <span><?= htmlspecialchars($flashMsg) ?></span>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Content area con animación -->
    <div class="animate-fade-in p-6 neu-flat">
        <?= $content ?>
    </div>

    <!-- Footer mejorado -->
    <footer class="mt-16 pt-8 border-t border-gray-200 text-center text-gray-600">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm">
                <strong class="text-sena-green">RolPlay EDU</strong> - Plataforma de Simulación Gamificada para Competencias Transversales
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-graduation-cap mr-1"></i> SENA - Centro Agropecuario de Buga
            </div>
        </div>
        <div class="mt-4 text-xs text-gray-500">
            © <?= date('Y') ?> RolPlay EDU. Desarrollado con propósito educativo.
        </div>
    </footer>
</main>

<!-- JavaScript para mobile menu -->
<script>
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeMobileMenu = document.getElementById('close-mobile-menu');
    const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');

    function openMobileMenu() {
        mobileMenu.classList.add('open');
        mobileMenuOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenuFn() {
        mobileMenu.classList.remove('open');
        mobileMenuOverlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', openMobileMenu);
    }

    if (closeMobileMenu) {
        closeMobileMenu.addEventListener('click', closeMobileMenuFn);
    }

    if (mobileMenuOverlay) {
        mobileMenuOverlay.addEventListener('click', closeMobileMenuFn);
    }

    // Cerrar menu mobile al hacer clic en un link
    const mobileMenuLinks = mobileMenu?.querySelectorAll('a');
    mobileMenuLinks?.forEach(link => {
        link.addEventListener('click', closeMobileMenuFn);
    });

    // Smooth scroll para anclas
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href !== '#' && document.querySelector(href)) {
                e.preventDefault();
                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
</script>

</body>
</html>
