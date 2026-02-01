<?php

declare(strict_types=1);

use App\Core\Router;

$summary = $summary ?? [];
$aprendices = $aprendices ?? [];
$scenarios = $scenarios ?? [];
$alerts = $alerts ?? [];
$fichas = $fichas ?? [];
$areas = $areas ?? [];
$selectedFicha = $selectedFicha ?? '';
$selectedArea = $selectedArea ?? '';
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-chalkboard-teacher text-sena-green mr-2"></i>
                Dashboard Instructor
            </h1>
            <p class="text-neu-text-light">
                Vista agregada del progreso de aprendices y rendimiento de escenarios.
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="<?= Router::url('/scenarios') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
                <i class="fas fa-briefcase"></i> Escenarios
            </a>
            <a href="<?= Router::url('/instructor/routes') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
                <i class="fas fa-route"></i> Rutas
            </a>
            <a href="<?= Router::url('/instructor/programs') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
                <i class="fas fa-folder-open"></i> Programas
            </a>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="neu-flat p-6 mb-8">
    <form method="get" action="<?= Router::url('/instructor') ?>" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1">
            <label for="ficha" class="block text-sm font-semibold text-neu-text-main mb-2">Ficha</label>
            <select id="ficha" name="ficha" class="neu-input w-full">
                <option value="">Todas las fichas</option>
                <?php foreach ($fichas as $f): ?>
                    <option value="<?= htmlspecialchars($f) ?>" <?= $selectedFicha === $f ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex-1">
            <label for="area" class="block text-sm font-semibold text-neu-text-main mb-2">Área</label>
            <select id="area" name="area" class="neu-input w-full">
                <option value="">Todas las áreas</option>
                <?php foreach ($areas as $a): ?>
                    <option value="<?= htmlspecialchars($a) ?>" <?= $selectedArea === $a ? 'selected' : '' ?>>
                        <?= ucfirst(htmlspecialchars($a)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="neu-btn-primary px-6 py-3 rounded-full">
                <i class="fas fa-search"></i> Aplicar
            </button>
            <a href="<?= Router::url('/instructor') ?>" class="neu-flat px-6 py-3 rounded-full">
                <i class="fas fa-redo"></i> Limpiar
            </a>
        </div>
    </form>
</div>

<!-- Resumen de plataforma (KPIs) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="neu-flat p-6">
        <div class="text-4xl font-bold text-neu-text-main mb-1">
            <?= (int)($summary['total_aprendices'] ?? 0) ?>
        </div>
        <div class="text-sm text-neu-text-light">Aprendices</div>
    </div>

    <div class="neu-flat p-6">
        <div class="text-4xl font-bold text-neu-text-main mb-1">
            <?= (int)($summary['total_sessions'] ?? 0) ?>
        </div>
        <div class="text-sm text-neu-text-light">Sesiones</div>
    </div>

    <div class="neu-flat p-6">
        <div class="text-4xl font-bold text-neu-text-main mb-1">
            <?= (int)($summary['completed_sessions'] ?? 0) ?>
        </div>
        <div class="text-sm text-neu-text-light">Finalizadas</div>
    </div>

    <div class="neu-flat p-6">
        <div class="text-4xl font-bold text-neu-text-main mb-1">
            <?= number_format((float)($summary['avg_score'] ?? 0), 1) ?>
        </div>
        <div class="text-sm text-neu-text-light">Puntuación</div>
    </div>

    <div class="neu-flat p-6">
        <div class="text-4xl font-bold text-neu-text-main mb-1">
            <?= (int)($summary['best_score'] ?? 0) ?>
        </div>
        <div class="text-sm text-neu-text-light">Récord</div>
    </div>
</div>

<!-- Gráficos principales -->
<div class="grid lg:grid-cols-2 gap-8 mb-8">
    <!-- Gráfico de competencias (Radar) -->
    <div class="neu-flat p-6">
        <h3 class="font-bold text-lg text-neu-text-main mb-4">
            Promedio por Competencia
        </h3>
        <div class="h-64">
            <canvas id="competenciasChart"></canvas>
        </div>
    </div>

    <!-- Gráfico de rendimiento por escenario (Barras) -->
    <div class="neu-flat p-6">
        <h3 class="font-bold text-lg text-neu-text-main mb-4">
            Top 5 Escenarios Más Jugados
        </h3>
        <div class="h-64">
            <canvas id="scenariosChart"></canvas>
        </div>
    </div>
</div>

<!-- Alertas: bajo desempeño -->
<?php if (!empty($alerts)): ?>
<div class="neu-flat p-6 mb-8">
    <h3 class="font-bold text-lg text-neu-text-main mb-4">
        Alertas: Aprendices con Bajo Desempeño
    </h3>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($alerts as $a): ?>
            <div class="neu-flat p-4">
                <div class="font-bold text-neu-text-main"><?= htmlspecialchars((string)($a['name'] ?? 'Aprendiz')) ?></div>
                <div class="text-xs text-neu-text-light"><?= htmlspecialchars((string)($a['email'] ?? '')) ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Rendimiento por aprendiz -->
<div class="neu-flat p-6 mb-8">
    <h3 class="font-bold text-lg text-neu-text-main mb-4">
        Rendimiento por Aprendiz
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="neu-convex">
                <tr>
                    <th class="px-4 py-3 text-sm font-semibold">Nombre</th>
                    <th class="px-4 py-3 text-sm font-semibold text-center">Puntos</th>
                    <th class="px-4 py-3 text-sm font-semibold text-center">Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aprendices as $a): ?>
                    <tr class="border-b border-neu-shadow-dark">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-neu-text-main"><?= htmlspecialchars((string)($a['name'] ?? 'Aprendiz')) ?></div>
                        </td>
                        <td class="px-4 py-3 text-center font-bold text-sena-green">
                            <?= number_format((int)($a['total_points'] ?? 0)) ?>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="neu-flat px-3 py-1 rounded-full text-sm font-semibold">
                                <?= number_format((float)($a['average_score'] ?? 0), 1) ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Rendimiento por escenario -->
