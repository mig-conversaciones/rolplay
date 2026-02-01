<?php

declare(strict_types=1);

use App\Core\Env;
use App\Core\Router;

// Basic error handling for development
// Basic error handling
$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
error_reporting($debug ? E_ALL : 0);
ini_set('display_errors', $debug ? '1' : '0');
ini_set('default_charset', 'UTF-8');

if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

// Ensure session is available early
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$root = dirname(__DIR__);

// 1) Autoload (Composer if available, otherwise local autoloader)
$composerAutoload = $root . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require $composerAutoload;
} else {
    require $root . '/app/core/Autoload.php';
}

// 2) Load environment variables (.env)
Env::load($root . '/.env');

// 3) Load configuration files (best-effort)
$configDir = $root . '/config';
if (is_dir($configDir)) {
    foreach (glob($configDir . '/*.php') ?: [] as $configFile) {
        $configKey = basename($configFile, '.php');
        $configValue = require $configFile;
        if (!isset($GLOBALS['config']) || !is_array($GLOBALS['config'])) {
            $GLOBALS['config'] = [];
        }
        $GLOBALS['config'][$configKey] = $configValue;
    }
}

// 3.1) Apply basic runtime config
$appConfig = $GLOBALS['config']['app'] ?? [];
$timezone = $appConfig['timezone'] ?? ($_ENV['APP_TIMEZONE'] ?? 'UTC');
date_default_timezone_set($timezone);

// 4) Define routes and dispatch
$router = new Router();
require $root . '/app/routes.php';
$router->dispatch();
