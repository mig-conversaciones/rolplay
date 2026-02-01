<?php

declare(strict_types=1);

use App\Core\Router;

$scenarios = $scenarios ?? [];
$success = $success ?? null;
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-briefcase text-sena-green mr-2"></i>
                Gestión de Escenarios
            </h1>
            <p class="text-neu-text-light">
                Administra los escenarios disponibles en el sistema.
            </p>
        </div>
        <a href="<?= Router::url('/admin') ?>" class="neu-flat px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Dashboard
        </a>
    </div>
</div>

<!-- Estadísticas rápidas -->
<div class="grid md:grid-cols-4 gap-6 mb-8">
    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Escenarios Totales</p>
        <p class="text-3xl font-bold text-sena-green">
            <?= count($scenarios) ?>
        </p>
    </div>
    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Activos</p>
        <p class="text-3xl font-bold text-blue-600">
            <?= count(array_filter($scenarios, fn($s) => ($s['is_active'] ?? 1) == 1)) ?>
        </p>
    </div>
    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Generados con IA</p>
        <p class="text-3xl font-bold text-sena-violet">
            <?= count(array_filter($scenarios, fn($s) => !empty($s['is_ai_generated']))) ?>
        </p>
    </div>
    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Inactivos</p>
        <p class="text-3xl font-bold text-gray-600">
            <?= count(array_filter($scenarios, fn($s) => ($s['is_active'] ?? 1) == 0)) ?>
        </p>
    </div>
</div>

<!-- Filtros -->
<div class="neu-flat p-6 mb-8">
    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label for="filter-area" class="block text-sm font-semibold text-neu-text-main mb-2">
                Filtrar por Área
            </label>
            <select id="filter-area" class="neu-input w-full">
                <option value="">Todas las áreas</option>
                <option value="tecnologia">Tecnología</option>
                <option value="comercio">Comercio</option>
                <option value="salud">Salud</option>
                <option value="industrial">Industrial</option>
                <option value="agropecuario">Agropecuario</option>
                <option value="general">General</option>
            </select>
        </div>
        <div>
            <label for="filter-difficulty" class="block text-sm font-semibold text-neu-text-main mb-2">
                Nivel de Dificultad
            </label>
            <select id="filter-difficulty" class="neu-input w-full">
                <option value="">Todos los niveles</option>
                <option value="basico">Básico</option>
                <option value="intermedio">Intermedio</option>
                <option value="avanzado">Avanzado</option>
            </select>
        </div>
        <div>
            <label for="filter-status" class="block text-sm font-semibold text-neu-text-main mb-2">
                Estado
            </label>
            <select id="filter-status" class="neu-input w-full">
                <option value="">Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
    </div>
</div>

<!-- Tabla de escenarios -->
<div class="neu-flat overflow-hidden">
    <?php if (empty($scenarios)): ?>
        <div class="p-12 text-center">
            <h3 class="text-2xl font-bold text-neu-text-main mb-2">No hay escenarios</h3>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full" id="scenarios-table">
                <thead class="neu-convex">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold">ID</th>
                        <th class="text-left py-4 px-6 font-semibold">Título</th>
                        <th class="text-left py-4 px-6 font-semibold">Área</th>
                        <th class="text-left py-4 px-6 font-semibold">Dificultad</th>
                        <th class="text-center py-4 px-6 font-semibold">Origen</th>
                        <th class="text-center py-4 px-6 font-semibold">Estado</th>
                        <th class="text-center py-4 px-6 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scenarios as $scenario): ?>
                        <tr class="border-b border-neu-shadow-dark"
                            data-area="<?= htmlspecialchars($scenario['area'] ?? '') ?>"
                            data-difficulty="<?= htmlspecialchars($scenario['difficulty'] ?? '') ?>"
                            data-status="<?= ($scenario['is_active'] ?? 1) ? '1' : '0' ?>">
                            <td class="py-4 px-6 text-neu-text-main font-medium">
                                <?= (int)$scenario['id'] ?>
                            </td>
                            <td class="py-4 px-6">
                                <p class="text-neu-text-main font-medium">
                                    <?= htmlspecialchars($scenario['title']) ?>
                                </p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold">
                                    <?= htmlspecialchars($scenario['area'] ?? 'general') ?>
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold">
                                    <?= htmlspecialchars($scenario['difficulty'] ?? 'basico') ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php if (!empty($scenario['is_ai_generated'])): ?>
                                    <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold">
                                        IA
                                    </span>
                                <?php else: ?>
                                    <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold">
                                        Base
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <form method="POST"
                                      action="<?= Router::url('/admin/scenarios/' . $scenario['id'] . '/toggle') ?>"
                                      class="inline">
                                    <button type="submit"
                                            class="neu-btn-primary px-4 py-2 rounded-full text-sm">
                                        <?= ($scenario['is_active'] ?? 1) ? 'Activo' : 'Inactivo' ?>
                                    </button>
                                </form>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex gap-2 justify-center">
                                    <a href="<?= Router::url('/scenarios/' . $scenario['id']) ?>"
                                       class="neu-icon-btn"
                                       target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Información adicional -->
<div class="neu-flat p-6 mt-8">
    <h3 class="text-lg font-bold text-neu-text-main mb-2">
        Información sobre Gestión de Escenarios
    </h3>
    <ul class="text-neu-text-light space-y-2">
        <li>Los instructores pueden crear nuevos escenarios.</li>
        <li>Los escenarios inactivos no se muestran a los aprendices.</li>
        <li>Los escenarios generados con IA están marcados.</li>
        <li>Puedes activar o desactivar escenarios con el botón de estado.</li>
    </ul>
</div>

<script>
    // Filtrado en tiempo real
    function applyFilters() {
        const areaFilter = document.getElementById('filter-area').value.toLowerCase();
        const difficultyFilter = document.getElementById('filter-difficulty').value.toLowerCase();
        const statusFilter = document.getElementById('filter-status').value;
        const rows = document.querySelectorAll('#scenarios-table tbody tr');

        rows.forEach(row => {
            const area = row.getAttribute('data-area').toLowerCase();
            const difficulty = row.getAttribute('data-difficulty').toLowerCase();
            const status = row.getAttribute('data-status');

            const matchesArea = !areaFilter || area === areaFilter;
            const matchesDifficulty = !difficultyFilter || difficulty === difficultyFilter;
            const matchesStatus = !statusFilter || status === statusFilter;

            if (matchesArea && matchesDifficulty && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('filter-area')?.addEventListener('change', applyFilters);
    document.getElementById('filter-difficulty')?.addEventListener('change', applyFilters);
    document.getElementById('filter-status')?.addEventListener('change', applyFilters);
</script>
