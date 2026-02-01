<?php

declare(strict_types=1);

use App\Core\Router;

$achievement = $achievement ?? null;
$errors = $errors ?? [];
$old = $old ?? [];
?>

<?php if (!$achievement): ?>
    <div class="neu-flat p-12 text-center">
        <i class="fas fa-trophy-slash text-gray-300 text-6xl mb-4"></i>
        <h2 class="text-2xl font-bold text-neu-text-main mb-2">Logro no encontrado</h2>
        <p class="text-neu-text-light mb-6">El logro que intentas editar no existe.</p>
        <a href="<?= Router::url('/achievements/manage') ?>" class="neu-btn-primary px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Volver a Logros
        </a>
    </div>
<?php else: ?>

<!-- Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-edit text-sena-blue mr-2"></i>
                Editar Logro
            </h1>
            <p class="text-neu-text-light">
                Modifica la información del logro: <strong><?= htmlspecialchars($achievement['name']) ?></strong>
            </p>
        </div>
        <a href="<?= Router::url('/achievements/manage') ?>" class="neu-flat px-4 py-2 rounded-full">
            <i class="fas fa-arrow-left"></i> Volver a Logros
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="neu-flat p-8 max-w-4xl mx-auto">
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

    <!-- Información de registro -->
    <div class="neu-flat p-4 mb-6">
        <div class="grid md:grid-cols-2 gap-4 text-sm text-neu-text-light">
            <div>
                <span class="font-semibold">ID del Logro:</span>
                <span class="ml-2"><?= (int)$achievement['id'] ?></span>
            </div>
            <div>
                <span class="font-semibold">Fecha de Creación:</span>
                <span class="ml-2"><?= date('d/m/Y H:i', strtotime($achievement['created_at'])) ?></span>
            </div>
        </div>
    </div>

    <form method="POST" action="<?= Router::url('/achievements/' . $achievement['id']) ?>" class="space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <!-- Título -->
            <div class="md:col-span-2">
                <label for="name" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-heading mr-1 text-sena-blue"></i>
                    Título del Logro <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="name"
                       name="name"
                       value="<?= htmlspecialchars($old['name'] ?? $achievement['name']) ?>"
                       required
                       maxlength="100"
                       placeholder="Ej: Primer Paso"
                       class="neu-input w-full">
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-500 text-sm mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($errors['name'][0]) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Descripción -->
            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-align-left mr-1 text-sena-blue"></i>
                    Descripción <span class="text-red-500">*</span>
                </label>
                <textarea id="description"
                          name="description"
                          required
                          rows="3"
                          placeholder="Ej: Completa tu primera simulación de escenario laboral."
                          class="neu-input w-full"><?= htmlspecialchars($old['description'] ?? $achievement['description']) ?></textarea>
                <?php if (isset($errors['description'])): ?>
                    <p class="text-red-500 text-sm mt-1">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        <?= htmlspecialchars($errors['description'][0]) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Icono (Font Awesome) -->
            <div>
                <label for="icon" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-icons mr-1 text-sena-blue"></i>
                    Icono (Font Awesome) <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="icon"
                       name="icon"
                       value="<?= htmlspecialchars($old['icon'] ?? $achievement['icon']) ?>"
                       required
                       placeholder="Ej: fa-trophy"
                       class="neu-input w-full">
                <p class="text-neu-text-light text-sm mt-1">
                    <i class="<?= htmlspecialchars($achievement['icon']) ?> mr-1"></i>
                    Icono actual: <?= htmlspecialchars($achievement['icon']) ?>
                </p>
            </div>

            <!-- Categoría -->
            <div>
                <label for="category" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-tags mr-1 text-sena-blue"></i>
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select id="category"
                        name="category"
                        required
                        class="neu-input w-full">
                    <option value="">Seleccione una categoría...</option>
                    <option value="progreso" <?= ($old['category'] ?? $achievement['category']) === 'progreso' ? 'selected' : '' ?>>Progreso</option>
                    <option value="excelencia" <?= ($old['category'] ?? $achievement['category']) === 'excelencia' ? 'selected' : '' ?>>Excelencia</option>
                    <option value="social" <?= ($old['category'] ?? $achievement['category']) === 'social' ? 'selected' : '' ?>>Social</option>
                    <option value="especial" <?= ($old['category'] ?? $achievement['category']) === 'especial' ? 'selected' : '' ?>>Especial</option>
                    <option value="general" <?= ($old['category'] ?? $achievement['category']) === 'general' ? 'selected' : '' ?>>General</option>
                </select>
            </div>

            <!-- Puntos -->
            <div>
                <label for="points" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-coins mr-1 text-sena-blue"></i>
                    Puntos <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="points"
                       name="points"
                       value="<?= htmlspecialchars($old['points'] ?? $achievement['points']) ?>"
                       required
                       min="0"
                       max="1000"
                       placeholder="Ej: 10"
                       class="neu-input w-full">
            </div>

            <!-- Tipo de Requisito -->
            <div>
                <label for="requirement_type" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-tasks mr-1 text-sena-blue"></i>
                    Tipo de Requisito <span class="text-red-500">*</span>
                </label>
                <select id="requirement_type"
                        name="requirement_type"
                        required
                        class="neu-input w-full">
                    <option value="">Seleccione un tipo...</option>
                    <option value="sessions_completed" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'sessions_completed' ? 'selected' : '' ?>>Sesiones Completadas</option>
                    <option value="avg_score" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'avg_score' ? 'selected' : '' ?>>Promedio de Puntuación</option>
                    <option value="competence_comunicacion" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'competence_comunicacion' ? 'selected' : '' ?>>Competencia: Comunicación</option>
                    <option value="competence_liderazgo" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'competence_liderazgo' ? 'selected' : '' ?>>Competencia: Liderazgo</option>
                    <option value="competence_trabajo_equipo" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'competence_trabajo_equipo' ? 'selected' : '' ?>>Competencia: Trabajo en Equipo</option>
                    <option value="competence_toma_decisiones" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'competence_toma_decisiones' ? 'selected' : '' ?>>Competencia: Toma de Decisiones</option>
                    <option value="all_competences" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'all_competences' ? 'selected' : '' ?>>Todas las Competencias</option>
                    <option value="streak" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'streak' ? 'selected' : '' ?>>Racha Consecutiva</option>
                    <option value="areas_explored" <?= ($old['requirement_type'] ?? $achievement['requirement_type']) === 'areas_explored' ? 'selected' : '' ?>>Áreas Exploradas</option>
                </select>
            </div>

            <!-- Valor del Requisito -->
            <div>
                <label for="requirement_value" class="block text-sm font-semibold text-neu-text-main mb-2">
                    <i class="fas fa-hashtag mr-1 text-sena-blue"></i>
                    Valor del Requisito <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="requirement_value"
                       name="requirement_value"
                       value="<?= htmlspecialchars($old['requirement_value'] ?? $achievement['requirement_value']) ?>"
                       required
                       min="1"
                       placeholder="Ej: 1"
                       class="neu-input w-full">
            </div>

            <!-- Estado -->
            <div class="md:col-span-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           <?= ($old['is_active'] ?? $achievement['is_active'] ?? 1) == 1 ? 'checked' : '' ?>
                           class="w-5 h-5 text-sena-green border-gray-300 rounded focus:ring-sena-green">
                    <span class="text-neu-text-main font-medium">
                        <i class="fas fa-check-circle text-sena-green mr-1"></i>
                        Logro activo (visible para usuarios)
                    </span>
                </label>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-4 pt-6 border-t border-neu-shadow-dark">
            <button type="submit"
                    class="flex-1 neu-btn-primary px-6 py-3 rounded-full font-bold">
                <i class="fas fa-save mr-2"></i>
                Guardar Cambios
            </button>
            <a href="<?= Router::url('/achievements/manage') ?>"
               class="flex-1 neu-flat px-6 py-3 rounded-full font-bold text-center">
                <i class="fas fa-times mr-2"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<!-- Advertencia -->
<div class="neu-flat p-6 mt-8 max-w-4xl mx-auto">
    <h3 class="text-lg font-bold text-neu-text-main mb-2">
        <i class="fas fa-exclamation-triangle text-sena-yellow mr-2"></i>
        Advertencia
    </h3>
    <p class="text-neu-text-light">
        Modificar los requisitos de un logro ya desbloqueado por usuarios no afectará los logros ya otorgados.
        Solo afectará futuros desbloqueos.
    </p>
</div>

<?php endif; ?>
