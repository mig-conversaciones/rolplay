<?php

namespace App\Middleware;

class CsrfMiddleware
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $_POST['_token'])) {
                http_response_code(419);
                echo '<h1>419 - Page Expired</h1><p>Invalid CSRF Token. Please refresh the page and try again.</p>';
                return false;
            }
        }
        return true;
    }
}
