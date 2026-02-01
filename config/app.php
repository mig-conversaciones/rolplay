<?php

declare(strict_types=1);

return [
    'name' => $_ENV['APP_NAME'] ?? 'RolPlay EDU',
    'env' => $_ENV['APP_ENV'] ?? 'development',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOL),
    'url' => $_ENV['APP_URL'] ?? 'http://localhost/rolplay/online-version/public',
    'timezone' => $_ENV['APP_TIMEZONE'] ?? 'America/Bogota',
    'locale' => $_ENV['APP_LOCALE'] ?? 'es_CO',
];