<?php

declare(strict_types=1);

use App\Core\Router;

$ranking = $ranking ?? [];
$type = $type ?? 'general';
$competence = $competence ?? 'comunicacion';
$userPosition = $userPosition ?? null;
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-medal text-sena-yellow mr-2"></i>
                Ranking Global
            </h1>
            <p class="text-gray-600">
                Compite con otros aprendices y demuestra tus habilidades.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="<?= Router::url('/achievements') ?>" class="btn-secondary">
                <i class="fas fa-trophy"></i> Mis Logros
            </a>
            <a href="<?= Router::url('/profile') ?>" class="btn-secondary">
                <i class="fas fa-user"></i> Mi Perfil
            </a>
        </div>
    </div>
</div>

<!-- Filtros de tipo de ranking -->
<div class="bg-white rounded-xl shadow-lg p-6 mb-8">
    <h3 class="font-bold text-lg text-gray-800 mb-4">
        <i class="fas fa-filter text-sena-green mr-2"></i>
        Tipo de Ranking
    </h3>
    <div class="flex flex-col md:flex-row gap-4">
        <!-- Tipo de ranking -->
        <div class="flex-1">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Clasificación</label>
            <select id="ranking-type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sena-green focus:border-sena-green transition duration-300" onchange="updateRanking()">
                <option value="general" <?= $type === 'general' ? 'selected' : '' ?>>Puntuación Total</option>
                <option value="competence" <?= $type === 'competence' ? 'selected' : '' ?>>Por Competencia</option>
            </select>
        </div>

        <!-- Competencia (solo si type=competence) -->
        <div class="flex-1" id="competence-selector" style="display: <?= $type === 'competence' ? 'block' : 'none' ?>">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Competencia</label>
            <select id="competence" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sena-green focus:border-sena-green transition duration-300" onchange="updateRanking()">
                <option value="comunicacion" <?= $competence === 'comunicacion' ? 'selected' : '' ?>>💬 Comunicación</option>
                <option value="liderazgo" <?= $competence === 'liderazgo' ? 'selected' : '' ?>>👥 Liderazgo</option>
                <option value="trabajo_equipo" <?= $competence === 'trabajo_equipo' ? 'selected' : '' ?>>🤝 Trabajo en Equipo</option>
                <option value="toma_decisiones" <?= $competence === 'toma_decisiones' ? 'selected' : '' ?>>🎯 Toma de Decisiones</option>
            </select>
        </div>
    </div>
</div>

<!-- Tu posición -->
<?php if ($userPosition): ?>
<div class="bg-gradient-to-br from-sena-green to-sena-green-dark text-white rounded-xl shadow-lg p-6 mb-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-sm text-white/80 mb-1">Tu Posición</div>
            <div class="text-4xl font-bold">#<?= $userPosition ?></div>
        </div>
        <div class="bg-white/20 rounded-full p-6">
            <i class="fas fa-user-circle text-5xl"></i>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Ranking -->
<?php if (empty($ranking)): ?>
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <i class="fas fa-users text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">No hay datos de ranking</h3>
        <p class="text-gray-600">
            Completa simulaciones para aparecer en el ranking global.
        </p>
    </div>
<?php else: ?>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-sena-green to-sena-green-dark text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">Posición</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Aprendiz</th>
                        <?php if ($type === 'general'): ?>
                            <th class="px-6 py-4 text-center text-sm font-bold">Puntos</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Logros</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Sesiones</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Promedio</th>
                        <?php else: ?>
                            <th class="px-6 py-4 text-center text-sm font-bold">Promedio en Competencia</th>
                            <th class="px-6 py-4 text-center text-sm font-bold">Sesiones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($ranking as $index => $entry): ?>
                        <?php
                        $position = $index + 1;
                        $isCurrentUser = $user && (int)$entry['id'] === (int)$user['id'];

                        // Colores de medalla para top 3
                        $medalColors = [
                            1 => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600', 'icon' => 'fa-crown'],
                            2 => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'fa-medal'],
                            3 => ['bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'icon' => 'fa-medal'],
                        ];
                        $medal = $medalColors[$position] ?? null;
                        ?>

                        <tr class="<?= $isCurrentUser ? 'bg-green-50 font-bold' : 'hover:bg-gray-50' ?> transition duration-200">
                            <!-- Posición -->
                            <td class="px-6 py-4">
                                <?php if ($medal): ?>
                                    <div class="<?= $medal['bg'] ?> <?= $medal['text'] ?> rounded-full w-12 h-12 flex items-center justify-center font-bold text-lg">
                                        <i class="fas <?= $medal['icon'] ?>"></i>
                                    </div>
                                <?php else: ?>
                                    <div class="text-gray-600 font-bold text-lg">
                                        #<?= $position ?>
                                    </div>
                                <?php endif; ?>
                            </td>

                            <!-- Nombre -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="bg-sena-green rounded-full w-10 h-10 flex items-center justify-center text-white font-bold">
                                        <?= strtoupper(substr($entry['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-800">
                                            <?= htmlspecialchars($entry['name']) ?>
                                            <?php if ($isCurrentUser): ?>
                                                <span class="text-sena-green ml-2">(Tú)</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs text-gray-500"><?= htmlspecialchars($entry['email']) ?></div>
                                        <?php if (!empty($entry['ficha'])): ?>
                                            <div class="text-xs text-gray-500">
                                                <i class="fas fa-id-card mr-1"></i><?= htmlspecialchars($entry['ficha']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Métricas -->
                            <?php if ($type === 'general'): ?>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fas fa-coins text-sena-yellow"></i>
                                        <span class="font-bold text-lg text-sena-green">
                                            <?= number_format((int)($entry['total_points'] ?? 0)) ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fas fa-trophy text-sena-yellow"></i>
                                        <span class="font-semibold"><?= (int)($entry['achievements_unlocked'] ?? 0) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    <?= (int)($entry['total_sessions'] ?? 0) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                        <?= number_format((float)($entry['avg_score'] ?? 0), 1) ?>
                                    </span>
                                </td>
                            <?php else: ?>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-4 py-2 rounded-full text-lg font-bold bg-sena-green text-white">
                                        <?= number_format((float)($entry['avg_competence_score'] ?? 0), 1) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-600">
                                    <?= (int)($entry['total_sessions'] ?? 0) ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Script para cambiar tipo de ranking -->
<script>
    const typeSelect = document.getElementById('ranking-type');
    const competenceDiv = document.getElementById('competence-selector');

    if (typeSelect) {
        typeSelect.addEventListener('change', function() {
            if (this.value === 'competence') {
                competenceDiv.style.display = 'block';
            } else {
                competenceDiv.style.display = 'none';
            }
        });
    }

    function updateRanking() {
        const type = document.getElementById('ranking-type').value;
        const competence = document.getElementById('competence').value;

        let url = '<?= Router::url('/achievements/ranking') ?>?type=' + type;
        if (type === 'competence') {
            url += '&competence=' + competence;
        }

        window.location.href = url;
    }
</script>
