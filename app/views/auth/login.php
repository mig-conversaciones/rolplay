<?php

declare(strict_types=1);

use App\Core\Router;

$errors = $errors ?? [];
$old = $old ?? [];
?>

<!-- Container con fondo gradiente -->
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="neu-flat p-8 sm:p-10 rounded-2xl">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img src="<?= Router::url('/assets/images/LogoRP3.png') ?>" alt="RolPlay EDU" class="h-20">
            </div>

            <!-- Título -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-neu-text-main mb-2">
                    ¡Bienvenido de nuevo! 👋
                </h2>
                <p class="text-neu-text-light">
                    Accede a tu plataforma de simulación
                </p>
            </div>

            <!-- Formulario -->
            <form method="post" action="<?= Router::url('/login') ?>" class="space-y-6">
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-neu-text-main mb-2">
                        <i class="fas fa-envelope text-sena-green mr-2"></i>
                        Correo Electrónico
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                        class="neu-input w-full"
                        placeholder="correo@sena.edu.co"
                        autocomplete="email"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-neu-text-main mb-2">
                        <i class="fas fa-lock text-sena-green mr-2"></i>
                        Contraseña
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="neu-input w-full pr-12"
                            placeholder="••••••••"
                            autocomplete="current-password"
                        >
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-neu-text-light hover:text-neu-text-main transition-colors focus:outline-none"
                            aria-label="Mostrar/ocultar contraseña"
                        >
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Recordarme y Olvidaste contraseña -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-gray-300 text-sena-green focus:ring-sena-green"
                        >
                        <label for="remember" class="ml-2 block text-sm text-neu-text-main">
                            Recordarme
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="text-sena-green hover:text-sena-green-dark font-medium transition-colors">
                            ¿Olvidaste tu contraseña? 🔑
                        </a>
                    </div>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    class="w-full neu-btn-primary py-3 text-lg font-semibold rounded-full"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Entrar a la plataforma 🚀
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 mb-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-neu-shadow-dark"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 text-neu-text-light" style="background-color: var(--bg-color);">¿Primera vez aquí?</span>
                    </div>
                </div>
            </div>

            <!-- Link a registro -->
            <div class="text-center">
                <a href="<?= Router::url('/register') ?>" class="w-full inline-block neu-btn-primary py-3 text-lg font-semibold rounded-full">
                    Regístrate aquí 📝
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para toggle password -->
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon = document.getElementById('password-icon');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
