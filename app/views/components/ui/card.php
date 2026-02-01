<?php

declare(strict_types=1);

/**
 * Card Component - Componente de tarjeta reutilizable
 *
 * Ejemplo de uso:
 *
 * <?php
 * $props = [
 *     'title' => 'Título de la tarjeta',
 *     'content' => '<p>Contenido HTML de la tarjeta</p>',
 *     'footer' => '<button class="btn-primary">Acción</button>',
 *     'icon' => 'fa-users',
 *     'borderColor' => 'sena-green',
 *     'hover' => true,
 *     'classes' => 'mb-4'
 * ];
 * renderCard($props);
 * ?>
 *
 * @param array $props Propiedades del componente:
 *   - title (string): Título opcional de la tarjeta
 *   - content (string): Contenido HTML de la tarjeta (requerido)
 *   - footer (string): Footer HTML opcional
 *   - icon (string): Clase de Font Awesome (ej: 'fa-users')
 *   - variant (string): 'default'|'gradient'|'bordered'
 *   - borderColor (string): 'sena-green'|'sena-blue'|'sena-violet'|'sena-yellow'
 *   - hover (bool): Activar efecto hover (default: true)
 *   - classes (string): Clases CSS adicionales
 */

if (!function_exists('renderCard')) {
    function renderCard(array $props): void
    {
        // Propiedades con valores por defecto
        $title = $props['title'] ?? '';
        $content = $props['content'] ?? '';
        $footer = $props['footer'] ?? '';
        $icon = $props['icon'] ?? '';
        $variant = $props['variant'] ?? 'default';
        $borderColor = $props['borderColor'] ?? '';
        $hover = $props['hover'] ?? true;
        $customClasses = $props['classes'] ?? '';

        // Construir clases CSS
        $baseClasses = 'bg-white rounded-xl shadow-lg p-6';
        $hoverClass = $hover ? 'card-hover transition duration-300' : '';
        $borderClass = $borderColor ? "border-l-4 border-{$borderColor}" : '';

        // Variantes especiales
        $variantClass = '';
        if ($variant === 'gradient') {
            $variantClass = 'bg-gradient-to-br from-white to-gray-50';
        } elseif ($variant === 'bordered') {
            $variantClass = 'border border-gray-200';
        }

        $allClasses = trim("{$baseClasses} {$hoverClass} {$borderClass} {$variantClass} {$customClasses}");
        ?>

        <div class="<?= htmlspecialchars($allClasses) ?>">
            <?php if (!empty($title)): ?>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <?php if (!empty($icon)): ?>
                            <i class="fas <?= htmlspecialchars($icon) ?>"></i>
                        <?php endif; ?>
                        <?= htmlspecialchars($title) ?>
                    </h3>
                </div>
            <?php endif; ?>

            <div class="card-content">
                <?= $content ?>
            </div>

            <?php if (!empty($footer)): ?>
                <div class="card-footer mt-4 pt-4 border-t border-gray-200">
                    <?= $footer ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
    }
}
