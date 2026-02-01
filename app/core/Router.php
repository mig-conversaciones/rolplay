<?php

namespace App\Core;

use App\Models\SystemSetting;

/**
 * Router - Sistema de enrutamiento de la aplicación
 */
class Router
{
    private array $routes = [];
    private array $middlewares = [];

    /**
     * Registra una ruta GET
     */
    public function get(string $path, string $controller, string $method): self
    {
        $this->addRoute('GET', $path, $controller, $method);
        return $this;
    }

    /**
     * Registra una ruta POST
     */
    public function post(string $path, string $controller, string $method): self
    {
        $this->addRoute('POST', $path, $controller, $method);
        return $this;
    }

    /**
     * Registra una ruta PUT
     */
    public function put(string $path, string $controller, string $method): self
    {
        $this->addRoute('PUT', $path, $controller, $method);
        return $this;
    }

    /**
     * Registra una ruta DELETE
     */
    public function delete(string $path, string $controller, string $method): self
    {
        $this->addRoute('DELETE', $path, $controller, $method);
        return $this;
    }

    /**
     * Agrega un middleware a la última ruta registrada
     */
    public function middleware(string $middleware): self
    {
        $lastRouteKey = array_key_last($this->routes);
        if ($lastRouteKey !== null) {
            $this->routes[$lastRouteKey]['middleware'] = $middleware;
        }
        return $this;
    }

    /**
     * Agrega una ruta al registro
     */
    private function addRoute(string $method, string $path, string $controller, string $action): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'middleware' => null
        ];
    }

    /**
     * Procesa la solicitud actual
     */
    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        // Remover el prefijo del proyecto (detecta si /public estÃ¡ expuesto o no)
        $basePath = self::basePath();
        if ($basePath !== '') {
            $uri = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $uri);
        }

        $uri = rtrim($uri, '/') ?: '/';

        if ($this->isMaintenanceMode($uri)) {
            $this->renderMaintenance();
            return;
        }

        foreach ($this->routes as $route) {
            $pattern = $this->convertToRegex($route['path']);

            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remover el match completo

                // Ejecutar middleware si existe
                if ($route['middleware']) {
                    $middlewareClass = "App\\Middleware\\" . $route['middleware'];
                    if (class_exists($middlewareClass)) {
                        $middleware = new $middlewareClass();
                        if (!$middleware->handle()) {
                            return;
                        }
                    }
                }

                // Ejecutar controlador
                $controllerClass = "App\\Controllers\\" . $route['controller'];
                if (!class_exists($controllerClass)) {
                    $this->notFound("Controller {$controllerClass} not found");
                    return;
                }

                $controller = new $controllerClass();
                $action = $route['action'];

                if (!method_exists($controller, $action)) {
                    $this->notFound("Method {$action} not found in controller");
                    return;
                }

                // Llamar al método del controlador con parámetros
                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        $this->notFound();
    }

    /**
     * Convierte un patrón de ruta a expresión regular
     */
    private function convertToRegex(string $path): string
    {
        // Escapar slashes
        $pattern = str_replace('/', '\/', $path);

        // Convertir parámetros {:id} a grupos de captura
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $pattern);

        return '/^' . $pattern . '$/';
    }

    /**
     * Maneja rutas no encontradas
     */
    private function notFound(string $message = "404 - Page Not Found"): void
    {
        http_response_code(404);

        if (file_exists(__DIR__ . '/../views/errors/404.php')) {
            require __DIR__ . '/../views/errors/404.php';
        } else {
            echo "<h1>404 - Page Not Found</h1>";
            if ($_ENV['APP_DEBUG'] ?? false) {
                echo "<p>{$message}</p>";
            }
        }
    }

    /**
     * Genera una URL a partir del nombre de ruta
     */
    public static function url(string $path): string
    {
        $basePath = self::basePath();
        $cleanPath = ltrim($path, '/');
        if ($basePath === '') {
            return '/' . $cleanPath;
        }
        return $basePath . '/' . $cleanPath;
    }

    /**
     * Redirecciona a una ruta
     */
    public static function redirect(string $path): void
    {
        header('Location: ' . self::url($path));
        exit;
    }

    /**
     * Genera la URL de inicio según el rol del usuario
     */
    public static function homeUrl(): string
    {
        $user = $_SESSION['user'] ?? null;

        if (!$user) {
            return self::url('/login');
        }

        $role = $user['role'] ?? 'aprendiz';

        return match ($role) {
            'admin' => self::url('/admin'),
            'instructor' => self::url('/instructor'),
            default => self::url('/scenarios'),
        };
    }

    /**
     * Calcula el prefijo base del proyecto de forma consistente
     */
    private static function basePath(): string
    {
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptDir = rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

        $basePath = $scriptDir;
        if ($basePath !== '' && str_ends_with($basePath, '/public')) {
            $withoutPublic = rtrim(substr($basePath, 0, -strlen('/public')), '/');
            $startsWithBase = (strpos($uri, $basePath . '/') === 0) || ($uri === $basePath);
            if (!$startsWithBase) {
                $basePath = $withoutPublic;
            }
        }

        return $basePath;
    }

    private function isMaintenanceMode(string $uri): bool
    {
        $maintenance = SystemSetting::get('maintenance_mode', '0') === '1';
        if (!$maintenance) {
            return false;
        }

        $user = $_SESSION['user'] ?? null;
        if ($user && ($user['role'] ?? '') === 'admin') {
            return false;
        }

        $allowlist = [
            '/login',
            '/logout',
            '/register',
            '/admin',
            '/admin/settings',
            '/admin/settings/action',
            '/admin/settings/save',
            '/admin/logs',
        ];

        return !in_array($uri, $allowlist, true);
    }

    private function renderMaintenance(): void
    {
        http_response_code(503);
        $maintenanceView = __DIR__ . '/../views/errors/maintenance.php';
        if (file_exists($maintenanceView)) {
            require $maintenanceView;
            return;
        }

        echo '<h1>Mantenimiento</h1><p>El sistema está en mantenimiento. Intenta más tarde.</p>';
    }
}
