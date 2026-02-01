<?php

use App\Core\Router;

$programs = $programs ?? [];
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-folder-open text-sena-green mr-2"></i>
                Programas de Formación
            </h1>
            <p class="text-neu-text-light">
                Registra competencias y perfil de egreso para analizar con IA y generar escenarios automáticamente
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= Router::url('/instructor') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Dashboard
            </a>
            <a href="<?= Router::url('/instructor/programs/create') ?>" class="neu-btn-primary px-4 py-2 rounded-full text-sm">
                <i class="fas fa-plus mr-1"></i> Cargar Programa
            </a>
        </div>
    </div>
</div>

<!-- Filtros y búsqueda -->
<?php if (!empty($programs)): ?>
<div class="neu-flat p-6 mb-8">
    <div class="flex flex-col md:flex-row gap-4 items-center">
        <!-- Búsqueda -->
        <div class="flex-1 w-full">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-neu-text-light"></i>
                </div>
                <input
                    type="text"
                    id="search-programs"
                    placeholder="Buscar programa por nombre o código..."
                    class="neu-input w-full pl-10"
                >
            </div>
        </div>

        <!-- Filtro por estado -->
        <select id="filter-status" class="neu-input">
            <option value="">Todos los estados</option>
            <option value="pending">Pendiente</option>
            <option value="analyzing">Analizando</option>
            <option value="completed">Completado</option>
            <option value="failed">Fallido</option>
        </select>

        <!-- Contador de resultados -->
        <div class="text-neu-text-light text-sm whitespace-nowrap">
            <span id="results-count"><?= count($programs) ?></span> programa(s)
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Grid de programas -->
<?php if (empty($programs)): ?>
    <!-- Empty state -->
    <div class="neu-flat p-12 text-center">
        <div class="text-6xl mb-4"><i class="fas fa-clipboard-list text-neu-text-light"></i></div>
        <h3 class="text-xl font-bold text-neu-text-main mb-2">Aún no hay programas</h3>
        <p class="text-neu-text-light mb-6">Crea tu primer programa con competencias y perfil de egreso para iniciar el análisis.</p>
        <a href="<?= Router::url('/instructor/programs/create') ?>" class="neu-btn-primary px-6 py-3 rounded-full inline-block">
            <i class="fas fa-plus mr-2"></i> Cargar Mi Primer Programa
        </a>
    </div>
