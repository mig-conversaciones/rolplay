<?php

declare(strict_types=1);

/**
 * Stats Card Component - Componente de estadísticas/KPI
 *
 * Ejemplo de uso:
 *
 * <?php
 * $props = [
 *     'label' => 'Usuarios Totales',
 *     'value' => 1250,
 *     'icon' => 'fa-users',
 *     'color' => 'green',
 *     'trend' => '+12.5%',
 *     'trendUp' => true
 * ];
 * renderStatsCard($props);
 * ?>
 *
 * @param array $props Propiedades del componente:
 *   - label (string): Etiqueta descriptiva del KPI (requerido)
 *   - value (string|int): Valor principal a mostrar (requerido)
 *   - icon (string): Clase de Font Awesome (requerido)
 *   - color (string): 'blue'|'green'|'violet'|'yellow' (default: 'blue')
 *   - trend (string): Texto de tendencia opcional (ej: '+12%')
 *   - trendUp (bool): Si es true muestra flecha arriba (verde), false muestra flecha abajo (roja)
 *   - subtitle (string): Texto adicional debajo del trend
 */

if (!function_exists('renderStatsCard')) {
    function renderStatsCard(array $props): void
    {
        // Propiedades requeridas
        if (empty($props['label']) || empty($props['icon'])) {
            return;
        }

        $label = $props['label'];
        $value = $props['value'] ?? '0';
        $icon = $props['icon'];
        $color = $props['color'] ?? 'blue';
        $trend = $props['trend'] ?? '';
        $trendUp = $props['trendUp'] ?? true;
        $subtitle = $props['subtitle'] ?? '';

        // Mapeo de colores
        $colorMap = [
            'blue' => [
                'border' => 'border-sena-blue',
                'text' => 'text-sena-blue',
                'bg' => 'bg-blue-100'
            ],
            'green' => [
                'border' => 'border-sena-green',
                'text' => 'text-sena-green',
                'bg' => 'bg-green-100'
            ],
            'violet' => [
                'border' => 'border-sena-violet',
                'text' => 'text-sena-violet',
                'bg' => 'bg-purple-100'
            ],
            'yellow' => [
                'border' => 'border-sena-yellow',
                'text' => 'text-gray-800',
                'bg' => 'bg-yellow-100'
            ],
        ];

        $colors = $colorMap[$color] ?? $colorMap['blue'];
        ?>

        <div class="bg-white rounded-xl shadow-lg p-6 card-hover border-l-4 <?= $colors['border'] ?> transition duration-300">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-600 text-sm font-medium mb-1">
                        <?= htmlspecialchars($label) ?>
                    </p>
                    <p class="text-3xl font-bold <?= $colors['text'] ?>">
                        <?= htmlspecialchars((string)$value) ?>
                    </p>
                </div>
                <div class="<?= $colors['bg'] ?> rounded-full p-4">
                    <i class="fas <?= htmlspecialchars($icon) ?> <?= $colors['text'] ?> text-2xl"></i>
                </div>
            </div>

            <?php if (!empty($trend) || !empty($subtitle)): ?>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <?php if (!empty($trend)): ?>
                        <p class="text-sm flex items-center gap-1">
                            <?php if ($trendUp): ?>
                                <i class="fas fa-arrow-up text-green-500"></i>
                                <span class="text-green-600 font-medium"><?= htmlspecialchars($trend) ?></span>
                            <?php else: ?>
                                <i class="fas fa-arrow-down text-red-500"></i>
                                <span class="text-red-600 font-medium"><?= htmlspecialchars($trend) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($subtitle)): ?>
                                <span class="text-gray-500 ml-1"><?= htmlspecialchars($subtitle) ?></span>
                            <?php endif; ?>
                        </p>
                    <?php elseif (!empty($subtitle)): ?>
                        <p class="text-sm text-gray-500">
                            <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                            <?= htmlspecialchars($subtitle) ?>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
    }
}
