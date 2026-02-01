<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

final class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index', [
            'appName' => $_ENV['APP_NAME'] ?? 'RolPlay EDU',
            'user' => $this->getAuthUser(),
        ]);
    }
}