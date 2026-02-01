<?php

use App\Core\Router;

$scenarios = $scenarios ?? [];
$errors = $errors ?? [];
$old = $old ?? [];
$selectedIds = $old['scenario_ids'] ?? [];
?>

<!-- Page Header -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-plus-circle text-sena-green mr-2"></i>
                Crear Ruta
            </h1>
            <p class="text-neu-text-light">
                Define una secuencia de escenarios y asignala a fichas/grupos
            </p>
        </div>
        <a href="<?= Router::url('/instructor/routes') ?>" class="neu-flat px-4 py-2 rounded-full text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Volver
        </a>
    </div>
</div>

<!-- Formulario -->
<div class="neu-flat p-6 max-w-4xl">
    <?php if (!empty($errors)): ?>
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-exclamation-circle"></i>
                <strong>Por favor corrige los siguientes errores:</strong>
            </div>
            <ul class="list-disc list-inside text-sm">
                <?php foreach ($errors as $fieldErrors): ?>
                    <?php foreach ($fieldErrors as $message): ?>
                        <li><?= htmlspecialchars($message) ?></li>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= Router::url('/instructor/routes') ?>" class="space-y-6">
        <!-- Nombre -->
        <div>
            <label for="name" class="block text-sm font-semibold text-neu-text-main mb-2">
                Nombre de la ruta <span class="text-red-500">*</span>
            </label>
            <input
                id="name"
                name="name"
                type="text"
                required
                class="neu-input w-full"
                placeholder="Ej: Ruta de liderazgo empresarial"
                value="<?= htmlspecialchars((string) ($old['name'] ?? '')) ?>"
            >
        </div>

        <!-- Descripcion -->
        <div>
            <label for="description" class="block text-sm font-semibold text-neu-text-main mb-2">
                Descripcion
            </label>
            <input
                id="description"
                name="description"
                type="text"
                class="neu-input w-full"
                placeholder="Breve descripcion de la ruta"
                value="<?= htmlspecialchars((string) ($old['description'] ?? '')) ?>"
            >
        </div>

        <!-- Escenarios -->
        <div>
            <label class="block text-sm font-semibold text-neu-text-main mb-2">
                Escenarios <span class="text-red-500">*</span>
                <span class="font-normal text-neu-text-light">(orden segun seleccion)</span>
            </label>
            <?php if (empty($scenarios)): ?>
                <div class="neu-flat p-6 text-center">
                    <i class="fas fa-briefcase text-4xl text-neu-text-light mb-2"></i>
                    <p class="text-neu-text-light">No hay escenarios activos disponibles.</p>
                </div>
            <?php else: ?>
                <div class="neu-flat p-4 max-h-72 overflow-y-auto space-y-2">
                    <?php foreach ($scenarios as $s): ?>
                        <?php $sid = (int) ($s['id'] ?? 0); ?>
                        <label class="flex gap-3 items-center p-3 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors border border-transparent hover:border-gray-200">
                            <input
                                type="checkbox"
                                name="scenario_ids[]"
                                value="<?= $sid ?>"
                                class="w-5 h-5 text-sena-green rounded"
                                <?= in_array($sid, $selectedIds, true) ? 'checked' : '' ?>
                            >
                            <div class="flex-1">
                                <div class="font-semibold text-neu-text-main">
                                    #<?= $sid ?> <?= htmlspecialchars((string) ($s['title'] ?? 'Escenario')) ?>
                                </div>
                                <div class="text-sm text-neu-text-light">
                                    <?php if (!empty($s['area'])): ?>
                                        <span class="mr-2"><i class="fas fa-tag mr-1"></i><?= htmlspecialchars((string) $s['area']) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($s['difficulty'])): ?>
                                        <span><i class="fas fa-signal mr-1"></i><?= htmlspecialchars((string) $s['difficulty']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Fichas / Grupos -->
        <div>
            <label for="groups" class="block text-sm font-semibold text-neu-text-main mb-2">
                Fichas / Grupos
                <span class="font-normal text-neu-text-light">(separados por coma o espacio)</span>
            </label>
            <input
                id="groups"
                name="groups"
                type="text"
                class="neu-input w-full"
                placeholder="Ej: 2557214, 2557215"
                value="<?= htmlspecialchars((string) ($old['groups'] ?? '')) ?>"
            >
        </div>

        <!-- Fechas -->
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-semibold text-neu-text-main mb-2">
                    Fecha inicio
                </label>
                <input
                    id="start_date"
                    name="start_date"
                    type="date"
                    class="neu-input w-full"
                    value="<?= htmlspecialchars((string) ($old['start_date'] ?? '')) ?>"
                >
            </div>
            <div>
                <label for="end_date" class="block text-sm font-semibold text-neu-text-main mb-2">
                    Fecha fin
                </label>
                <input
                    id="end_date"
                    name="end_date"
                    type="date"
                    class="neu-input w-full"
                    value="<?= htmlspecialchars((string) ($old['end_date'] ?? '')) ?>"
                >
            </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3 pt-4">
            <button type="submit" class="neu-btn-primary px-6 py-3 rounded-full">
                <i class="fas fa-save mr-2"></i> Guardar Ruta
            </button>
            <a href="<?= Router::url('/instructor/routes') ?>" class="neu-flat px-6 py-3 rounded-full">
                Cancelar
            </a>
        </div>
    </form>
</div>
