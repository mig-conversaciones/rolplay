<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

final class NotificationController extends Controller
{
    public function index(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $model = new Notification();
        $showAll = (int) ($_GET['all'] ?? 0) === 1;
        $notifications = $showAll
            ? $model->listByUser((int) $user['id'])
            : $model->listUnreadByUser((int) $user['id']);

        $this->view('notifications/index', [
            'title' => 'Notificaciones',
            'notifications' => $notifications,
            'showAll' => $showAll,
        ]);
    }

    public function markRead(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $notificationId = (int) $id;
        if ($notificationId <= 0) {
            $this->redirect('/notifications');
            return;
        }

        $model = new Notification();
        $model->markRead($notificationId, (int) $user['id']);

        $this->redirect('/notifications');
    }

    public function markAllRead(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $model = new Notification();
        $model->markAllRead((int) $user['id']);

        $this->redirect('/notifications');
    }
}
