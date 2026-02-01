<?php

declare(strict_types=1);

use App\Core\Router;

/** @var array|null $user */
?>

<!-- Hero Section -->
<section class="neu-convex text-neu-text-main rounded-2xl p-8 md:p-12 mb-8">
    <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Bienvenido a <?= htmlspecialchars($appName ?? 'RolPlay EDU') ?>
        </h1>
        <p class="text-lg md:text-xl mb-8 text-neu-text-light">
            Desarrolla tus <strong>competencias transversales</strong> mediante simulaciones de escenarios laborales realistas con inteligencia artificial.
        </p>

        <?php if (!$user): ?>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="<?= Router::url('/register') ?>" class="neu-btn-primary px-8 py-3 rounded-full font-bold inline-flex items-center gap-2">
                    <i class="fas fa-user-plus"></i> Crear Cuenta
                </a>
                <a href="<?= Router::url('/login') ?>" class="neu-flat px-8 py-3 rounded-full font-bold inline-flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Ya Tengo Cuenta
                </a>
            </div>
        <?php else: ?>
            <a href="<?= Router::url('/scenarios') ?>" class="neu-btn-primary px-8 py-4 rounded-full font-bold inline-flex items-center gap-2 text-lg">
                <i class="fas fa-play-circle"></i> Comenzar Simulación
            </a>
        <?php endif; ?>
    </div>
</section>

<?php if ($user): ?>
    <!-- Estado actual del usuario -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="neu-flat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neu-text-light text-sm font-medium mb-1">Estado</p>
                    <p class="text-2xl font-bold text-sena-green">
                        <i class="fas fa-check-circle mr-2"></i>Activo
                    </p>
                </div>
                <div class="neu-icon-btn">
                    <i class="fas fa-user-check text-sena-green text-2xl"></i>
                </div>
            </div>
            <p class="text-neu-text-light text-sm mt-3">
                Sesión iniciada como <strong><?= htmlspecialchars($user['role']) ?></strong>
            </p>
        </div>

        <div class="neu-flat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neu-text-light text-sm font-medium mb-1">Nombre</p>
                    <p class="text-xl font-bold text-neu-text-main">
                        <?= htmlspecialchars($user['name']) ?>
                    </p>
                </div>
                <div class="neu-icon-btn">
                    <i class="fas fa-id-card text-sena-blue text-2xl"></i>
                </div>
            </div>
            <p class="text-neu-text-light text-sm mt-3">
                Email: <?= htmlspecialchars($user['email']) ?>
            </p>
        </div>

        <div class="neu-flat p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neu-text-light text-sm font-medium mb-1">Acceso</p>
                    <p class="text-lg font-bold text-neu-text-main">
                        Dashboard Completo
                    </p>
                </div>
                <div class="neu-icon-btn">
                    <i class="fas fa-shield-alt text-sena-violet text-2xl"></i>
                </div>
            </div>
            <p class="text-neu-text-light text-sm mt-3">
                <i class="fas fa-star mr-1 text-sena-yellow"></i>Todos los módulos habilitados
            </p>
        </div>
    </div>
<?php endif; ?>

<!-- Características principales -->
<section class="mb-12">
    <h2 class="text-3xl font-bold text-center text-neu-text-main mb-10">
        ¿Cómo Funciona RolPlay EDU?
    </h2>

    <div class="grid md:grid-cols-3 gap-8">
        <!-- Feature 1 -->
        <div class="neu-card neu-flat p-8 text-center">
            <div class="neu-icon-btn mx-auto mb-4">
                <i class="fas fa-tasks text-sena-green text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-neu-text-main mb-3">1. Elige un Reto</h3>
            <p class="text-neu-text-light">
                Selecciona escenarios laborales diseñados específicamente para tu área de formación SENA.
            </p>
        </div>

        <!-- Feature 2 -->
        <div class="neu-card neu-flat p-8 text-center">
            <div class="neu-icon-btn mx-auto mb-4">
                <i class="fas fa-comments text-sena-blue text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-neu-text-main mb-3">2. Interactúa y Decide</h3>
            <p class="text-neu-text-light">
                Toma decisiones críticas en situaciones realistas. Cada opción impacta en tu puntuación.
            </p>
        </div>

        <!-- Feature 3 -->
        <div class="neu-card neu-flat p-8 text-center">
            <div class="neu-icon-btn mx-auto mb-4">
                <i class="fas fa-chart-line text-sena-violet text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-neu-text-main mb-3">3. Recibe Feedback</h3>
            <p class="text-neu-text-light">
                Análisis detallado de tu desempeño en 4 competencias clave con sugerencias de mejora.
            </p>
        </div>
    </div>
</section>

<!-- Competencias medidas -->
<section class="neu-flat p-8 md:p-12 mb-12">
    <h2 class="text-3xl font-bold text-center text-neu-text-main mb-10">
        Competencias que Desarrollarás
    </h2>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="text-center p-6 neu-flat">
            <div class="text-5xl mb-3">💬</div>
            <h3 class="font-bold text-lg text-neu-text-main mb-2">Comunicación</h3>
            <p class="text-neu-text-light text-sm">Expresión clara y efectiva en contextos laborales</p>
        </div>

        <div class="text-center p-6 neu-flat">
            <div class="text-5xl mb-3">👥</div>
            <h3 class="font-bold text-lg text-neu-text-main mb-2">Liderazgo</h3>
            <p class="text-neu-text-light text-sm">Capacidad de guiar equipos y tomar iniciativa</p>
        </div>

        <div class="text-center p-6 neu-flat">
            <div class="text-5xl mb-3">🤝</div>
            <h3 class="font-bold text-lg text-neu-text-main mb-2">Trabajo en Equipo</h3>
            <p class="text-neu-text-light text-sm">Colaboración efectiva y empatía con compañeros</p>
        </div>

        <div class="text-center p-6 neu-flat">
            <div class="text-5xl mb-3">🎯</div>
            <h3 class="font-bold text-lg text-neu-text-main mb-2">Toma de Decisiones</h3>
            <p class="text-neu-text-light text-sm">Criterio y pensamiento crítico bajo presión</p>
        </div>
    </div>
</section>

<!-- CTA final -->
<section class="neu-convex text-neu-text-main rounded-2xl p-8 md:p-12 text-center">
    <h2 class="text-3xl md:text-4xl font-bold mb-4">
        ¿Listo para Comenzar?
    </h2>
    <p class="text-lg md:text-xl mb-8 text-neu-text-light">
        Explora nuestros escenarios laborales y pon a prueba tus habilidades blandas.
    </p>
    <a href="<?= Router::url('/scenarios') ?>" class="neu-btn-primary px-8 py-4 rounded-full font-bold inline-flex items-center gap-3 text-lg">
        <i class="fas fa-rocket"></i> Ver Escenarios Disponibles
    </a>
</section>
