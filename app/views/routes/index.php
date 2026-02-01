<?php

use App\Core\Router;

$routes = $routes ?? [];
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-route text-sena-green mr-2"></i>
                Rutas de Aprendizaje
            </h1>
            <p class="text-neu-text-light">
                Secuencias de escenarios para asignar a grupos/fichas
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= Router::url('/instructor') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
                <i class="fas fa-arrow-left mr-1"></i> Dashboard
            </a>
            <a href="<?= Router::url('/instructor/routes/create') ?>" class="neu-btn-primary px-4 py-2 rounded-full text-sm">
                <i class="fas fa-plus mr-1"></i> Nueva Ruta
            </a>
        </div>
    </div>
</div>

<!-- Grid de rutas -->
<?php if (empty($routes)): ?>
    <!-- Empty state -->
    <div class="neu-flat p-12 text-center">
        <div class="text-6xl mb-4"><i class="fas fa-route text-neu-text-light"></i></div>
        <h3 class="text-xl font-bold text-neu-text-main mb-2">Sin rutas creadas</h3>
        <p class="text-neu-text-light mb-6">Aún no has creado rutas. Crea la primera para asignar escenarios a tus aprendices.</p>
        <a href="<?= Router::url('/instructor/routes/create') ?>" class="neu-btn-primary px-6 py-3 rounded-full inline-block">
            <i class="fas fa-plus mr-2"></i> Crear Mi Primera Ruta
        </a>
    </div>
<?php else: ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="routes-grid">
        <?php foreach ($routes as $r): ?>
            <?php
            $scenarioCount = is_array($r['scenario_ids'] ?? null) ? count($r['scenario_ids']) : 0;
            $groups = $r['groups'] ?? [];
            $isActive = !empty($r['active']);
            ?>
            <div class="neu-flat transition-all hover:shadow-lg rounded-xl">
                <!-- Card Header con gradiente -->
                <div class="bg-gradient-to-r from-sena-green to-sena-green-dark p-5 text-white relative overflow-hidden rounded-t-xl">
                    <!-- Patrón decorativo -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-24 h-24 rounded-full bg-white transform translate-x-12 -translate-y-12"></div>
                    </div>

                    <div class="relative flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-bold mb-1 truncate">
                                <?= htmlspecialchars((string) ($r['name'] ?? 'Ruta')) ?>
                            </h3>
                            <?php if (!empty($r['description'])): ?>
                                <p class="text-green-100 text-sm line-clamp-2">
                                    <?= htmlspecialchars((string) $r['description']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="flex-shrink-0 ml-3">
                            <i class="fas fa-route text-4xl opacity-30"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    <!-- Badge de estado -->
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold <?= $isActive ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' ?>">
                        <i class="fas <?= $isActive ? 'fa-check-circle' : 'fa-pause-circle' ?>"></i>
                        <?= $isActive ? 'Activa' : 'Inactiva' ?>
                    </span>

                    <!-- Metadata -->
                    <div class="mt-4 space-y-2 text-sm text-neu-text-light">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-briefcase w-4 text-sena-green"></i>
                            <span>
                                <strong class="text-neu-text-main"><?= (int) $scenarioCount ?></strong>
                                escenario<?= $scenarioCount !== 1 ? 's' : '' ?>
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <i class="fas fa-users w-4"></i>
                            <span>
                                <?= empty($groups) ? 'Sin asignar' : htmlspecialchars(implode(', ', array_map('strval', $groups))) ?>
                            </span>
                        </div>

                        <?php if (!empty($r['start_date']) || !empty($r['end_date'])): ?>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar w-4"></i>
                                <span>
                                    <?= htmlspecialchars((string) ($r['start_date'] ?? '')) ?>
                                    <?php if (!empty($r['start_date']) && !empty($r['end_date'])): ?> - <?php endif; ?>
                                    <?= htmlspecialchars((string) ($r['end_date'] ?? '')) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Actions -->
                    <div class="mt-5 flex gap-2">
                        <a
                            href="<?= Router::url('/instructor/routes/' . (int) ($r['id'] ?? 0)) ?>"
                            class="flex-1 neu-btn-primary px-4 py-2 rounded-full text-center text-sm"
                        >
                            <i class="fas fa-eye mr-1"></i> Ver Detalle
                        </a>
                        <a
                            href="<?= Router::url('/instructor/routes/create') ?>"
                            class="neu-flat px-3 py-2 rounded-full text-sm"
                            title="Duplicar ruta"
                        >
                            <i class="fas fa-copy text-neu-text-light"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
