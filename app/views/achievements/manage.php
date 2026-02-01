<?php

declare(strict_types=1);

use App\Core\Router;

$achievements = $achievements ?? [];

$fixText = static function (?string $text): string {
    if ($text === null) {
        return '';
    }

    $value = $text;

    // Arreglo rÃ¡pido de mojibake (ej: "simulaciÃ³n").
    if (preg_match('/Ã|Â|â€“|â€”|â€œ|â€|â€™/u', $value)) {
        if (function_exists('mb_convert_encoding')) {
            $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
        } else {
            $converted = @iconv('ISO-8859-1', 'UTF-8//IGNORE', $value);
            if ($converted !== false) {
                $value = $converted;
            }
        }
    }

    return $value;
};

$requirementMeta = [
    'sessions_completed' => ['label' => 'Sesiones completadas', 'icon' => 'fa-check-circle'],
    'total_sessions' => ['label' => 'Sesiones totales', 'icon' => 'fa-list-check'],
    'avg_score' => ['label' => 'Promedio de puntuación', 'icon' => 'fa-chart-line'],
    'best_score' => ['label' => 'Mejor puntaje', 'icon' => 'fa-star'],
    'total_points' => ['label' => 'Puntos totales', 'icon' => 'fa-coins'],
    'total_achievement_points' => ['label' => 'Puntos de logros', 'icon' => 'fa-coins'],
    'achievements_count' => ['label' => 'Logros desbloqueados', 'icon' => 'fa-trophy'],
    'achievements_unlocked' => ['label' => 'Logros desbloqueados', 'icon' => 'fa-trophy'],
    'competence_comunicacion' => ['label' => 'Competencia: Comunicación', 'icon' => 'fa-comments'],
    'competence_liderazgo' => ['label' => 'Competencia: Liderazgo', 'icon' => 'fa-people-group'],
    'competence_trabajo_equipo' => ['label' => 'Competencia: Trabajo en Equipo', 'icon' => 'fa-people-carry-box'],
    'competence_toma_decisiones' => ['label' => 'Competencia: Toma de Decisiones', 'icon' => 'fa-sitemap'],
    'all_competences' => ['label' => 'Todas las competencias', 'icon' => 'fa-layer-group'],
    'streak' => ['label' => 'Racha consecutiva', 'icon' => 'fa-fire'],
    'areas_explored' => ['label' => 'Áreas exploradas', 'icon' => 'fa-compass'],
    'area_tecnologia' => ['label' => 'Área: Tecnología', 'icon' => 'fa-microchip'],
    'area_comercio' => ['label' => 'Área: Comercio', 'icon' => 'fa-store'],
    'area_salud' => ['label' => 'Área: Salud', 'icon' => 'fa-heartbeat'],
    'area_industrial' => ['label' => 'Área: Industrial', 'icon' => 'fa-industry'],
    'area_agropecuario' => ['label' => 'Área: Agropecuario', 'icon' => 'fa-seedling'],
    'difficulty_basico' => ['label' => 'Dificultad: Básico', 'icon' => 'fa-signal'],
    'difficulty_intermedio' => ['label' => 'Dificultad: Intermedio', 'icon' => 'fa-signal'],
    'difficulty_avanzado' => ['label' => 'Dificultad: Avanzado', 'icon' => 'fa-signal'],
    'completion_time' => ['label' => 'Tiempo de finalización', 'icon' => 'fa-stopwatch'],
    'early_bird' => ['label' => 'Madrugador', 'icon' => 'fa-sun'],
    'night_owl' => ['label' => 'Noctámbulo', 'icon' => 'fa-moon'],
    'profile_complete' => ['label' => 'Perfil completo', 'icon' => 'fa-id-card'],
    'first_login' => ['label' => 'Primer inicio de sesiÃ³n', 'icon' => 'fa-right-to-bracket'],
    'interactions_count' => ['label' => 'Interacciones', 'icon' => 'fa-comments'],
];
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-trophy text-sena-yellow mr-2"></i>
                Gestión de Logros
            </h1>
            <p class="text-neu-text-light">
                Administra los logros del sistema de gamificación.
            </p>
        </div>
        <div class="flex gap-3">
            <a href="<?= Router::url('/achievements/create') ?>" class="neu-btn-primary px-4 py-2 rounded-full">
                <i class="fas fa-plus"></i> Crear Logro
            </a>
            <a href="<?= Router::url('/admin') ?>" class="neu-flat px-4 py-2 rounded-full">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid md:grid-cols-4 gap-6 mb-8">
    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Total Logros</p>
        <p class="text-3xl font-bold text-sena-yellow">
            <?= count($achievements) ?>
        </p>
    </div>

    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Activos</p>
        <p class="text-3xl font-bold text-sena-green">
            <?= count(array_filter($achievements, fn($a) => ($a['is_active'] ?? 1) == 1)) ?>
        </p>
    </div>

    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Puntos Totales</p>
        <p class="text-3xl font-bold text-sena-violet">
            <?= number_format(array_sum(array_column($achievements, 'points'))) ?>
        </p>
    </div>

    <div class="neu-flat p-6">
        <p class="text-neu-text-light text-sm font-medium">Categorías</p>
        <p class="text-3xl font-bold text-sena-blue">
            <?= count(array_unique(array_column($achievements, 'category'))) ?>
        </p>
    </div>
