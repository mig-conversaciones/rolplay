<?php

declare(strict_types=1);

use App\Core\Router;

$errors = $errors ?? [];
$old = $old ?? [];
?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                <i class="fas fa-plus-circle text-sena-green mr-2"></i>
                Crear Nuevo Logro
            </h1>
            <p class="text-gray-600">
                Completa el formulario para crear un nuevo logro en el sistema de gamificación.
            </p>
        </div>
        <a href="<?= Router::url('/achievements/manage') ?>" class="btn-secondary">
            <i class="fas fa-arrow-left"></i> Volver a Logros
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="bg-white rounded-xl shadow-lg p-8 max-w-4xl mx-auto">
    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-2xl mr-3 mt-1"></i>
                <div>
                    <p class="font-bold mb-2">Se encontraron errores en el formulario:</p>
                    <ul class="list-disc list-inside space-y-1">
                        <?php foreach ($errors as $fieldErrors): ?>
                            <?php foreach ($fieldErrors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= Router::url('/achievements') ?>" class="space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-heading mr-1 text-sena-blue"></i>
                    Título del Logro <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                       required
                       maxlength="100"
                       placeholder="Ej: Primer Paso"
                       class="w-full px-4 py-3 border <?= isset($errors['name']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300">
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-500 text-sm mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($errors['name'][0]) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-1 text-sena-blue"></i>
                    Descripción <span class="text-red-500">*</span>
                </label>
                <textarea id="description"
                          name="description"
                          required
                          rows="3"
                          placeholder="Ej: Completa tu primera simulación de escenario laboral."
                          class="w-full px-4 py-3 border <?= isset($errors['description']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <p class="text-red-500 text-sm mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($errors['description'][0]) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Icono (Font Awesome) -->
            <div>
                <label for="icon" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-icons mr-1 text-sena-blue"></i>
                    Icono (Font Awesome) <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="icon"
                       name="icon"
                       value="<?= htmlspecialchars($old['icon'] ?? 'fa-trophy') ?>"
                       required
                       placeholder="Ej: fa-trophy"
                       class="w-full px-4 py-3 border <?= isset($errors['icon']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300">
                <p class="text-gray-500 text-sm mt-1">
                    Ver iconos en: <a href="https://fontawesome.com/icons" target="_blank" class="text-sena-blue hover:underline">fontawesome.com/icons</a>
                </p>
            </div>

            <!-- Categoría -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tags mr-1 text-sena-blue"></i>
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select id="category"
                        name="category"
                        required
                        class="w-full px-4 py-3 border <?= isset($errors['category']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300">
                    <option value="">Seleccione una categoría...</option>
                    <option value="progreso" <?= ($old['category'] ?? '') === 'progreso' ? 'selected' : '' ?>>Progreso</option>
                    <option value="excelencia" <?= ($old['category'] ?? '') === 'excelencia' ? 'selected' : '' ?>>Excelencia</option>
                    <option value="social" <?= ($old['category'] ?? '') === 'social' ? 'selected' : '' ?>>Social</option>
                    <option value="especial" <?= ($old['category'] ?? '') === 'especial' ? 'selected' : '' ?>>Especial</option>
                    <option value="general" <?= ($old['category'] ?? '') === 'general' ? 'selected' : '' ?>>General</option>
                </select>
            </div>

            <!-- Puntos -->
            <div>
                <label for="points" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-coins mr-1 text-sena-blue"></i>
                    Puntos <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="points"
                       name="points"
                       value="<?= htmlspecialchars($old['points'] ?? '10') ?>"
                       required
                       min="0"
                       max="1000"
                       placeholder="Ej: 10"
                       class="w-full px-4 py-3 border <?= isset($errors['points']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300">
            </div>

            <!-- Tipo de Requisito -->
            <div>
                <label for="requirement_type" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-tasks mr-1 text-sena-blue"></i>
                    Tipo de Requisito <span class="text-red-500">*</span>
                </label>
                <select id="requirement_type"
                        name="requirement_type"
                        required
                        class="w-full px-4 py-3 border <?= isset($errors['requirement_type']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300">
                    <option value="">Seleccione un tipo...</option>
                    <option value="sessions_completed" <?= ($old['requirement_type'] ?? '') === 'sessions_completed' ? 'selected' : '' ?>>Sesiones Completadas</option>
                    <option value="avg_score" <?= ($old['requirement_type'] ?? '') === 'avg_score' ? 'selected' : '' ?>>Promedio de Puntuación</option>
                    <option value="competence_comunicacion" <?= ($old['requirement_type'] ?? '') === 'competence_comunicacion' ? 'selected' : '' ?>>Competencia: Comunicación</option>
                    <option value="competence_liderazgo" <?= ($old['requirement_type'] ?? '') === 'competence_liderazgo' ? 'selected' : '' ?>>Competencia: Liderazgo</option>
                    <option value="competence_trabajo_equipo" <?= ($old['requirement_type'] ?? '') === 'competence_trabajo_equipo' ? 'selected' : '' ?>>Competencia: Trabajo en Equipo</option>
                    <option value="competence_toma_decisiones" <?= ($old['requirement_type'] ?? '') === 'competence_toma_decisiones' ? 'selected' : '' ?>>Competencia: Toma de Decisiones</option>
                    <option value="all_competences" <?= ($old['requirement_type'] ?? '') === 'all_competences' ? 'selected' : '' ?>>Todas las Competencias</option>
                    <option value="streak" <?= ($old['requirement_type'] ?? '') === 'streak' ? 'selected' : '' ?>>Racha Consecutiva</option>
                    <option value="areas_explored" <?= ($old['requirement_type'] ?? '') === 'areas_explored' ? 'selected' : '' ?>>Áreas Exploradas</option>
                </select>
            </div>

            <!-- Valor del Requisito -->
            <div>
                <label for="requirement_value" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-hashtag mr-1 text-sena-blue"></i>
                    Valor del Requisito <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="requirement_value"
                       name="requirement_value"
                       value="<?= htmlspecialchars($old['requirement_value'] ?? '1') ?>"
                       required
                       min="1"
                       placeholder="Ej: 1"
                       class="w-full px-4 py-3 border <?= isset($errors['requirement_value']) ? 'border-red-500' : 'border-gray-300' ?> rounded-lg focus:ring-2 focus:ring-sena-yellow focus:border-sena-yellow transition duration-300">
                <p class="text-gray-500 text-sm mt-1">
                    Ejemplo: Si el tipo es "Sesiones Completadas" y el valor es "5", se desbloqueará al completar 5 sesiones.
                </p>
            </div>

            <!-- Estado -->
            <div class="md:col-span-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           <?= ($old['is_active'] ?? '1') === '1' ? 'checked' : '' ?>
                           class="w-5 h-5 text-sena-green border-gray-300 rounded focus:ring-sena-green">
                    <span class="text-gray-700 font-medium">
                        <i class="fas fa-check-circle text-sena-green mr-1"></i>
                        Logro activo (visible para usuarios)
                    </span>
                </label>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-4 pt-6 border-t">
            <button type="submit"
                    class="flex-1 bg-sena-green text-white px-6 py-3 rounded-lg font-bold hover:bg-sena-green-dark transition duration-300 shadow-lg">
                <i class="fas fa-save mr-2"></i>
                Crear Logro
            </button>
            <a href="<?= Router::url('/achievements/manage') ?>"
               class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-bold hover:bg-gray-300 transition duration-300 text-center">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Info adicional -->
<div class="bg-blue-50 border-l-4 border-sena-blue rounded-lg p-6 mt-8 max-w-4xl mx-auto">
    <h3 class="text-lg font-bold text-sena-blue mb-2">
        <i class="fas fa-lightbulb mr-2"></i>
        Consejos para Crear Logros
    </h3>
    <ul class="text-gray-700 space-y-2">
        <li><i class="fas fa-check text-sena-green mr-2"></i>Los logros deben ser alcanzables pero retadores.</li>
        <li><i class="fas fa-check text-sena-green mr-2"></i>Usa nombres descriptivos y motivadores.</li>
        <li><i class="fas fa-check text-sena-green mr-2"></i>Distribuye los puntos proporcionalmente a la dificultad.</li>
        <li><i class="fas fa-check text-sena-green mr-2"></i>El sistema verificará y desbloqueará logros automáticamente al completar sesiones.</li>
    </ul>
</div>
