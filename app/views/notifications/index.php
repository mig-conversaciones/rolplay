<?php

$notifications = $notifications ?? [];

$typeIcons = [
    'achievement' => 'fa-trophy',
    'route' => 'fa-route',
    'instructor' => 'fa-chalkboard-teacher',
    'system' => 'fa-bell',
];
?>

<section class="neu-flat p-6">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <h2 class="text-2xl font-bold text-neu-text-main m-0">Notificaciones</h2>
        <div class="flex items-center gap-2">
            <a class="neu-flat px-4 py-2 rounded-full text-sm" href="<?= \App\Core\Router::url('/notifications') ?>">
                <i class="fas fa-inbox mr-1"></i> No leídas
            </a>
            <a class="neu-flat px-4 py-2 rounded-full text-sm" href="<?= \App\Core\Router::url('/notifications?all=1') ?>">
                <i class="fas fa-layer-group mr-1"></i> Ver todas
            </a>
            <form method="post" action="<?= \App\Core\Router::url('/notifications/read-all') ?>" class="m-0">
                <button type="submit" class="neu-flat px-4 py-2 rounded-full text-sm">
                    <i class="fas fa-check-double mr-1"></i> Marcar todas como leídas
                </button>
            </form>
        </div>
    </div>
</section>

<section class="neu-flat p-6 mt-4">
    <?php if (empty($notifications)): ?>
        <div class="text-neu-text-light text-sm">No tienes notificaciones por ahora.</div>
    <?php else: ?>
        <div class="grid gap-3">
            <?php foreach ($notifications as $notification): ?>
                <?php
                $icon = $typeIcons[$notification['type'] ?? 'system'] ?? 'fa-bell';
                $isRead = (int) ($notification['is_read'] ?? 0) === 1;
                $link = trim((string) ($notification['link'] ?? ''));
                ?>
                <div class="neu-flat p-4 <?= $isRead ? 'opacity-80' : '' ?>">
                    <div class="flex justify-between gap-3 flex-wrap items-start">
                        <div class="flex gap-3">
                            <div class="text-sena-green text-lg"><i class="fas <?= $icon ?>"></i></div>
                            <div>
                                <div class="font-semibold text-neu-text-main"><?= htmlspecialchars((string) ($notification['title'] ?? 'Notificación')) ?></div>
                                <div class="text-sm text-neu-text-light mt-1"><?= htmlspecialchars((string) ($notification['message'] ?? '')) ?></div>
                                <?php if (!empty($notification['created_at'])): ?>
                                    <div class="text-xs text-gray-500 mt-2">
                                        <?= htmlspecialchars((string) $notification['created_at']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <?php if ($link !== ''): ?>
                                <a class="neu-flat px-3 py-1 rounded-full text-xs" href="<?= \App\Core\Router::url($link) ?>">
                                    Ver
                                </a>
                            <?php endif; ?>
                            <?php if (!$isRead): ?>
                                <form method="post" action="<?= \App\Core\Router::url('/notifications/' . (int) ($notification['id'] ?? 0) . '/read') ?>" class="m-0">
                                    <button type="submit" class="neu-btn-primary px-3 py-1 rounded-full text-xs">
                                        Marcar leída
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-xs text-gray-500">Leída</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