</div>

<!-- Filtros -->
<div class="neu-flat p-6 mb-8">
    <div class="grid md:grid-cols-3 gap-4">
        <div>
            <label for="filter-category" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-filter mr-1"></i> Filtrar por Categoría
            </label>
            <select id="filter-category" class="neu-input w-full">
                <option value="">Todas las categorías</option>
                <option value="progreso">Progreso</option>
                <option value="excelencia">Excelencia</option>
                <option value="social">Social</option>
                <option value="especial">Especial</option>
                <option value="general">General</option>
            </select>
        </div>
        <div>
            <label for="filter-status" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-toggle-on mr-1"></i> Estado
            </label>
            <select id="filter-status" class="neu-input w-full">
                <option value="">Todos los estados</option>
                <option value="1">Activos</option>
                <option value="0">Inactivos</option>
            </select>
        </div>
        <div>
            <label for="search-title" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-search mr-1"></i> Buscar por Título
            </label>
            <input type="text" id="search-title" placeholder="Escribe un título..." class="neu-input w-full">
        </div>
    </div>
</div>

<!-- Tabla de logros -->
<div class="neu-flat overflow-hidden">
    <?php if (empty($achievements)): ?>
        <div class="p-12 text-center">
            <i class="fas fa-trophy text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-2xl font-bold text-neu-text-main mb-2">No hay logros</h3>
            <p class="text-neu-text-light mb-6">Aún no se han creado logros en el sistema.</p>
            <a href="<?= Router::url('/achievements/create') ?>" class="neu-btn-primary px-4 py-2 rounded-full">
                <i class="fas fa-plus"></i> Crear Primer Logro
            </a>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full" id="achievements-table">
                <thead class="neu-convex">
                    <tr>
                        <th class="text-left py-4 px-6 font-semibold">ID</th>
                        <th class="text-left py-4 px-6 font-semibold">Título</th>
                        <th class="text-left py-4 px-6 font-semibold">Categoría</th>
                        <th class="text-center py-4 px-6 font-semibold">Puntos</th>
                        <th class="text-left py-4 px-6 font-semibold">Requisito</th>
                        <th class="text-center py-4 px-6 font-semibold">Estado</th>
                        <th class="text-center py-4 px-6 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($achievements as $achievement): ?>
                        <?php
                        $isActive = ($achievement['is_active'] ?? 1) == 1;
                        $name = $fixText($achievement['name'] ?? '');
                        $description = $fixText($achievement['description'] ?? '');
                        $descriptionShort = function_exists('mb_substr')
                            ? mb_substr($description, 0, 60)
                            : substr($description, 0, 60);
                        $reqType = (string)($achievement['requirement_type'] ?? 'N/A');
                        $reqMeta = $requirementMeta[$reqType] ?? [
                            'label' => ucfirst(str_replace('_', ' ', $reqType)),
                            'icon' => 'fa-list-check',
                        ];
                        $reqLabel = $fixText($reqMeta['label'] ?? $reqType);
                        ?>
                        <tr class="border-b border-neu-shadow-dark"
                            data-category="<?= htmlspecialchars($achievement['category'] ?? '') ?>"
                            data-status="<?= $isActive ? '1' : '0' ?>"
                            data-title="<?= htmlspecialchars($name) ?>">
                            <td class="py-4 px-6 text-neu-text-main font-medium">
                                <?= (int)$achievement['id'] ?>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="neu-flat rounded-full w-10 h-10 flex items-center justify-center">
                                        <i class="fas <?= htmlspecialchars($achievement['icon'] ?? 'fa-trophy') ?> text-sena-yellow"></i>
                                    </div>
                                    <div>
                                        <p class="text-neu-text-main font-medium">
                                            <?= htmlspecialchars($name) ?>
                                        </p>
                                        <p class="text-neu-text-light text-sm">
                                            <?= htmlspecialchars($descriptionShort) ?><?= (strlen($description) > 60) ? '...' : '' ?>
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold capitalize">
                                    <?= htmlspecialchars($achievement['category'] ?? 'general') ?>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="neu-flat px-3 py-1 rounded-full text-sm font-bold text-sena-violet whitespace-nowrap inline-flex items-center justify-center"
                                      style="min-width: 72px;">
                                    <?= (int)$achievement['points'] ?> pts
                                </span>
                            </td>
                            <td class="py-4 px-6 text-neu-text-light text-sm">
                                <span class="inline-flex items-center gap-2 whitespace-nowrap">
                                    <span class="neu-flat w-8 h-8 rounded-full flex items-center justify-center text-neu-text-main">
                                        <i class="fas <?= htmlspecialchars($reqMeta['icon'] ?? 'fa-list-check') ?>"></i>
                                    </span>
                                    <span class="text-neu-text-main font-medium">
                                        <?= htmlspecialchars($reqLabel) ?>
                                    </span>
                                    <span class="text-neu-text-light font-semibold">
                                        <?= (int)($achievement['requirement_value'] ?? 0) ?>
                                    </span>
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <?php if ($isActive): ?>
                                    <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold text-sena-green inline-flex items-center gap-1 whitespace-nowrap">
                                        <i class="fas fa-check-circle mr-1"></i> Activo
                                    </span>
                                <?php else: ?>
                                    <span class="neu-flat px-3 py-1 rounded-full text-xs font-semibold text-neu-text-light inline-flex items-center gap-1 whitespace-nowrap">
                                        <i class="fas fa-ban mr-1"></i> Inactivo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex gap-2 justify-center">
                                    <a href="<?= Router::url('/achievements/' . $achievement['id'] . '/edit') ?>"
                                       class="neu-icon-btn"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= (int)$achievement['id'] ?>, '<?= htmlspecialchars($name, ENT_QUOTES) ?>')"
                                            class="neu-icon-btn"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Modal de confirmación de eliminación -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="neu-flat max-w-md w-full p-6">
        <div class="text-center mb-6">
            <h3 class="text-2xl font-bold text-neu-text-main mb-2">¿Eliminar Logro?</h3>
            <p class="text-neu-text-light">
                ¿Estás seguro de que deseas eliminar el logro <strong id="delete-achievement-title"></strong>?
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
        const categoryFilter = document.getElementById('filter-category').value.toLowerCase();
        const statusFilter = document.getElementById('filter-status').value;
        const titleFilter = document.getElementById('search-title').value.toLowerCase();
        const rows = document.querySelectorAll('#achievements-table tbody tr');

        rows.forEach(row => {
            const category = row.getAttribute('data-category').toLowerCase();
            const status = row.getAttribute('data-status');
            const title = row.getAttribute('data-title').toLowerCase();

            const matchesCategory = !categoryFilter || category === categoryFilter;
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesTitle = !titleFilter || title.includes(titleFilter);

            if (matchesCategory && matchesStatus && matchesTitle) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.getElementById('filter-category')?.addEventListener('change', applyFilters);
    document.getElementById('filter-status')?.addEventListener('change', applyFilters);
    document.getElementById('search-title')?.addEventListener('input', applyFilters);

    // Modal de eliminación
    function confirmDelete(achievementId, achievementTitle) {
        document.getElementById('delete-achievement-title').textContent = achievementTitle;
        document.getElementById('delete-form').action = '<?= Router::url('/achievements') ?>/' + achievementId + '/delete';
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
