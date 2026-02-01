<?php

use App\Core\Router;

// Determinar a dónde redirigir según el rol del usuario
$user = $_SESSION['user'] ?? null;
$homeUrl = '/';

if ($user) {
    $role = $user['role'] ?? 'aprendiz';
    if ($role === 'admin') {
        $homeUrl = '/admin';
    } elseif ($role === 'instructor') {
        $homeUrl = '/instructor';
    } else {
        $homeUrl = '/scenarios';
    }
}
?>

<div class="neu-flat p-12 text-center max-w-lg mx-auto mt-8">
    <div class="text-6xl mb-4">
        <i class="fas fa-exclamation-triangle text-yellow-500"></i>
    </div>
    <h1 class="text-4xl font-bold text-neu-text-main mb-2">404</h1>
    <p class="text-neu-text-light mb-6">La ruta solicitada no existe.</p>
    <a class="neu-btn-primary px-6 py-3 rounded-full inline-block" href="<?= Router::url($homeUrl) ?>">
        <i class="fas fa-home mr-2"></i> Volver al inicio
    </a>
</div>
