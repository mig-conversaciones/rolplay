<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;

use App\Models\User;

use App\Models\Scenario;

use App\Models\Achievement;

use App\Models\GameSession;

use App\Models\SystemSetting;

use App\Core\Database;

use PDOException;

/**

 * Controlador de AdministraciÃ³n

 * RF-015: GestiÃ³n de Usuarios

 * RF-016: GestiÃ³n de Contenidos

 * RF-017: ConfiguraciÃ³n del Sistema

 */

final class AdminController extends Controller

{

    /**

     * Dashboard de administraciÃ³n

     */

    public function dashboard(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $userModel = new User();

        $scenarioModel = new Scenario();

        $achievementModel = new Achievement();

        $sessionModel = new GameSession();

        // Obtener estadÃ­sticas de sesiones usando el mÃ©todo del modelo

        $sessionStats = $sessionModel->getGlobalStats();

        // Obtener usuarios por rol usando el mÃ©todo del modelo

        $usersByRole = $userModel->getUsersByRole();

        // Obtener usuarios recientes usando el mÃ©todo del modelo

        $recentUsers = $userModel->getRecentUsers(10);

        // EstadÃ­sticas generales

        $stats = [

            'total_users' => count($userModel->findAll()),

            'total_aprendices' => count($userModel->findByRole('aprendiz')),

            'total_instructores' => count($userModel->findByRole('instructor')),

            'total_admins' => count($userModel->findByRole('admin')),

            'total_scenarios' => count($scenarioModel->listActive()),

            'total_achievements' => $achievementModel->getStats()['total_achievements'] ?? 0,

            'total_sessions' => $sessionStats['total_sessions'],

            'completed_sessions' => $sessionStats['completed_sessions'],

            'active_sessions' => $sessionStats['active_sessions'],

            'dynamic_sessions' => $sessionStats['dynamic_sessions'],

            'avg_score' => $sessionStats['avg_score'],

            'users_by_role' => $usersByRole,

            'recent_users' => $recentUsers,

        ];

        $this->view('admin/dashboard', [

            'title' => 'Panel de AdministraciÃ³n',

            'user' => $user,

            'stats' => $stats,

        ]);

    }

    /**

     * Lista todos los usuarios

     */

    public function users(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $userModel = new User();

        $users = $userModel->findAll();

        $this->view('admin/users/index', [

            'title' => 'GestiÃ³n de Usuarios',

            'user' => $user,

            'users' => $users,

        ]);

    }

    /**

     * Formulario para crear nuevo usuario

     */

    public function createUser(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $this->view('admin/users/create', [

            'title' => 'Crear Usuario',

            'user' => $user,

            'errors' => [],

            'old' => [],

        ]);

    }

    /**

     * Guarda un nuevo usuario

     */

    public function storeUser(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $name = $this->sanitize((string)($_POST['name'] ?? ''));

        $email = $this->sanitize((string)($_POST['email'] ?? ''));

        $password = (string)($_POST['password'] ?? '');

        $role = $this->sanitize((string)($_POST['role'] ?? 'aprendiz'));

        $ficha = $this->sanitize((string)($_POST['ficha'] ?? ''));

        $errors = $this->validate([

            'name' => $name,

            'email' => $email,

            'password' => $password,

            'role' => $role,

        ], [

            'name' => 'required|min:3|max:100',

            'email' => 'required|email',

            'password' => 'required|min:6',

            'role' => 'required',

        ]);

        // Validar que el rol sea vÃ¡lido

        if (!in_array($role, ['aprendiz', 'instructor', 'admin'], true)) {

            $errors['role'][] = 'Rol invÃ¡lido';

        }

        if (!empty($errors)) {

            $this->view('admin/users/create', [

                'title' => 'Crear Usuario',

                'user' => $user,

                'errors' => $errors,

                'old' => $_POST,

            ]);

            return;

        }

        $userModel = new User();

        // Verificar si el email ya existe

        if ($userModel->findByEmail($email)) {

            $errors['email'][] = 'El email ya estÃ¡ registrado';

            $this->view('admin/users/create', [

                'title' => 'Crear Usuario',

                'user' => $user,

                'errors' => $errors,

                'old' => $_POST,

            ]);

            return;

        }

        // Crear usuario

        $userId = $userModel->create([

            'name' => $name,

            'email' => $email,

            'password' => $password,

            'role' => $role,

            'ficha' => $ficha !== '' ? $ficha : null,

        ]);

        $_SESSION['flash'] = [

            'type' => 'success',

            'message' => 'Usuario creado exitosamente',

        ];

        $this->redirect('/admin/users');

    }

