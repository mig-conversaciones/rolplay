<?php

declare(strict_types=1);

use App\Core\Router;

$stats = $stats ?? [
    'total_users' => 0,
    'total_scenarios' => 0,
    'total_sessions' => 0,
    'total_achievements' => 0,
    'users_by_role' => [],
    'recent_users' => [],
    'active_sessions' => 0,
    'completed_sessions' => 0,
    'dynamic_sessions' => 0,
    'avg_score' => 0,
];
?>

<!-- Header del dashboard -->
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold text-neu-text-main mb-2">
                <i class="fas fa-shield-alt text-sena-violet mr-2"></i>
                Panel de Administración
            </h1>
            <p class="text-neu-text-light">
                Gestiona usuarios, escenarios y configuración del sistema.
            </p>
        </div>
        <a href="<?= Router::homeUrl() ?>" class="neu-flat px-6 py-2 rounded-full inline-flex items-center gap-2 hover:shadow-lg transition-all duration-200">
            <i class="fas fa-home"></i> Volver al Inicio
        </a>
    </div>
</div>

<!-- KPIs principales -->
<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Usuarios totales -->
    <div class="neu-flat p-6 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-neu-text-light text-sm font-medium mb-1">Usuarios Totales</p>
                <p class="text-3xl font-bold text-sena-blue">
                    <?= number_format($stats['total_users']) ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fas fa-users text-sena-blue text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Escenarios activos -->
    <div class="neu-flat p-6 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-neu-text-light text-sm font-medium mb-1">Escenarios Activos</p>
                <p class="text-3xl font-bold text-sena-green">
                    <?= number_format($stats['total_scenarios']) ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                <i class="fas fa-briefcase text-sena-green text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Sesiones completadas -->
    <div class="neu-flat p-6 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-neu-text-light text-sm font-medium mb-1">Sesiones Totales</p>
                <p class="text-3xl font-bold text-sena-violet">
                    <?= number_format($stats['total_sessions']) ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                <i class="fas fa-play-circle text-sena-violet text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Logros configurados -->
    <div class="neu-flat p-6 transition-all duration-300 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-neu-text-light text-sm font-medium mb-1">Logros Configurados</p>
                <p class="text-3xl font-bold text-yellow-600">
                    <?= number_format($stats['total_achievements']) ?>
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                <i class="fas fa-trophy text-yellow-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Distribución por rol y Actividad -->
<div class="grid md:grid-cols-2 gap-6 mb-8">
    <div class="neu-flat p-6">
        <h3 class="text-xl font-bold text-neu-text-main mb-4">
            <i class="fas fa-user-tag text-sena-blue mr-2"></i>
            Usuarios por Rol
        </h3>
        <div class="space-y-3">
            <?php
            $roleIcons = ['admin' => 'fa-shield-alt', 'instructor' => 'fa-chalkboard-teacher', 'aprendiz' => 'fa-user-graduate'];
            $roleColors = ['admin' => 'text-sena-violet bg-purple-100', 'instructor' => 'text-sena-blue bg-blue-100', 'aprendiz' => 'text-sena-green bg-green-100'];
            $roleBgColors = ['admin' => 'bg-purple-50', 'instructor' => 'bg-blue-50', 'aprendiz' => 'bg-green-50'];
            if (!empty($stats['users_by_role'])):
                foreach ($stats['users_by_role'] as $roleData):
                    $role = $roleData['role'] ?? 'aprendiz';
                    $count = $roleData['count'] ?? 0;
            ?>
                <div class="flex items-center justify-between p-3 rounded-lg <?= $roleBgColors[$role] ?? 'bg-gray-50' ?> transition-all duration-200 hover:shadow-md">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full <?= $roleColors[$role] ?? 'text-gray-600 bg-gray-100' ?> flex items-center justify-center">
                            <i class="fas <?= $roleIcons[$role] ?? 'fa-user' ?>"></i>
                        </div>
                        <span class="font-medium capitalize"><?= htmlspecialchars($role) ?></span>
                    </div>
                    <span class="font-bold text-xl"><?= number_format($count) ?></span>
                </div>
            <?php
                endforeach;
            else:
            ?>
                <p class="text-neu-text-light text-sm text-center py-4">No hay datos disponibles</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sesiones activas -->
    <div class="neu-flat p-6">
        <h3 class="text-xl font-bold text-neu-text-main mb-4">
            <i class="fas fa-chart-pie text-sena-green mr-2"></i>
            Actividad del Sistema
        </h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 rounded-lg bg-green-50 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-100 text-sena-green flex items-center justify-center">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="font-medium">Completadas</span>
                </div>
                <span class="font-bold text-xl"><?= number_format($stats['completed_sessions'] ?? 0) ?></span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-blue-50 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-sena-blue flex items-center justify-center">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <span class="font-medium">En Progreso</span>
                </div>
                <span class="font-bold text-xl"><?= number_format($stats['active_sessions'] ?? 0) ?></span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-purple-50 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 text-sena-violet flex items-center justify-center">
                        <i class="fas fa-robot"></i>
                    </div>
                    <span class="font-medium">Dinámicas (IA)</span>
                </div>
                <span class="font-bold text-xl"><?= number_format($stats['dynamic_sessions'] ?? 0) ?></span>
            </div>
            <div class="flex items-center justify-between p-3 rounded-lg bg-yellow-50 transition-all duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                        <i class="fas fa-star"></i>
                    </div>
                    <span class="font-medium">Puntaje Promedio</span>
                </div>
                <span class="font-bold text-xl"><?= number_format($stats['avg_score'] ?? 0, 1) ?>%</span>
            </div>
        </div>
    </div>
