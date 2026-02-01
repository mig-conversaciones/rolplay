<?php

declare(strict_types=1);

use App\Core\Router;

$stats = $stats ?? [];
$history = $history ?? [];
$user = $user ?? $_SESSION['user'] ?? [];

$totalSessions = (int) ($stats['total_sessions'] ?? 0);
$completedSessions = (int) ($stats['completed_sessions'] ?? 0);
$totalPoints = (int) ($stats['total_points'] ?? 0);
$avgScore = (float) ($stats['average_score'] ?? 0);
$bestScore = (int) ($stats['best_score'] ?? 0);
$lastActivity = $stats['last_activity'] ?? null;
$completionRate = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0;
?>

<!-- Profile Header con gradiente y avatar -->
<div class="neu-convex rounded-2xl p-8 mb-8 text-neu-text-main">
    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
        <!-- Avatar -->
        <div class="w-24 h-24 rounded-full neu-flat flex items-center justify-center text-4xl font-bold">
            <?= strtoupper(substr($user['name'] ?? $user['email'] ?? 'U', 0, 1)) ?>
        </div>

        <!-- User Info -->
        <div class="flex-1 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">
                <?= htmlspecialchars($user['name'] ?? 'Mi Perfil') ?>
            </h1>
            <p class="text-neu-text-light mb-3">
                <?= htmlspecialchars($user['email'] ?? '') ?>
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <a href="<?= Router::url('/scenarios') ?>" class="neu-flat px-4 py-2 rounded-full">
                Escenarios
            </a>
            <a href="<?= Router::homeUrl() ?>" class="neu-flat px-4 py-2 rounded-full">
                <i class="fas fa-home mr-1"></i> Inicio
            </a>
        </div>
    </div>
</div>

<!-- Tabs Container -->
<div class="neu-flat p-6" data-component="tabs">
    <!-- Tab List -->
    <div class="flex border-b border-neu-shadow-dark" data-tabs-list>
        <button class="neu-pressed py-2 px-4 rounded-t-lg" data-tab="0">
            Estadisticas
        </button>
        <button class="neu-flat py-2 px-4 rounded-t-lg" data-tab="1">
            Historial
        </button>
        <button class="neu-flat py-2 px-4 rounded-t-lg" data-tab="2">
            Logros
        </button>
    </div>

    <!-- Tab Panels -->
    <div class="pt-6">
        <!-- Statistics Panel -->
        <div id="panel-0" data-tab-panel="0">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                <div class="neu-flat p-4">
                    <div class="text-sm text-neu-text-light">Sesiones</div>
                    <div class="text-2xl font-bold text-neu-text-main"><?= $totalSessions ?></div>
                </div>
                <div class="neu-flat p-4">
                    <div class="text-sm text-neu-text-light">Completadas</div>
                    <div class="text-2xl font-bold text-neu-text-main"><?= $completedSessions ?></div>
                    <div class="text-xs text-neu-text-light"><?= $completionRate ?>% de avance</div>
                </div>
                <div class="neu-flat p-4">
                    <div class="text-sm text-neu-text-light">Puntos totales</div>
                    <div class="text-2xl font-bold text-neu-text-main"><?= $totalPoints ?></div>
                </div>
                <div class="neu-flat p-4">
                    <div class="text-sm text-neu-text-light">Promedio</div>
                    <div class="text-2xl font-bold text-neu-text-main"><?= number_format($avgScore, 1) ?></div>
                </div>
                <div class="neu-flat p-4">
                    <div class="text-sm text-neu-text-light">Mejor resultado</div>
                    <div class="text-2xl font-bold text-neu-text-main"><?= $bestScore ?></div>
                    <?php if ($lastActivity): ?>
                        <div class="text-xs text-neu-text-light">Ultima actividad: <?= htmlspecialchars((string) $lastActivity) ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- History Panel -->
        <div id="panel-1" data-tab-panel="1" class="hidden">
            <?php if (empty($history)): ?>
                <div class="neu-flat p-6 text-center text-neu-text-light">
                    Aun no has completado sesiones.
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($history as $item): ?>
                        <div class="neu-flat p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                            <div>
                                <div class="text-lg font-semibold text-neu-text-main">
                                    <?= htmlspecialchars($item['scenario_title'] ?? 'Escenario') ?>
                                </div>
                                <div class="text-sm text-neu-text-light">
                                    Area: <?= htmlspecialchars($item['area'] ?? 'general') ?> |
                                    Dificultad: <?= htmlspecialchars($item['difficulty'] ?? 'basico') ?>
                                </div>
                            </div>
                            <div class="text-sm text-neu-text-light">
                                Puntaje: <strong><?= (int) ($item['final_score'] ?? 0) ?></strong>
                                | Decisiones: <?= (int) ($item['decisions_count'] ?? 0) ?>
                                | Avance: <?= (int) ($item['completion_percentage'] ?? 0) ?>%
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Achievements Panel -->
        <div id="panel-2" data-tab-panel="2" class="hidden">
            <div class="text-center py-12 neu-flat p-6">
                <h3 class="text-2xl font-bold text-neu-text-main mb-3">
                    Sistema de Logros Proximamente
                </h3>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('[data-tab]');
        const panels = document.querySelectorAll('[data-tab-panel]');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const tabId = tab.getAttribute('data-tab');

                tabs.forEach(t => {
                    t.classList.remove('neu-pressed');
                    t.classList.add('neu-flat');
                });
                tab.classList.add('neu-pressed');
                tab.classList.remove('neu-flat');

                panels.forEach(panel => {
                    if (panel.getAttribute('data-tab-panel') === tabId) {
                        panel.classList.remove('hidden');
                    } else {
                        panel.classList.add('hidden');
                    }
                });
            });
        });
    });
</script>
