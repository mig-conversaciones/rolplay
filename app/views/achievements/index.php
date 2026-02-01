<?php

declare(strict_types=1);

use App\Core\Router;

$achievements = $achievements ?? [];
$stats = $stats ?? [];
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-trophy text-sena-yellow mr-2"></i>
                Mis Logros
            </h1>
            <p class="text-gray-600">
                Colecciona insignias desbloqueando logros mediante tu progreso en la plataforma.
            </p>
        </div>
        <div class="flex gap-2">
            <a href="<?= Router::url('/achievements/ranking') ?>" class="btn-secondary">
                <i class="fas fa-medal"></i> Ver Ranking
            </a>
            <a href="<?= Router::url('/profile') ?>" class="btn-secondary">
                <i class="fas fa-user"></i> Mi Perfil
            </a>
        </div>
    </div>
</div>

<!-- Progreso general -->
<div class="bg-gradient-to-br from-sena-green to-sena-green-dark text-white rounded-2xl shadow-2xl p-8 mb-8">
    <div class="grid md:grid-cols-3 gap-6 text-center">
        <div>
            <div class="text-5xl font-bold mb-2"><?= (int)($stats['unlocked'] ?? 0) ?></div>
            <div class="text-white/80">Logros Desbloqueados</div>
        </div>
        <div>
            <div class="text-5xl font-bold mb-2"><?= (int)($stats['total'] ?? 0) ?></div>
            <div class="text-white/80">Logros Totales</div>
        </div>
        <div>
            <div class="text-5xl font-bold mb-2"><?= number_format((float)($stats['percentage'] ?? 0), 1) ?>%</div>
            <div class="text-white/80">Completitud</div>
        </div>
    </div>

    <!-- Barra de progreso -->
    <div class="mt-6">
        <div class="progress-bar-container bg-white/20">
            <div class="progress-bar bg-white" style="width: <?= (float)($stats['percentage'] ?? 0) ?>%"></div>
        </div>
    </div>
</div>

<?php if (empty($achievements)): ?>
    <!-- Estado vacío -->
    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
        <i class="fas fa-trophy text-gray-300 text-6xl mb-4"></i>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">No hay logros disponibles</h3>
        <p class="text-gray-600 mb-6">
            Los logros se agregarán pronto. ¡Sigue participando en las simulaciones!
        </p>
    </div>
<?php else: ?>
    <!-- Logros por categoría -->
    <?php
    $categoryNames = [
        'progreso' => ['name' => 'Progreso', 'icon' => 'fa-chart-line', 'color' => 'sena-green'],
        'excelencia' => ['name' => 'Excelencia', 'icon' => 'fa-star', 'color' => 'sena-yellow'],
        'social' => ['name' => 'Social', 'icon' => 'fa-users', 'color' => 'sena-violet'],
        'especial' => ['name' => 'Especial', 'icon' => 'fa-gem', 'color' => 'sena-blue'],
        'general' => ['name' => 'General', 'icon' => 'fa-trophy', 'color' => 'sena-green'],
    ];
    ?>

    <?php foreach ($achievements as $category => $items): ?>
        <?php
        $categoryInfo = $categoryNames[$category] ?? $categoryNames['general'];
        $totalInCategory = count($items);
        $unlockedInCategory = count(array_filter($items, fn($a) => !empty($a['is_unlocked'])));
        $percentageCategory = $totalInCategory > 0 ? round(($unlockedInCategory / $totalInCategory) * 100, 1) : 0;
        ?>

        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas <?= $categoryInfo['icon'] ?> text-<?= $categoryInfo['color'] ?> mr-2"></i>
                    <?= $categoryInfo['name'] ?>
                </h2>
                <span class="text-sm text-gray-600 font-semibold">
                    <?= $unlockedInCategory ?> / <?= $totalInCategory ?> (<?= $percentageCategory ?>%)
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                <?php foreach ($items as $achievement): ?>
                    <?php $isUnlocked = !empty($achievement['is_unlocked']); ?>

                    <div class="bg-white rounded-xl shadow-lg p-6 text-center card-hover relative <?= $isUnlocked ? '' : 'opacity-60' ?>">
                        <!-- Badge de estado -->
                        <?php if ($isUnlocked): ?>
                            <div class="absolute top-2 right-2">
                                <i class="fas fa-check-circle text-sena-green text-xl"></i>
                            </div>
                        <?php else: ?>
                            <div class="absolute top-2 right-2">
                                <i class="fas fa-lock text-gray-400 text-xl"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Icono del logro -->
                        <div class="mb-4">
                            <div class="<?= $isUnlocked ? 'bg-gradient-to-br from-sena-yellow to-yellow-600' : 'bg-gray-200' ?> rounded-full w-20 h-20 flex items-center justify-center mx-auto shadow-lg">
                                <i class="fas <?= htmlspecialchars($achievement['icon'] ?? 'fa-trophy') ?> text-3xl <?= $isUnlocked ? 'text-white' : 'text-gray-400' ?>"></i>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <h3 class="font-bold text-gray-800 mb-2 text-sm">
                            <?= htmlspecialchars($achievement['name']) ?>
                        </h3>

                        <!-- Descripción -->
                        <p class="text-gray-600 text-xs mb-3 line-clamp-2">
                            <?= htmlspecialchars($achievement['description']) ?>
                        </p>

                        <!-- Puntos -->
                        <div class="flex items-center justify-center gap-2">
                            <i class="fas fa-coins text-sena-yellow"></i>
                            <span class="font-bold text-gray-800"><?= (int)$achievement['points'] ?> pts</span>
                        </div>

                        <!-- Fecha de desbloqueo -->
                        <?php if ($isUnlocked && !empty($achievement['unlocked_at'])): ?>
                            <div class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                <?= date('d/m/Y', strtotime($achievement['unlocked_at'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Llamado a la acción -->
<div class="bg-gradient-to-r from-sena-blue to-sena-green-dark text-white rounded-2xl shadow-2xl p-8 text-center mt-8">
    <h2 class="text-2xl md:text-3xl font-bold mb-4">
        ¿Quieres Más Logros?
    </h2>
    <p class="text-lg mb-6 text-white/90">
        Completa más escenarios y mejora tu desempeño para desbloquear todas las insignias.
    </p>
    <a href="<?= Router::url('/scenarios') ?>" class="bg-white text-sena-green px-8 py-3 rounded-lg font-bold hover:bg-gray-100 transition duration-300 shadow-lg inline-flex items-center gap-2">
        <i class="fas fa-play-circle"></i> Jugar Escenarios
    </a>
</div>
