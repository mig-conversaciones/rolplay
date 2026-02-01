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
                    ¡Únete a RolPlay EDU! 🎓
                </h2>
                <p class="text-neu-text-light">
                    Crea tu cuenta y comienza tu aventura de aprendizaje
                </p>
            </div>

            <!-- Formulario -->
            <form method="post" action="<?= Router::url('/register') ?>" class="space-y-5">
                <input type="hidden" name="_token" value="<?= $csrf_token ?>">
                <!-- Nombre -->
                <div>
                    <label for="name" class="block text-sm font-medium text-neu-text-main mb-2">
                        <i class="fas fa-user text-sena-green mr-2"></i>
                        Nombre Completo
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        required
                        value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                        class="neu-input w-full"
                        placeholder="Tu nombre completo"
                        autocomplete="name"
                    >
                </div>

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

                <!-- Rol -->
                <div>
                    <label for="role" class="block text-sm font-medium text-neu-text-main mb-2">
                        <i class="fas fa-id-badge text-sena-green mr-2"></i>
                        Rol en la Plataforma
                    </label>
                    <select
                        id="role"
                        name="role"
                        required
                        class="neu-input w-full"
                    >
                        <?php $selectedRole = $old['role'] ?? 'aprendiz'; ?>
                        <option value="aprendiz" <?= $selectedRole === 'aprendiz' ? 'selected' : '' ?>>
                            👨‍🎓 Aprendiz - Completa escenarios y gana logros
                        </option>
                        <option value="instructor" <?= $selectedRole === 'instructor' ? 'selected' : '' ?>>
                            👨‍🏫 Instructor - Gestiona programas y evalúa
                        </option>
                    </select>
                </div>

                <!-- Ficha (opcional) -->
                <div id="ficha-container">
                    <label for="ficha" class="block text-sm font-medium text-neu-text-main mb-2">
                        <i class="fas fa-graduation-cap text-sena-green mr-2"></i>
                        Número de Ficha
                        <span class="text-neu-text-light text-xs">(opcional para aprendices)</span>
                    </label>
                    <input
                        type="text"
                        id="ficha"
                        name="ficha"
                        value="<?= htmlspecialchars($old['ficha'] ?? '') ?>"
                        class="neu-input w-full"
                        placeholder="Ej: 2742345"
                        autocomplete="off"
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
                            placeholder="Mínimo 8 caracteres"
                            autocomplete="new-password"
                            minlength="8"
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

                <!-- Submit button -->
                <button
                    type="submit"
                    class="w-full neu-btn-primary py-3 text-lg font-semibold rounded-full"
                >
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Mi Cuenta 🚀
                </button>
            </form>

            <!-- Divider -->
            <div class="mt-6 mb-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-neu-shadow-dark"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 text-neu-text-light" style="background-color: var(--bg-color);">¿Ya tienes cuenta?</span>
                    </div>
                </div>
            </div>

            <!-- Link a login -->
            <div class="text-center">
                <a href="<?= Router::url('/login') ?>" class="w-full inline-block neu-btn-primary py-3 text-lg font-semibold rounded-full">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    Inicia sesión aquí 🔑
                </a>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para toggle password y ficha -->
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

const roleSelect = document.getElementById('role');
const fichaContainer = document.getElementById('ficha-container');

if (roleSelect && fichaContainer) {
    roleSelect.addEventListener('change', function() {
        fichaContainer.style.display = this.value === 'aprendiz' ? 'block' : 'none';
    });
    fichaContainer.style.display = roleSelect.value === 'aprendiz' ? 'block' : 'none';
}
</script>