    /**

     * Formulario para editar usuario

     */

    public function editUser(string $id): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $userId = (int)$id;

        $userModel = new User();

        $targetUser = $userModel->findById($userId);

        if (!$targetUser) {

            $_SESSION['flash'] = [

                'type' => 'error',

                'message' => 'Usuario no encontrado',

            ];

            $this->redirect('/admin/users');

            return;

        }

        $this->view('admin/users/edit', [

            'title' => 'Editar Usuario',

            'user' => $user,

            'targetUser' => $targetUser,

            'errors' => [],

        ]);

    }

    /**

     * Actualiza un usuario

     */

    public function updateUser(string $id): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $userId = (int)$id;

        $userModel = new User();

        $targetUser = $userModel->findById($userId);

        if (!$targetUser) {

            $_SESSION['flash'] = [

                'type' => 'error',

                'message' => 'Usuario no encontrado',

            ];

            $this->redirect('/admin/users');

            return;

        }

        $name = $this->sanitize((string)($_POST['name'] ?? ''));

        $email = $this->sanitize((string)($_POST['email'] ?? ''));

        $role = $this->sanitize((string)($_POST['role'] ?? ''));

        $ficha = $this->sanitize((string)($_POST['ficha'] ?? ''));

        $password = (string)($_POST['password'] ?? '');

        $errors = $this->validate([

            'name' => $name,

            'email' => $email,

            'role' => $role,

        ], [

            'name' => 'required|min:3|max:100',

            'email' => 'required|email',

            'role' => 'required',

        ]);

        if (!in_array($role, ['aprendiz', 'instructor', 'admin'], true)) {

            $errors['role'][] = 'Rol invÃ¡lido';

        }

        if (!empty($errors)) {

            $this->view('admin/users/edit', [

                'title' => 'Editar Usuario',

                'user' => $user,

                'targetUser' => $targetUser,

                'errors' => $errors,

            ]);

            return;

        }

        // Datos a actualizar

        $updateData = [

            'name' => $name,

            'email' => $email,

            'role' => $role,

            'ficha' => $ficha !== '' ? $ficha : null,

        ];

        // Solo actualizar contraseÃ±a si se proporcionÃ³ una nueva

        if ($password !== '') {

            if (strlen($password) < 6) {

                $errors['password'][] = 'La contraseÃ±a debe tener al menos 6 caracteres';

                $this->view('admin/users/edit', [

                    'title' => 'Editar Usuario',

                    'user' => $user,

                    'targetUser' => $targetUser,

                    'errors' => $errors,

                ]);

                return;

            }

            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);

        }

        $userModel->update($userId, $updateData);

        $_SESSION['flash'] = [

            'type' => 'success',

            'message' => 'Usuario actualizado exitosamente',

        ];

        $this->redirect('/admin/users');

    }

    /**

     * Elimina (desactiva) un usuario

     */

    public function deleteUser(string $id): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $userId = (int)$id;

        // No permitir eliminar el propio usuario admin

        if ($userId === (int)$user['id']) {

            $_SESSION['flash'] = [

                'type' => 'error',

                'message' => 'No puedes eliminar tu propio usuario',

            ];

            $this->redirect('/admin/users');

            return;

        }

        $userModel = new User();

        $userModel->delete($userId);

        $_SESSION['flash'] = [

            'type' => 'success',

            'message' => 'Usuario eliminado exitosamente',

        ];

        $this->redirect('/admin/users');

    }

    /**

     * GestiÃ³n de escenarios

     */

    public function scenarios(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $scenarioModel = new Scenario();

        $scenarios = $scenarioModel->listAll();

        $this->view('admin/scenarios/index', [

            'title' => 'GestiÃ³n de Escenarios',

            'user' => $user,

            'scenarios' => $scenarios,

        ]);

    }

    /**

     * Activa/Desactiva un escenario

     */

    public function toggleScenario(string $id): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $scenarioId = (int)$id;

        $scenarioModel = new Scenario();

        $scenario = $scenarioModel->findById($scenarioId);

        if (!$scenario) {

            $_SESSION['flash'] = [

                'type' => 'error',

                'message' => 'Escenario no encontrado',

            ];

            $this->redirect('/admin/scenarios');

            return;

        }

        // Cambiar estado

        $newStatus = $scenario['is_active'] ? 0 : 1;

        $scenarioModel->updateStatus($scenarioId, $newStatus);

        $_SESSION['flash'] = [

            'type' => 'success',

            'message' => $newStatus ? 'Escenario activado' : 'Escenario desactivado',

        ];

        $this->redirect('/admin/scenarios');

    }

    /**

     * ConfiguraciÃ³n del sistema

     */

    public function settings(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $defaults = [

            'app_name' => $_ENV['APP_NAME'] ?? 'RolPlay EDU',

            'maintenance_mode' => '0',

            'gamification_achievements_enabled' => '1',

            'gamification_ranking_enabled' => '1',

            'gamification_notifications_enabled' => '1',
            'puter_enabled' => '1',
            'puter_auto_login' => '0',
            'puter_login_hint' => '',
            'puter_prompt_mode' => 'login',
            'puter_password' => '',

        ];

        $settings = SystemSetting::getMany(array_keys($defaults));

        $settings = array_merge($defaults, $settings);

        $dbConnected = false;

        try {

            Database::getConnection();

            $dbConnected = true;

        } catch (PDOException $e) {

            $dbConnected = false;

        }

        $this->view('admin/settings', [

            'title' => 'Configuración del Sistema',

            'user' => $user,

            'settings' => $settings,

            'status' => [

                'db_connected' => $dbConnected,
                'tcpdf_available' => class_exists('TCPDF'),

            ],

        ]);

    }

    /**

     * Guarda configuraciones del sistema

     */

    public function saveSettings(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $appName = $this->sanitize((string)($_POST['app_name'] ?? 'RolPlay EDU'));

        if ($appName === '') {

            $appName = 'RolPlay EDU';

        }

        $settings = [

            'app_name' => $appName,

            'maintenance_mode' => isset($_POST['maintenance_mode']) ? '1' : '0',

            'gamification_achievements_enabled' => isset($_POST['gamification_achievements_enabled']) ? '1' : '0',

            'gamification_ranking_enabled' => isset($_POST['gamification_ranking_enabled']) ? '1' : '0',

            'gamification_notifications_enabled' => isset($_POST['gamification_notifications_enabled']) ? '1' : '0',
            'puter_enabled' => isset($_POST['puter_enabled']) ? '1' : '0',
            'puter_auto_login' => isset($_POST['puter_auto_login']) ? '1' : '0',
            'puter_login_hint' => $this->sanitize((string) ($_POST['puter_login_hint'] ?? '')),
            'puter_prompt_mode' => $this->sanitize((string) ($_POST['puter_prompt_mode'] ?? 'login')),

        ];

        $puterPassword = (string) ($_POST['puter_password'] ?? '');
        if ($puterPassword !== '') {
            $settings['puter_password'] = $puterPassword;
        }

        SystemSetting::setMany($settings);

        $_SESSION['flash'] = [

            'type' => 'success',

            'message' => 'Configuración actualizada correctamente.',

        ];

        $this->redirect('/admin/settings');

    }

    /**

     * Acciones del sistema

     */

    public function settingsAction(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $action = $this->sanitize((string)($_POST['action'] ?? ''));

        switch ($action) {

            case 'clear_cache':

                $deleted = $this->clearCache();

                $_SESSION['flash'] = [

                    'type' => 'success',

                    'message' => "Caché limpiada. Archivos eliminados: {$deleted}.",

                ];

                $this->redirect('/admin/settings');

                return;

            case 'backup_db':

                $backupPath = $this->backupDatabase();

                if ($backupPath) {

                    $_SESSION['flash'] = [

                        'type' => 'success',

                        'message' => 'Backup creado en: ' . $backupPath,

                    ];

                } else {

                    $_SESSION['flash'] = [

                        'type' => 'error',

                        'message' => 'No fue posible crear el backup.',

                    ];

                }

                $this->redirect('/admin/settings');

                return;

            case 'export_data':

                $this->exportUsersCsv();

                return;

            default:

                $_SESSION['flash'] = [

                    'type' => 'warning',

                    'message' => 'Acci?n no reconocida.',

                ];

                $this->redirect('/admin/settings');

                return;

        }

    }

    /**

     * Vista de logs del sistema

     */

    public function logs(): void

    {

        $user = $this->getAuthUser();

        if (!$user || $user['role'] !== 'admin') {

            $this->redirect('/');

            return;

        }

        $logDir = __DIR__ . '/../../storage/logs';

        $files = [];

        if (is_dir($logDir)) {

            foreach (scandir($logDir) as $file) {

                if ($file === '.' || $file === '..') {

                    continue;

                }

                $path = $logDir . DIRECTORY_SEPARATOR . $file;

                if (is_file($path)) {

                    $files[] = [

                        'name' => $file,

                        'path' => $path,

                        'modified' => filemtime($path) ?: 0,

                    ];

                }

            }

        }

        usort($files, fn($a, $b) => $b['modified'] <=> $a['modified']);

        $latestLog = $files[0]['path'] ?? null;

        $logContent = '';

        if ($latestLog && is_readable($latestLog)) {

            $lines = file($latestLog, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            if ($lines) {

                $logContent = implode("\n", array_slice($lines, -200));

            }

        }

        $this->view('admin/logs', [

            'title' => 'Logs del Sistema',

            'user' => $user,

            'files' => $files,

            'logContent' => $logContent,

        ]);

    }

    private function clearCache(): int
    {
        $cacheDir = __DIR__ . '/../../storage/cache';
        if (!is_dir($cacheDir)) {
            return 0;
        }

        $deleted = 0;
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($cacheDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            if ($item->isDir()) {
                @rmdir($item->getPathname());
            } else {
                if (@unlink($item->getPathname())) {
                    $deleted++;
                }
            }
        }

        return $deleted;
    }

    private function backupDatabase(): ?string

    {

        try {

            $db = Database::getConnection();

            $tables = $db->query('SHOW TABLES')->fetchAll(\PDO::FETCH_NUM);

            $tables = array_map(fn($row) => $row[0], $tables);

            $backupDir = __DIR__ . '/../../storage/backup';

            if (!is_dir($backupDir)) {

                @mkdir($backupDir, 0775, true);

            }

            $fileName = 'rolplay-backup-' . date('Ymd-His') . '.sql';

            $filePath = $backupDir . DIRECTORY_SEPARATOR . $fileName;

            $handle = fopen($filePath, 'wb');

            if (!$handle) {

                return null;

            }

            fwrite($handle, "-- RolPlay EDU Backup\n");

            fwrite($handle, "-- Generated at " . date('c') . "\n\n");

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tables as $table) {

                $createRow = $db->query('SHOW CREATE TABLE `' . $table . '`')->fetch(\PDO::FETCH_ASSOC);

                if (!empty($createRow['Create Table'])) {

                    fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");

                    fwrite($handle, $createRow['Create Table'] . ";\n\n");

                }

                $rows = $db->query('SELECT * FROM `' . $table . '`')->fetchAll(\PDO::FETCH_ASSOC);

                if (!empty($rows)) {

                    $columns = array_map(fn($col) => "`{$col}`", array_keys($rows[0]));

                    $columnsSql = implode(', ', $columns);

                    foreach ($rows as $row) {

                        $values = array_map(

                            fn($value) => $value === null ? 'NULL' : $db->quote((string) $value),

                            array_values($row)

                        );

                        $valuesSql = implode(', ', $values);

                        fwrite($handle, "INSERT INTO `{$table}` ({$columnsSql}) VALUES ({$valuesSql});\n");

                    }

                    fwrite($handle, "\n");

                }

            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");

            fclose($handle);

            return $filePath;

        } catch (\Throwable $e) {

            return null;

        }

    }

    private function exportUsersCsv(): void

    {

        try {

            $db = Database::getConnection();

            $rows = $db->query('SELECT id, name, email, role, ficha, created_at FROM users ORDER BY id ASC')

                ->fetchAll(\PDO::FETCH_ASSOC);

            $fileName = 'usuarios-export-' . date('Ymd-His') . '.csv';

            header('Content-Type: text/csv; charset=utf-8');

            header('Content-Disposition: attachment; filename="' . $fileName . '"');

            $output = fopen('php://output', 'wb');

            if (!empty($rows)) {

                fputcsv($output, array_keys($rows[0]));

                foreach ($rows as $row) {

                    fputcsv($output, $row);

                }

            } else {

                fputcsv($output, ['id', 'name', 'email', 'role', 'ficha', 'created_at']);

            }

            fclose($output);

            exit;

        } catch (\Throwable $e) {

            $_SESSION['flash'] = [

                'type' => 'error',

                'message' => 'No fue posible exportar los datos.',

            ];

            $this->redirect('/admin/settings');

        }

    }

}
