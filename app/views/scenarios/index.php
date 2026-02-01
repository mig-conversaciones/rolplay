<?php

declare(strict_types=1);

use App\Core\Router;

$scenarios = $scenarios ?? [];
?>

<!-- Header de sección -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-briefcase text-sena-green mr-2"></i>
                Escenarios Disponibles
            </h1>
            <p class="text-neu-text-light">
                Selecciona un caso para practicar tus competencias transversales en contextos laborales reales.
            </p>
        </div>
        <a href="<?= Router::homeUrl() ?>" class="neu-flat px-6 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Volver al Inicio
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="neu-flat p-6 mb-8">
    <div class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1">
            <label for="filter-area" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-filter mr-1"></i> Filtrar por Área
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
        <div class="flex-1">
            <label for="filter-difficulty" class="block text-sm font-semibold text-neu-text-main mb-2">
                <i class="fas fa-chart-line mr-1"></i> Nivel de Dificultad
            </label>
            <select id="filter-difficulty" class="neu-input w-full">
                <option value="">Todos los niveles</option>
                <option value="basico">Básico</option>
                <option value="intermedio">Intermedio</option>
                <option value="avanzado">Avanzado</option>
            </select>
        </div>
        <button onclick="applyFilters()" class="neu-btn-primary px-8 py-3 rounded-full">
            <i class="fas fa-search"></i> Buscar
        </button>
    </div>
</div>

<?php if (empty($scenarios)): ?>
    <!-- Estado vacío -->
    <div class="neu-flat p-12 text-center">
        <div class="mb-6">
            <i class="fas fa-inbox text-neu-text-light text-6xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-neu-text-main mb-2">No hay escenarios disponibles</h3>
        <p class="text-neu-text-light mb-6">
            Aún no hay escenarios activos en la base de datos. Los instructores pueden generar casos con IA.
        </p>
        <a href="<?= Router::homeUrl() ?>" class="neu-btn-primary px-6 py-3 rounded-full">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
    </div>
<?php else: ?>
    <!-- Grid de escenarios -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" id="scenarios-grid">
        <?php foreach ($scenarios as $scenario): ?>
            <article class="neu-card neu-flat overflow-hidden transition-all duration-300 hover:shadow-lg"
                     data-area="<?= htmlspecialchars($scenario['area'] ?? '') ?>"
                     data-difficulty="<?= htmlspecialchars($scenario['difficulty'] ?? '') ?>">

                <div class="p-6">
                    <!-- Badges de área y dificultad -->
                    <div class="flex flex-wrap gap-2 mb-3">
                        <?php
                        $areaColors = [
                            'tecnologia' => 'bg-blue-100 text-blue-800',
                            'comercio' => 'bg-amber-100 text-amber-800',
                            'salud' => 'bg-red-100 text-red-800',
                            'industrial' => 'bg-gray-100 text-gray-800',
                            'agropecuario' => 'bg-green-100 text-green-800',
                            'general' => 'bg-purple-100 text-purple-800',
                        ];
                        $difficultyColors = [
                            'basico' => 'bg-emerald-100 text-emerald-700',
                            'intermedio' => 'bg-yellow-100 text-yellow-700',
                            'avanzado' => 'bg-rose-100 text-rose-700',
                        ];
                        $area = strtolower($scenario['area'] ?? 'general');
                        $difficulty = strtolower($scenario['difficulty'] ?? 'basico');
                        ?>
                        <span class="px-3 py-1 text-xs font-medium rounded-full <?= $areaColors[$area] ?? 'bg-gray-100 text-gray-800' ?>">
                            <i class="fas fa-tag mr-1"></i><?= ucfirst($area) ?>
                        </span>
                        <span class="px-3 py-1 text-xs font-medium rounded-full <?= $difficultyColors[$difficulty] ?? 'bg-gray-100 text-gray-700' ?>">
                            <i class="fas fa-signal mr-1"></i><?= ucfirst($difficulty) ?>
                        </span>
                        <?php if (!empty($scenario['is_ai_generated'])): ?>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-violet-100 text-violet-700">
                                <i class="fas fa-robot mr-1"></i>IA
                            </span>
                        <?php endif; ?>
                    </div>

                    <h3 class="text-xl font-bold text-neu-text-main mb-3">
                        <?= htmlspecialchars($scenario['title']) ?>
                    </h3>
                    <p class="text-neu-text-light text-sm mb-4 line-clamp-2">
                        <?= htmlspecialchars($scenario['description']) ?>
                    </p>

                    <!-- Duración estimada -->
                    <div class="flex items-center text-sm text-neu-text-light mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        <span><?= (int)($scenario['estimated_duration'] ?? 15) ?> min</span>
                    </div>

                    <a href="<?= Router::url('/scenarios/' . $scenario['id']) ?>"
                       class="neu-btn-primary inline-flex items-center justify-center w-full py-3 px-6 text-center font-semibold rounded-full transition-all duration-200 hover:scale-[1.02]">
                        <i class="fas fa-play-circle mr-2"></i>
                        Iniciar Simulación
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="mt-8 neu-convex text-neu-text-main rounded-xl p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div>
                <div class="text-3xl font-bold"><?= count($scenarios) ?></div>
                <div class="text-sm">Escenarios Totales</div>
            </div>
            <div>
                <div class="text-3xl font-bold">
                    <?= count(array_filter($scenarios, fn($s) => !empty($s['is_ai_generated']))) ?>
                </div>
                <div class="text-sm">Generados con IA</div>
            </div>
            <div>
                <div class="text-3xl font-bold">4</div>
                <div class="text-sm">Competencias Medidas</div>
            </div>
            <div>
                <div class="text-3xl font-bold">
                    <?= array_sum(array_map(fn($s) => (int)($s['estimated_duration'] ?? 15), $scenarios)) ?>
                </div>
                <div class="text-sm">Minutos Totales</div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    function applyFilters() {
        const areaFilter = document.getElementById('filter-area').value.toLowerCase();
        const difficultyFilter = document.getElementById('filter-difficulty').value.toLowerCase();
        const scenarios = document.querySelectorAll('[data-area]');

        scenarios.forEach(scenario => {
            const area = scenario.getAttribute('data-area').toLowerCase();
            const difficulty = scenario.getAttribute('data-difficulty').toLowerCase();

            const matchesArea = !areaFilter || area === areaFilter;
            const matchesDifficulty = !difficultyFilter || difficulty === difficultyFilter;

            if (matchesArea && matchesDifficulty) {
                scenario.style.display = '';
            } else {
                scenario.style.display = 'none';
            }
        });
    }
</script>