</div>

<!-- Usuarios recientes -->
<div class="neu-flat p-6 mb-8">
    <h3 class="text-xl font-bold text-neu-text-main mb-4">
        <i class="fas fa-user-clock text-sena-violet mr-2"></i>
        Usuarios Registrados Recientemente
    </h3>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-200 bg-gray-50">
                    <th class="text-left py-3 px-4 text-neu-text-main font-semibold rounded-tl-lg">ID</th>
                    <th class="text-left py-3 px-4 text-neu-text-main font-semibold">Nombre</th>
                    <th class="text-left py-3 px-4 text-neu-text-main font-semibold">Email</th>
                    <th class="text-left py-3 px-4 text-neu-text-main font-semibold">Rol</th>
                    <th class="text-left py-3 px-4 text-neu-text-main font-semibold rounded-tr-lg">Registro</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($stats['recent_users'])): ?>
                    <?php foreach ($stats['recent_users'] as $recentUser): ?>
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150">
                            <td class="py-3 px-4 text-sm"><?= (int)$recentUser['id'] ?></td>
                            <td class="py-3 px-4 font-medium"><?= htmlspecialchars($recentUser['name']) ?></td>
                            <td class="py-3 px-4 text-sm text-neu-text-light"><?= htmlspecialchars($recentUser['email']) ?></td>
                            <td class="py-3 px-4">
                                <?php
                                $badgeColors = [
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'instructor' => 'bg-blue-100 text-blue-800',
                                    'aprendiz' => 'bg-green-100 text-green-800',
                                ];
                                $role = $recentUser['role'] ?? 'aprendiz';
                                ?>
                                <span class="px-3 py-1 text-xs font-medium rounded-full <?= $badgeColors[$role] ?? 'bg-gray-100 text-gray-800' ?>">
                                    <?= ucfirst(htmlspecialchars($role)) ?>
                                </span>
                            </td>
                            <td class="py-3 px-4 text-sm text-neu-text-light">
                                <?= date('d/m/Y H:i', strtotime($recentUser['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="py-8 text-center text-neu-text-light">
                            <i class="fas fa-inbox text-4xl mb-2 block"></i>
                            No hay usuarios registrados recientemente
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Acciones rápidas -->
<div class="neu-flat p-6">
    <h3 class="text-xl font-bold text-neu-text-main mb-6">
        <i class="fas fa-bolt text-sena-yellow mr-2"></i>
        Acciones Rápidas
    </h3>
    <div class="grid md:grid-cols-4 gap-4">
        <a href="<?= Router::url('/admin/users') ?>"
           class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-sena-blue">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-blue-100 flex items-center justify-center transition-all duration-300 group-hover:bg-sena-blue group-hover:text-white">
                <i class="fas fa-users text-2xl text-sena-blue group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Gestionar Usuarios</p>
            <p class="text-xs text-neu-text-light mt-1">Crear, editar, eliminar</p>
        </a>
        <a href="<?= Router::url('/admin/scenarios') ?>"
           class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-sena-green">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-green-100 flex items-center justify-center transition-all duration-300 group-hover:bg-sena-green group-hover:text-white">
                <i class="fas fa-briefcase text-2xl text-sena-green group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Gestionar Escenarios</p>
            <p class="text-xs text-neu-text-light mt-1">Activar, desactivar</p>
        </a>
        <a href="<?= Router::url('/achievements/manage') ?>"
           class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-yellow-500">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-yellow-100 flex items-center justify-center transition-all duration-300 group-hover:bg-yellow-500 group-hover:text-white">
                <i class="fas fa-trophy text-2xl text-yellow-600 group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Gestionar Logros</p>
            <p class="text-xs text-neu-text-light mt-1">Configurar gamificación</p>
        </a>
        <a href="<?= Router::url('/admin/settings') ?>"
           class="group neu-flat p-6 rounded-xl text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-transparent hover:border-sena-violet">
            <div class="w-16 h-16 mx-auto mb-3 rounded-full bg-purple-100 flex items-center justify-center transition-all duration-300 group-hover:bg-sena-violet group-hover:text-white">
                <i class="fas fa-cog text-2xl text-sena-violet group-hover:text-white"></i>
            </div>
            <p class="font-semibold text-neu-text-main">Configuración</p>
            <p class="text-xs text-neu-text-light mt-1">Ajustes del sistema</p>
        </a>
    </div>
</div>
