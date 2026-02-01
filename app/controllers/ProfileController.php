<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\GameSession;

final class ProfileController extends Controller
{
    public function show(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $sessionModel = new GameSession();
        $stats = $sessionModel->aggregateStatsByUser((int) $user['id']);
        $history = $sessionModel->historyByUser((int) $user['id'], 30);

        $this->view('profile/show', [
            'title' => 'Mi perfil',
            'user' => $user,
            'stats' => $stats,
            'history' => $history,
        ]);
    }
}