<?php

declare(strict_types=1);

// Include Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Lightweight PSR-4-ish autoloader for local development without Composer.
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $relativePath = str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';
    $baseDir = dirname(__DIR__);
    $file = $baseDir . DIRECTORY_SEPARATOR . $relativePath;

    if (file_exists($file)) {
        require $file;
    }
});