<?php else: ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="programs-grid">
        <?php foreach ($programs as $index => $p): ?>
            <div
                class="program-card neu-flat transition-all hover:shadow-lg rounded-xl"
                data-status="<?= htmlspecialchars($p['status'] ?? 'pending') ?>"
                data-title="<?= strtolower(htmlspecialchars($p['title'] ?? '')) ?>"
                data-code="<?= strtolower(htmlspecialchars($p['codigo_programa'] ?? '')) ?>"
            >
                <!-- Card Header con gradiente -->
                <div class="bg-gradient-to-r from-sena-green to-sena-green-dark p-5 text-white relative overflow-hidden rounded-t-xl">
                    <!-- Patrón decorativo -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white transform translate-x-12 -translate-y-12"></div>
                    </div>

                    <div class="relative flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold mb-1 truncate">
                                <?= htmlspecialchars($p['title'] ?? 'Programa') ?>
                            </h3>
                            <?php if (!empty($p['codigo_programa'])): ?>
                                <p class="text-green-100 text-sm">
                                    <i class="fas fa-hashtag mr-1"></i><?= htmlspecialchars($p['codigo_programa']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="flex-shrink-0 ml-3">
                            <i class="fas fa-clipboard-list text-4xl opacity-30"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    <!-- Badge de estado -->
                    <?php
                    $statusConfig = [
                        'pending' => ['icon' => 'fa-clock', 'text' => 'Pendiente', 'class' => 'bg-yellow-100 text-yellow-800'],
                        'analyzing' => ['icon' => 'fa-spinner fa-spin', 'text' => 'Analizando', 'class' => 'bg-blue-100 text-blue-800'],
                        'completed' => ['icon' => 'fa-check-circle', 'text' => 'Completado', 'class' => 'bg-green-100 text-green-800'],
                        'failed' => ['icon' => 'fa-times-circle', 'text' => 'Fallido', 'class' => 'bg-red-100 text-red-800']
                    ];
                    $status = $p['status'] ?? 'pending';
                    $config = $statusConfig[$status] ?? $statusConfig['pending'];
                    ?>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold <?= $config['class'] ?>">
                        <i class="fas <?= $config['icon'] ?>"></i>
                        <?= $config['text'] ?>
                    </span>

                    <!-- Metadata -->
                    <div class="mt-4 space-y-2 text-sm text-neu-text-light">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-calendar w-4"></i>
                            <span>Cargado: <?= date('d/m/Y', strtotime($p['created_at'] ?? 'now')) ?></span>
                        </div>

                        <?php if (!empty($p['scenarios_count'])): ?>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-briefcase w-4 text-sena-green"></i>
                                <span>
                                    <strong class="text-neu-text-main"><?= (int)$p['scenarios_count'] ?></strong>
                                    escenario<?= ((int)$p['scenarios_count']) !== 1 ? 's' : '' ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div class="mt-5 flex gap-2">
                        <a
                            href="<?= Router::url('/instructor/programs/' . (int)($p['id'] ?? 0)) ?>"
                            class="flex-1 neu-btn-primary px-4 py-2 rounded-full text-center text-sm"
                        >
                            <i class="fas fa-eye mr-1"></i> Ver Detalles
                        </a>

                        <!-- Dropdown Menu -->
                        <div class="relative">
                            <button
                                type="button"
                                class="neu-flat px-3 py-2 rounded-full hover:bg-gray-100 transition-colors"
                                onclick="toggleDropdown('dropdown-<?= (int)($p['id'] ?? 0) ?>', event)"
                                title="Más opciones"
                            >
                                <i class="fas fa-ellipsis-v text-neu-text-light"></i>
                            </button>
                            <div id="dropdown-<?= (int)($p['id'] ?? 0) ?>" class="dropdown-menu absolute right-0 bottom-full mb-2 w-48 neu-flat rounded-lg shadow-xl" style="display: none; z-index: 1000;">
                                <?php if ($status === 'completed'): ?>
                                <a href="<?= Router::url('/instructor/programs/' . (int)($p['id'] ?? 0)) ?>" class="flex items-center gap-2 px-4 py-3 text-sm text-neu-text-main hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-sync w-4 text-blue-500"></i>
                                    Generar escenario
                                </a>
                                <?php endif; ?>
                                <button type="button" onclick="confirmDelete(<?= (int)($p['id'] ?? 0) ?>)" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-b-lg transition-colors">
                                    <i class="fas fa-trash w-4"></i>
                                    Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Estado cuando no hay resultados de búsqueda -->
    <div id="no-results" style="display: none;">
        <div class="neu-flat p-12 text-center">
            <div class="text-6xl mb-4"><i class="fas fa-search text-neu-text-light"></i></div>
            <h3 class="text-xl font-bold text-neu-text-main mb-2">No se encontraron programas</h3>
            <p class="text-neu-text-light mb-4">Intenta con otros términos de búsqueda o cambia los filtros</p>
            <button onclick="resetFilters()" class="neu-flat px-4 py-2 rounded-full text-sm">
                <i class="fas fa-redo mr-1"></i> Limpiar filtros
            </button>
        </div>
    </div>
<?php endif; ?>

<!-- Modal de confirmación de eliminación -->
<div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-[2000] flex items-center justify-center" style="display: none;">
    <div class="neu-flat p-6 rounded-xl max-w-md mx-4">
        <h3 class="text-lg font-bold text-neu-text-main mb-2">¿Eliminar programa?</h3>
        <p class="text-neu-text-light mb-6">Esta acción no se puede deshacer. Se eliminarán también los escenarios asociados.</p>
        <div class="flex gap-3 justify-end">
            <button onclick="closeDeleteModal()" class="neu-flat px-4 py-2 rounded-full text-sm">
                Cancelar
            </button>
            <form id="delete-form" method="post" action="" class="inline">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full text-sm transition-colors">
                    <i class="fas fa-trash mr-1"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Dropdown functionality
function toggleDropdown(dropdownId, event) {
    event.stopPropagation();
    event.preventDefault();

    var menu = document.getElementById(dropdownId);
    if (!menu) return;

    // Close all other dropdowns first
    var allDropdowns = document.querySelectorAll('.dropdown-menu');
    for (var i = 0; i < allDropdowns.length; i++) {
        if (allDropdowns[i].id !== dropdownId) {
            allDropdowns[i].style.display = 'none';
        }
    }

    // Toggle current menu
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    // Don't close if clicking inside a dropdown menu
    if (e.target.closest('.dropdown-menu')) return;

    var allDropdowns = document.querySelectorAll('.dropdown-menu');
    for (var i = 0; i < allDropdowns.length; i++) {
        allDropdowns[i].style.display = 'none';
    }
});

// Filter functionality
const searchInput = document.getElementById('search-programs');
const filterStatus = document.getElementById('filter-status');
const programCards = document.querySelectorAll('.program-card');
const resultsCount = document.getElementById('results-count');
const programsGrid = document.getElementById('programs-grid');
const noResults = document.getElementById('no-results');

function applyFilters() {
    const searchTerm = searchInput?.value.toLowerCase() || '';
    const statusFilter = filterStatus?.value || '';

    let visibleCount = 0;

    programCards.forEach(card => {
        const title = card.dataset.title || '';
        const code = card.dataset.code || '';
        const status = card.dataset.status || '';

        const matchesSearch = !searchTerm || title.includes(searchTerm) || code.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    if (resultsCount) resultsCount.textContent = visibleCount;

    if (visibleCount === 0 && programCards.length > 0) {
        if (programsGrid) programsGrid.style.display = 'none';
        if (noResults) noResults.style.display = 'block';
    } else {
        if (programsGrid) programsGrid.style.display = '';
        if (noResults) noResults.style.display = 'none';
    }
}

function resetFilters() {
    if (searchInput) searchInput.value = '';
    if (filterStatus) filterStatus.value = '';
    applyFilters();
}

searchInput?.addEventListener('input', applyFilters);
filterStatus?.addEventListener('change', applyFilters);

// Delete modal
function confirmDelete(programId) {
    // Close any open dropdowns to avoid overlaying the modal
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.style.display = 'none';
    });
    const modal = document.getElementById('delete-modal');
    const form = document.getElementById('delete-form');
    form.action = '<?= Router::url('/instructor/programs/') ?>' + programId + '/delete';
    modal.style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
}

// Close modal on backdrop click
document.getElementById('delete-modal')?.addEventListener('click', (e) => {
    if (e.target === e.currentTarget) closeDeleteModal();
});

// Auto-refresh if analyzing
const hasAnalyzing = Array.from(programCards).some(card => card.dataset.status === 'analyzing');
if (hasAnalyzing) {
    setTimeout(() => location.reload(), 30000);
}
</script>
