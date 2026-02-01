<?php

declare(strict_types=1);

use App\Core\Router;

$user = $user ?? null;
$errors = $errors ?? [];
$old = $old ?? [];
?>

<?php if (!$user): ?>
    <div class="neu-flat p-12 text-center">
        <h2 class="text-2xl font-bold text-neu-text-main mb-2">Usuario no encontrado</h2>
        <p class="text-neu-text-light mb-6">El usuario que intentas editar no existe.</p>
        <a href="<?= Router::url('/admin/users') ?>" class="neu-btn-primary px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Volver a Usuarios
        </a>
    </div>
<?php else: ?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-user-edit text-sena-blue mr-2"></i>
                Editar Usuario
            </h1>
            <p class="text-neu-text-light">
                Modifica la información del usuario: <strong><?= htmlspecialchars($user['name']) ?></strong>
            </p>
        </div>
        <a href="<?= Router::url('/admin/users') ?>" class="neu-flat px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Volver a Usuarios
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="neu-flat p-8 max-w-3xl mx-auto">
    <form method="POST" action="<?= Router::url('/admin/users/' . $user['id']) ?>" class="space-y-6">
        <!-- Nombre completo -->
        <div>
            <label for="name" class="block text-sm font-semibold text-neu-text-main mb-2">
                Nombre Completo <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   id="name"
                   name="name"
                   value="<?= htmlspecialchars($old['name'] ?? $user['name']) ?>"
                   required
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
                   value="<?= htmlspecialchars($old['email'] ?? $user['email']) ?>"
                   required
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
                <option value="aprendiz" <?= ($old['role'] ?? $user['role']) === 'aprendiz' ? 'selected' : '' ?>>
                    Aprendiz
                </option>
                <option value="instructor" <?= ($old['role'] ?? $user['role']) === 'instructor' ? 'selected' : '' ?>>
                    Instructor
                </option>
                <option value="admin" <?= ($old['role'] ?? $user['role']) === 'admin' ? 'selected' : '' ?>>
                    Administrador
                </option>
            </select>
        </div>

        <!-- Cambiar contraseña (opcional) -->
        <div class="border-t border-neu-shadow-dark pt-6">
            <h3 class="text-lg font-bold text-neu-text-main mb-4">
                Cambiar Contraseña (Opcional)
            </h3>
            <!-- Nueva contraseña -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-semibold text-neu-text-main mb-2">
                    Nueva Contraseña
                </label>
                <input type="password"
                       id="password"
                       name="password"
                       placeholder="Mínimo 6 caracteres (opcional)"
                       class="neu-input w-full">
            </div>

            <!-- Confirmar nueva contraseña -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-neu-text-main mb-2">
                    Confirmar Nueva Contraseña
                </label>
                <input type="password"
                       id="password_confirmation"
                       name="password_confirmation"
                       placeholder="Repite la nueva contraseña"
                       class="neu-input w-full">
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-4 pt-4">
            <button type="submit"
                    class="flex-1 neu-btn-primary px-6 py-3 rounded-full font-bold">
                <i class="fas fa-save mr-2"></i>
                Guardar Cambios
            </button>
            <a href="<?= Router::url('/admin/users') ?>"
               class="flex-1 neu-flat px-6 py-3 rounded-full font-bold text-center">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Advertencia de seguridad -->
<div class="neu-flat p-6 mt-8 max-w-3xl mx-auto">
    <h3 class="text-lg font-bold text-neu-text-main mb-2">
        Advertencia de Seguridad
    </h3>
    <p class="text-neu-text-light">
        Ten cuidado al cambiar el rol de un usuario. Los administradores tienen acceso completo al sistema.
    </p>
</div>

<?php endif; ?>
