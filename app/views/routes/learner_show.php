<?php

use App\Core\Router;

$route = $route ?? null;
$scenarios = $scenarios ?? [];
?>
<?php if (!$route): ?>
    <section class="neu-flat p-6">
        <h2 class="text-xl font-bold text-neu-text-main mt-0">Ruta no disponible</h2>
        <a class="neu-btn-primary px-4 py-2 rounded-full" href="<?= Router::url('/routes') ?>">Volver</a>
    </section>
    <?php return; ?>
<?php endif; ?>

<!-- Page Header -->
<section class="neu-flat p-6">
    <div class="flex justify-between gap-4 flex-wrap items-start">
        <div>
            <h2 class="text-2xl font-bold text-neu-text-main m-0 mb-2">
                <?= htmlspecialchars((string) ($route['name'] ?? 'Ruta')) ?>
            </h2>
            <?php if (!empty($route['description'])): ?>
                <p class="text-neu-text-light m-0"><?= htmlspecialchars((string) $route['description']) ?></p>
            <?php endif; ?>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a class="neu-flat px-4 py-2 rounded-full text-sm" href="<?= Router::url('/routes') ?>">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
            <a class="neu-flat px-4 py-2 rounded-full text-sm" href="<?= Router::url('/scenarios') ?>">
                <i class="fas fa-briefcase mr-1"></i> Escenarios
            </a>
        </div>
    </div>
</section>

<!-- Escenarios en orden -->
<section class="neu-flat p-6 mt-4">
    <h3 class="text-xl font-bold text-neu-text-main mt-0">
        <i class="fas fa-list-ol text-sena-green mr-2"></i>
        Escenarios en orden
    </h3>
    <?php if (empty($scenarios)): ?>
        <p class="text-neu-text-light">Esta ruta aun no tiene escenarios.</p>
    <?php else: ?>
        <div class="grid gap-3">
            <?php foreach ($scenarios as $idx => $s): ?>
                <div class="neu-flat p-4">
                    <div class="flex justify-between gap-3 flex-wrap items-center">
                        <div>
                            <div class="font-bold text-neu-text-main">
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-sena-green text-white text-xs mr-2">
                                    <?= (int) ($idx + 1) ?>
                                </span>
                                <?= htmlspecialchars((string) ($s['title'] ?? 'Escenario')) ?>
                            </div>
                            <?php if (!empty($s['area']) || !empty($s['difficulty'])): ?>
                                <div class="text-sm text-neu-text-light mt-1 ml-8">
                                    <span class="text-neu-text-light">#<?= (int) ($s['id'] ?? 0) ?></span>
                                    <?php if (!empty($s['area'])): ?><span class="ml-2"><i class="fas fa-tag mr-1"></i><?= htmlspecialchars((string) $s['area']) ?></span><?php endif; ?>
                                    <?php if (!empty($s['difficulty'])): ?><span class="ml-2"><i class="fas fa-signal mr-1"></i><?= htmlspecialchars((string) $s['difficulty']) ?></span><?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a class="neu-btn-primary px-4 py-2 rounded-full text-sm" href="<?= Router::url('/scenarios/' . (int) ($s['id'] ?? 0)) ?>">
                            <i class="fas fa-play mr-1"></i> Iniciar
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
