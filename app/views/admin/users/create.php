<?php

declare(strict_types=1);

use App\Core\Router;

$errors = $errors ?? [];
$old = $old ?? [];
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-user-plus text-sena-green mr-2"></i>
                Crear Nuevo Usuario
            </h1>
            <p class="text-neu-text-light">
                Completa el formulario para registrar un nuevo usuario en el sistema.
            </p>
        </div>
        <a href="<?= Router::url('/admin/users') ?>" class="neu-flat px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Volver a Usuarios
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="neu-flat p-8 max-w-3xl mx-auto">
    <form method="POST" action="<?= Router::url('/admin/users') ?>" class="space-y-6">
        <!-- Nombre completo -->
        <div>
            <label for="name" class="block text-sm font-semibold text-neu-text-main mb-2">
                Nombre Completo <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                   required
                   placeholder="Ej: Juan Pérez González"
                   class="neu-input w-full">
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-neu-text-main mb-2">
                Correo Electrónico <span class="text-red-500">*</span>
            </label>
            <input type="email"
                   id="email"
                   name="email"
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   required
                   placeholder="Ej: usuario@sena.edu.co"
                   class="neu-input w-full">
        </div>

        <!-- Rol -->
        <div>
            <label for="role" class="block text-sm font-semibold text-neu-text-main mb-2">
                Rol del Usuario <span class="text-red-500">*</span>
            </label>
            <select id="role"
                    name="role"
                    required
                    class="neu-input w-full">
                <option value="">Seleccione un rol...</option>
                <option value="aprendiz" <?= ($old['role'] ?? '') === 'aprendiz' ? 'selected' : '' ?>>
                    Aprendiz
                </option>
                <option value="instructor" <?= ($old['role'] ?? '') === 'instructor' ? 'selected' : '' ?>>
                    Instructor
                </option>
                <option value="admin" <?= ($old['role'] ?? '') === 'admin' ? 'selected' : '' ?>>
                    Administrador
                </option>
            </select>
        </div>

        <!-- Contraseña -->
        <div>
            <label for="password" class="block text-sm font-semibold text-neu-text-main mb-2">
                Contraseña <span class="text-red-500">*</span>
            </label>
            <input type="password"
                   id="password"
                   name="password"
                   required
                   placeholder="Mínimo 6 caracteres"
                   class="neu-input w-full">
        </div>

        <!-- Confirmar contraseña -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-neu-text-main mb-2">
                Confirmar Contraseña <span class="text-red-500">*</span>
            </label>
            <input type="password"
                   id="password_confirmation"
                   name="password_confirmation"
                   required
                   placeholder="Repita la contraseña"
                   class="neu-input w-full">
        </div>

        <!-- Botones -->
        <div class="flex gap-4 pt-4">
            <button type="submit"
                    class="flex-1 neu-btn-primary px-6 py-3 rounded-full font-bold">
                <i class="fas fa-save mr-2"></i>
                Crear Usuario
            </button>
            <a href="<?= Router::url('/admin/users') ?>"
               class="flex-1 neu-flat px-6 py-3 rounded-full font-bold text-center">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Info adicional -->
<div class="neu-flat p-6 mt-8 max-w-3xl mx-auto">
    <h3 class="text-lg font-bold text-neu-text-main mb-2">
        Consejos para Crear Usuarios
    </h3>
    <ul class="text-neu-text-light space-y-2">
        <li>Usa correos institucionales (@sena.edu.co) para mantener la trazabilidad.</li>
        <li>Asigna el rol apropiado según las funciones que realizará el usuario.</li>
        <li>Las contraseñas deben tener al menos 6 caracteres para seguridad básica.</li>
        <li>Puedes editar la información del usuario más tarde desde la lista de usuarios.</li>
    </ul>
</div>
