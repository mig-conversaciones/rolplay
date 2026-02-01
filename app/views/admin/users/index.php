<?php

declare(strict_types=1);

use App\Core\Router;

$users = $users ?? [];
$success = $success ?? null;
$error = $error ?? null;
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-users text-sena-blue mr-2"></i>
                Gestión de Usuarios
            </h1>
            <p class="text-neu-text-light">
                Administra todos los usuarios del sistema.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?= Router::url('/admin/users/create') ?>" class="neu-btn-primary px-4 py-2 rounded-full">
                <i class="fas fa-user-plus"></i> Crear Usuario
            </a>
            <a href="<?= Router::url('/admin') ?>" class="neu-flat px-4 py-2 rounded-full">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<div class="neu-flat p-6 mb-8">
    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label for="filter-role" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-filter mr-1"></i> Filtrar por Rol
            </label>
            <select id="filter-role" class="neu-input w-full">
                <option value="">Todos los roles</option>
                <option value="admin">Administrador</option>
                <option value="instructor">Instructor</option>
                <option value="aprendiz">Aprendiz</option>
            </select>
        </div>
        <div>
            <label for="search-name" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-search mr-1"></i> Buscar por Nombre
            </label>
            <input type="text" id="search-name" placeholder="Escriba un nombre..." class="neu-input w-full">
        </div>
        <div>
            <label for="search-email" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-envelope mr-1"></i> Buscar por Email
            </label>
            <input type="text" id="search-email" placeholder="Escriba un email..." class="neu-input w-full">
        </div>
    </div>
</div>

<!-- Tabla de usuarios -->
<div class="neu-flat overflow-hidden">
    <div class="overflow-x-auto">
        <?php if (empty($users)): ?>
            <div class="p-12 text-center">
                <h3 class="text-2xl font-bold text-neu-text-main mb-2">No hay usuarios</h3>
                <a href="<?= Router::url('/admin/users/create') ?>" class="neu-btn-primary px-4 py-2 rounded-full mt-4">
                    <i class="fas fa-user-plus"></i> Crear Primer Usuario
                </a>
            </div>
        <?php else: ?>
            <table class="w-full" id="users-table">
                <thead class="neu-convex">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold">ID</th>
                        <th class="text-left py-4 px-6 font-semibold">Nombre</th>
                        <th class="text-left py-4 px-6 font-semibold">Email</th>
                        <th class="text-left py-4 px-6 font-semibold">Rol</th>
                        <th class="text-left py-4 px-6 font-semibold">Registro</th>
                        <th class="text-center py-4 px-6 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-b border-neu-shadow-dark"
                            data-role="<?= htmlspecialchars($user['role']) ?>"
                            data-name="<?= htmlspecialchars($user['name']) ?>"
                            data-email="<?= htmlspecialchars($user['email']) ?>">
                            <td class="py-4 px-6 text-neu-text-main font-medium"><?= (int)$user['id'] ?></td>
                            <td class="py-4 px-6">
                                <span class="text-neu-text-main font-medium">
                                    <?= htmlspecialchars($user['name']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-neu-text-light">
                                <?= htmlspecialchars($user['email']) ?>
                            </td>
                            <td class="py-4 px-6">
                                <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold">
                                    <?= htmlspecialchars($user['role']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-neu-text-light text-sm">
                                <?= date('d/m/Y', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex gap-2 justify-center">
                                    <a href="<?= Router::url('/admin/users/' . $user['id'] . '/edit') ?>"
                                       class="neu-icon-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= (int)$user['id'] ?>, '<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>')"
                                            class="neu-icon-btn">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="neu-flat max-w-md w-full p-6">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-neu-text-main mb-2">¿Eliminar Usuario?</h3>
            <p class="text-neu-text-light">
                ¿Estás seguro de que deseas eliminar al usuario <strong id="delete-user-name"></strong>?
                Esta acción no se puede deshacer.
            </p>
        </div>
        <form id="delete-form" method="POST" action="">
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 neu-flat px-4 py-3 rounded-full font-semibold">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 neu-btn-primary px-4 py-3 rounded-full font-semibold">
                    <i class="fas fa-trash mr-2"></i>Eliminar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Filtrado en tiempo real
    function applyFilters() {
        const roleFilter = document.getElementById('filter-role').value.toLowerCase();
        const nameFilter = document.getElementById('search-name').value.toLowerCase();
        const emailFilter = document.getElementById('search-email').value.toLowerCase();
        const rows = document.querySelectorAll('#users-table tbody tr');

        rows.forEach(row => {
            const role = row.getAttribute('data-role').toLowerCase();
            const name = row.getAttribute('data-name').toLowerCase();
            const email = row.getAttribute('data-email').toLowerCase();

            const matchesRole = !roleFilter || role === roleFilter;
            const matchesName = !nameFilter || name.includes(nameFilter);
            const matchesEmail = !emailFilter || email.includes(emailFilter);

            if (matchesRole && matchesName && matchesEmail) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('filter-role')?.addEventListener('change', applyFilters);
    document.getElementById('search-name')?.addEventListener('input', applyFilters);
    document.getElementById('search-email')?.addEventListener('input', applyFilters);

    // Modal de eliminación
    function confirmDelete(userId, userName) {
        document.getElementById('delete-user-name').textContent = userName;
        document.getElementById('delete-form').action = '<?= Router::url('/admin/users') ?>/' + userId + '/delete';
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }

    // Cerrar modal con ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
