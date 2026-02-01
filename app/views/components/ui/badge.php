<?php

declare(strict_types=1);

/**
 * Badge Component - Componente de etiqueta/insignia
 *
 * Ejemplo de uso:
 *
 * <?php
 * renderBadge([
 *     'text' => 'Activo',
 *     'variant' => 'success',
 *     'icon' => 'fa-check',
 *     'dot' => false
 * ]);
 * ?>
 *
 * @param array $props Propiedades del componente:
 *   - text (string): Texto del badge (requerido)
 *   - variant (string): 'success'|'danger'|'warning'|'info'|'new' (default: 'info')
 *   - icon (string): Clase de Font Awesome opcional
 *   - dot (bool): Mostrar punto indicador antes del texto
 *   - classes (string): Clases CSS adicionales
 */

if (!function_exists('renderBadge')) {
    function renderBadge(array $props): void
    {
        if (empty($props['text'])) {
            return;
        }

        $text = $props['text'];
        $variant = $props['variant'] ?? 'info';
        $icon = $props['icon'] ?? '';
        $dot = $props['dot'] ?? false;
        $customClasses = $props['classes'] ?? '';

        // Mapeo de variantes
        $variantClasses = [
            'success' => 'bg-green-100 text-green-800',
            'danger' => 'bg-red-100 text-red-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            'info' => 'bg-blue-100 text-blue-800',
            'new' => 'bg-sena-yellow text-gray-900',
        ];

        $variantClass = $variantClasses[$variant] ?? $variantClasses['info'];
        $baseClasses = 'inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium';
        $allClasses = trim("{$baseClasses} {$variantClass} {$customClasses}");
        ?>

        <span class="<?= htmlspecialchars($allClasses) ?>">
            <?php if ($dot): ?>
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
            <?php endif; ?>

            <?php if (!empty($icon)): ?>
                <i class="fas <?= htmlspecialchars($icon) ?>"></i>
            <?php endif; ?>

            <?= htmlspecialchars($text) ?>
        </span>

        <?php
    }
}
