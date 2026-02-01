<?php

namespace App\Core;

/**
 * Controller - Controlador base para todos los controladores
 */
abstract class Controller
{
    /**
     * Renderiza una vista
     */
    protected function view(string $view, array $data = [], ?string $layout = 'main'): void
    {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
        }

        // Extraer variables para la vista
        if (session_status() === PHP_SESSION_ACTIVE) {
            if (empty($_SESSION['csrf_token'])) {
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            }
            $data['csrf_token'] = $_SESSION['csrf_token'];
        }
        extract($data);

        // Capturar contenido de la vista
        ob_start();
        $viewPath = __DIR__ . "/../views/{$view}.php";

        if (!file_exists($viewPath)) {
            die("View {$view} not found");
        }

        require $viewPath;
        $content = ob_get_clean();

        // Si hay layout, renderizar con layout
        if ($layout) {
            $layoutPath = __DIR__ . "/../views/layouts/{$layout}.php";
            if (file_exists($layoutPath)) {
                require $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    /**
     * Retorna una respuesta JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Retorna un error JSON
     */
    protected function jsonError(string $message, int $statusCode = 400, array $errors = []): void
    {
        $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }

    /**
     * Retorna éxito JSON
     */
    protected function jsonSuccess(string $message, array $data = [], int $statusCode = 200): void
    {
        $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Redirecciona a una ruta
     */
    protected function redirect(string $path): void
    {
        Router::redirect($path);
    }

    /**
     * Obtiene el usuario autenticado actual
     */
    protected function getAuthUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Verifica si el usuario está autenticado
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Verifica si el usuario tiene un rol específico
     */
    protected function hasRole(string $role): bool
    {
        $user = $this->getAuthUser();
        return $user && $user['role'] === $role;
    }

    /**
     * Valida datos de entrada
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $rules = explode('|', $ruleString);

            foreach ($rules as $rule) {
                $value = $data[$field] ?? null;

                // Regla: required
                if ($rule === 'required' && empty($value)) {
                    $errors[$field][] = "El campo {$field} es requerido";
                }

                // Regla: email
                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "El campo {$field} debe ser un email válido";
                }

                // Regla: min:X
                if (preg_match('/^min:(\d+)$/', $rule, $matches)) {
                    $min = (int) $matches[1];
                    if (!empty($value) && strlen($value) < $min) {
                        $errors[$field][] = "El campo {$field} debe tener al menos {$min} caracteres";
                    }
                }

                // Regla: max:X
                if (preg_match('/^max:(\d+)$/', $rule, $matches)) {
                    $max = (int) $matches[1];
                    if (!empty($value) && strlen($value) > $max) {
                        $errors[$field][] = "El campo {$field} no debe exceder {$max} caracteres";
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Sanitiza una cadena
     */
    protected function sanitize(string $input): string
    {
        return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
    }
}
