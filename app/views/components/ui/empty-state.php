<?php

declare(strict_types=1);

use App\Core\Router;

/**
 * Empty State Component - Componente para mostrar estado vacÃ­o
 *
 * Ejemplo de uso:
 *
 * <?php
 * renderEmptyState([
 *     'icon' => 'fa-clipboard-list',
 *     'title' => 'Sin programas cargados',
 *     'message' => 'AÃºn no has cargado ningÃºn programa. Sube tu primer informacion del programa para comenzar.',
 *     'actionText' => 'Cargar Programa',
 *     'actionUrl' => Router::url('/instructor/programs/create')
 * ]);
 * ?>
 *
 * @param array $props Propiedades del componente:
 *   - icon (string): Clase de Font Awesome (requerido)
 *   - title (string): TÃ­tulo del estado vacÃ­o (requerido)
 *   - message (string): Mensaje descriptivo (requerido)
 *   - actionText (string): Texto del botÃ³n de acciÃ³n opcional
 *   - actionUrl (string): URL del botÃ³n de acciÃ³n opcional
 *   - secondaryActionText (string): Texto del botÃ³n secundario opcional
 *   - secondaryActionUrl (string): URL del botÃ³n secundario opcional
 */

if (!function_exists('renderEmptyState')) {
    function renderEmptyState(array $props): void
    {
        if (empty($props['icon']) || empty($props['title']) || empty($props['message'])) {
            return;
        }

        $icon = $props['icon'];
        $title = $props['title'];
        $message = $props['message'];
        $actionText = $props['actionText'] ?? '';
        $actionUrl = $props['actionUrl'] ?? '';
        $secondaryActionText = $props['secondaryActionText'] ?? '';
        $secondaryActionUrl = $props['secondaryActionUrl'] ?? '';
        ?>

        <div class="text-center py-16 px-4 animate-fade-in">
            <!-- Ãcono grande -->
            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-gray-50 to-gray-100 mb-6 animate-scale-in">
                <i class="fas <?= htmlspecialchars($icon) ?> text-5xl text-gray-400"></i>
            </div>

            <!-- TÃ­tulo -->
            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                <?= htmlspecialchars($title) ?>
            </h3>

            <!-- Mensaje -->
            <p class="text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                <?= htmlspecialchars($message) ?>
            </p>

            <!-- Acciones -->
            <?php if (!empty($actionText) && !empty($actionUrl)): ?>
                <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                    <a href="<?= htmlspecialchars($actionUrl) ?>" class="btn-primary">
                        <i class="fas fa-plus mr-2"></i>
                        <?= htmlspecialchars($actionText) ?>
                    </a>

                    <?php if (!empty($secondaryActionText) && !empty($secondaryActionUrl)): ?>
                        <a href="<?= htmlspecialchars($secondaryActionUrl) ?>" class="btn-secondary">
                            <?= htmlspecialchars($secondaryActionText) ?>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php
    }
}