<div class="neu-flat p-6">
    <h3 class="font-bold text-lg text-neu-text-main mb-4">
        Rendimiento por Escenario
    </h3>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($scenarios as $s): ?>
            <div class="neu-flat p-4">
                <h4 class="font-bold text-neu-text-main mb-2"><?= htmlspecialchars((string)($s['title'] ?? 'Escenario')) ?></h4>
                <div class="mt-3">
                    <a href="<?= Router::url('/scenarios/' . (int)($s['id'] ?? 0)) ?>" class="neu-btn-primary w-full justify-center text-sm">
                        Ver Escenario
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Chart.js Scripts -->
<script>
    // Gráfico de Competencias (Radar)
    const ctxCompetencias = document.getElementById('competenciasChart');
    if (ctxCompetencias) {
        new Chart(ctxCompetencias, {
            type: 'radar',
            data: {
                labels: ['Comunicación', 'Liderazgo', 'Trabajo en Equipo', 'Toma de Decisiones'],
                datasets: [{
                    label: 'Promedio General',
                    data: [65, 72, 68, 70], // Estos datos deberían venir del backend
                    backgroundColor: 'rgba(57, 169, 0, 0.2)',
                    borderColor: 'rgba(57, 169, 0, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(57, 169, 0, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(57, 169, 0, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    // Gráfico de Escenarios (Barras)
    const ctxScenarios = document.getElementById('scenariosChart');
    if (ctxScenarios) {
        new Chart(ctxScenarios, {
            type: 'bar',
            data: {
                labels: [
                    <?php if (!empty($scenarios)): ?>
                        <?php foreach (array_slice($scenarios, 0, 5) as $s): ?>
                            '<?= addslashes(htmlspecialchars((string)($s['title'] ?? 'Escenario'))) ?>',
                        <?php endforeach; ?>
                    <?php else: ?>
                        'Sin datos'
                    <?php endif; ?>
                ],
                datasets: [{
                    label: 'Sesiones Jugadas',
                    data: [
                        <?php if (!empty($scenarios)): ?>
                            <?php foreach (array_slice($scenarios, 0, 5) as $s): ?>
                                <?= (int)($s['sessions_count'] ?? 0) ?>,
                            <?php endforeach; ?>
                        <?php else: ?>
                            0
                        <?php endif; ?>
                    ],
                    backgroundColor: 'rgba(0, 48, 77, 0.7)',
                    borderColor: 'rgba(0, 48, 77, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
</script>
