<?php

declare(strict_types=1);

use App\Core\Router;

$files = $files ?? [];
$logContent = $logContent ?? '';
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-chart-bar text-sena-yellow mr-2"></i>
                Logs del Sistema
            </h1>
            <p class="text-neu-text-light">
                RevisiÃ³n rÃ¡pida de los registros recientes del sistema.
            </p>
        </div>
        <a href="<?= Router::url('/admin/settings') ?>" class="neu-flat px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> ConfiguraciÃ³n
        </a>
    </div>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <div class="neu-flat p-6">
        <h2 class="text-lg font-bold text-neu-text-main mb-4">
            <i class="fas fa-folder-open text-sena-blue mr-2"></i>
            Archivos disponibles
        </h2>
        <?php if (empty($files)): ?>
            <p class="text-neu-text-light text-sm">No hay archivos en storage/logs.</p>
        <?php else: ?>
            <ul class="space-y-2 text-sm">
                <?php foreach ($files as $file): ?>
                    <li class="neu-flat px-3 py-2 rounded-lg flex items-center justify-between">
                        <span class="text-neu-text-main truncate"><?= htmlspecialchars($file['name']) ?></span>
                        <span class="text-xs text-neu-text-light ml-2">
                            <?= date('d/m/Y H:i', (int) ($file['modified'] ?? 0)) ?>
                        </span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <div class="neu-flat p-6 md:col-span-2">
        <h2 class="text-lg font-bold text-neu-text-main mb-4">
            <i class="fas fa-file-alt text-sena-green mr-2"></i>
            Ãšltimas 200 lÃ­neas
        </h2>
        <?php if ($logContent === ''): ?>
            <p class="text-neu-text-light text-sm">No hay contenido para mostrar.</p>
        <?php else: ?>
            <pre class="text-xs text-neu-text-main bg-gray-50 p-4 rounded-lg overflow-auto max-h-[60vh]"><?= htmlspecialchars($logContent) ?></pre>
        <?php endif; ?>
    </div>
</div>
