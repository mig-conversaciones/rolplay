<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Achievement;
use App\Models\SystemSetting;

/**
 * Controlador de Logros
 * RF-018: Sistema de Logros y Gamificación
 * RF-019: Ranking y Competencia
 */
final class AchievementController extends Controller
{
    /**
     * Muestra la galería de logros del usuario
     */
    public function index(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        if (SystemSetting::get('gamification_achievements_enabled', '1') !== '1') {
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'El sistema de logros estÃ¡ deshabilitado temporalmente.',
            ];
            $this->redirect('/scenarios');
            return;
        }

        $achievementModel = new Achievement();

        // Obtener todos los logros con estado de desbloqueo
        $achievements = $achievementModel->getAllWithUserStatus((int)$user['id']);

        // Agrupar por categoría
        $grouped = [];
        foreach ($achievements as $achievement) {
            $category = $achievement['category'] ?? 'general';
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }
            $grouped[$category][] = $achievement;
        }

        // Calcular estadísticas
        $total = count($achievements);
        $unlocked = count(array_filter($achievements, fn($a) => !empty($a['is_unlocked'])));
        $percentage = $total > 0 ? round(($unlocked / $total) * 100, 1) : 0;

        $this->view('achievements/index', [
            'title' => 'Mis Logros',
            'user' => $user,
            'achievements' => $grouped,
            'stats' => [
                'total' => $total,
                'unlocked' => $unlocked,
                'percentage' => $percentage,
            ],
        ]);
    }

    /**
     * Muestra el ranking global
     */
    public function ranking(): void
    {
        $user = $this->getAuthUser();

        if (SystemSetting::get('gamification_ranking_enabled', '1') !== '1') {
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'El ranking estÃ¡ deshabilitado temporalmente.',
            ];
            $this->redirect('/achievements');
            return;
        }

        $achievementModel = new Achievement();

        // Obtener tipo de ranking (general, competence, soft_skill, dynamic_consolidated)
        $type = $this->sanitize((string)($_GET['type'] ?? 'dynamic_consolidated'));
        $competence = $this->sanitize((string)($_GET['competence'] ?? ''));
        $softSkill = $this->sanitize((string)($_GET['soft_skill'] ?? ''));

        // Obtener lista de soft skills disponibles para filtros
        $availableSoftSkills = $achievementModel->getAllUniqueSoftSkills();

        // Seleccionar tipo de ranking
        if ($type === 'soft_skill' && !empty($softSkill)) {
            // Ranking por soft skill dinámica específica
            $ranking = $achievementModel->getRankingByDynamicSoftSkill($softSkill, 50);
        } elseif ($type === 'competence' && !empty($competence)) {
            // Ranking por competencia estática (legacy)
            $ranking = $achievementModel->getRankingByCompetence($competence, 50);
        } elseif ($type === 'dynamic_consolidated') {
            // Ranking consolidado de escenarios dinámicos (nuevo default)
            $ranking = $achievementModel->getRankingConsolidatedSoftSkills(50);
        } else {
            // Ranking general por puntos
            $ranking = $achievementModel->getRanking(50);
        }

        // Encontrar posición del usuario actual
        $userPosition = null;
        if ($user) {
            foreach ($ranking as $index => $entry) {
                if ((int)$entry['id'] === (int)$user['id']) {
                    $userPosition = $index + 1;
                    break;
                }
            }
        }

        $this->view('achievements/ranking', [
            'title' => 'Ranking',
            'user' => $user,
            'ranking' => $ranking,
            'type' => $type,
            'competence' => $competence,
            'softSkill' => $softSkill,
            'availableSoftSkills' => $availableSoftSkills,
            'userPosition' => $userPosition,
        ]);
    }

    /**
     * API: Verifica y desbloquea logros para un usuario
     * Se llama automáticamente después de completar una sesión
     */
    public function checkUnlocks(): void
    {
        header('Content-Type: application/json');

        $user = $this->getAuthUser();
        if (!$user) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        $achievementModel = new Achievement();
        $newUnlocks = $achievementModel->checkAndUnlockAchievements((int)$user['id']);

        echo json_encode([
            'success' => true,
            'unlocked' => count($newUnlocks),
            'achievements' => $newUnlocks,
        ]);
    }

    /**
     * Muestra detalles de un logro específico
     */
    public function show(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $achievementId = (int)$id;
        if ($achievementId <= 0) {
            $this->redirect('/achievements');
            return;
        }

        $achievementModel = new Achievement();
        $achievement = $achievementModel->findById($achievementId);

        if (!$achievement) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Logro no encontrado']);
            return;
        }

        // Verificar si el usuario lo tiene desbloqueado
        $isUnlocked = $achievementModel->hasUnlocked((int)$user['id'], $achievementId);

        $this->view('achievements/show', [
            'title' => $achievement['name'],
            'user' => $user,
            'achievement' => $achievement,
            'isUnlocked' => $isUnlocked,
        ]);
    }

    /**
     * Vista de administración de logros (solo admin/instructor)
     */
    public function manage(): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $achievementModel = new Achievement();
        $achievements = $achievementModel->getAll();
        $stats = $achievementModel->getStats();

        $this->view('achievements/manage', [
            'title' => 'Gestionar Logros',
            'user' => $user,
            'achievements' => $achievements,
            'stats' => $stats,
        ]);
    }

    /**
     * Formulario para crear nuevo logro
     */
    public function create(): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $this->view('achievements/create', [
            'title' => 'Crear Logro',
            'user' => $user,
            'errors' => [],
            'old' => [],
        ]);
    }

    /**
     * Guarda un nuevo logro
     */
    public function store(): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $name = $this->sanitize((string)($_POST['name'] ?? ''));
        $description = $this->sanitize((string)($_POST['description'] ?? ''));
        $icon = $this->sanitize((string)($_POST['icon'] ?? 'fa-trophy'));
        $category = $this->sanitize((string)($_POST['category'] ?? 'general'));
        $requirementType = $this->sanitize((string)($_POST['requirement_type'] ?? ''));
        $requirementValue = (int)($_POST['requirement_value'] ?? 0);
        $points = (int)($_POST['points'] ?? 100);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $errors = $this->validate([
            'name' => $name,
            'description' => $description,
            'requirement_type' => $requirementType,
            'requirement_value' => $requirementValue,
            'points' => $points,
        ], [
            'name' => 'required|min:3|max:100',
            'description' => 'required|min:10|max:255',
            'requirement_type' => 'required',
            'requirement_value' => 'required|min:1',
            'points' => 'required|min:10',
        ]);

        if (!empty($errors)) {
            $this->view('achievements/create', [
                'title' => 'Crear Logro',
                'user' => $user,
                'errors' => $errors,
                'old' => $_POST,
            ]);
            return;
        }

        $achievementModel = new Achievement();
        $achievementId = $achievementModel->create([
            'name' => $name,
            'description' => $description,
            'icon' => $icon,
            'category' => $category,
            'requirement_type' => $requirementType,
            'requirement_value' => $requirementValue,
            'points' => $points,
            'is_active' => $isActive,
        ]);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Logro creado exitosamente.',
        ];

        $this->redirect('/achievements/manage');
    }

    /**
     * Muestra el formulario de edición de un logro
     */
    public function edit(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $achievementId = (int)$id;
        if ($achievementId <= 0) {
            $this->redirect('/achievements/manage');
            return;
        }

        $achievementModel = new Achievement();
        $achievement = $achievementModel->findById($achievementId);

        if (!$achievement) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Logro no encontrado.',
            ];
            $this->redirect('/achievements/manage');
            return;
        }

        $this->view('achievements/edit', [
            'title' => 'Editar Logro',
            'user' => $user,
            'achievement' => $achievement,
            'errors' => $_SESSION['errors'] ?? [],
            'old' => $_SESSION['old'] ?? [],
        ], 'main');

        unset($_SESSION['errors'], $_SESSION['old']);
    }

    /**
     * Actualiza un logro existente
     */
    public function update(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $achievementId = (int)$id;
        if ($achievementId <= 0) {
            $this->redirect('/achievements/manage');
            return;
        }

        // Validar datos
        $errors = [];
        $name = $this->sanitize((string)($_POST['name'] ?? ''));
        $description = $this->sanitize((string)($_POST['description'] ?? ''));
        $icon = $this->sanitize((string)($_POST['icon'] ?? ''));
        $category = $this->sanitize((string)($_POST['category'] ?? ''));
        $points = (int)($_POST['points'] ?? 0);
        $requirementType = $this->sanitize((string)($_POST['requirement_type'] ?? ''));
        $requirementValue = (int)($_POST['requirement_value'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (empty($name)) {
            $errors['name'] = ['El título es requerido.'];
        }

        if (empty($description)) {
            $errors['description'] = ['La descripción es requerida.'];
        }

        if (empty($icon)) {
            $errors['icon'] = ['El icono es requerido.'];
        }

        if (empty($category)) {
            $errors['category'] = ['La categoría es requerida.'];
        }

        if ($points < 0) {
            $errors['points'] = ['Los puntos deben ser un valor positivo.'];
        }

        if (empty($requirementType)) {
            $errors['requirement_type'] = ['El tipo de requisito es requerido.'];
        }

        if ($requirementValue <= 0) {
            $errors['requirement_value'] = ['El valor del requisito debe ser mayor que 0.'];
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            $this->redirect('/achievements/' . $achievementId . '/edit');
            return;
        }

        // Actualizar logro
        $achievementModel = new Achievement();
        $data = [
            'name' => $name,
            'description' => $description,
            'icon' => $icon,
            'category' => $category,
            'points' => $points,
            'requirement_type' => $requirementType,
            'requirement_value' => $requirementValue,
            'is_active' => $isActive,
        ];

        $achievementModel->update($achievementId, $data);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Logro actualizado exitosamente.',
        ];

        $this->redirect('/achievements/manage');
    }

    /**
     * Elimina un logro
     */
    public function delete(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $achievementId = (int)$id;
        if ($achievementId <= 0) {
            $this->redirect('/achievements/manage');
            return;
        }

        $achievementModel = new Achievement();
        $achievementModel->delete($achievementId);

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Logro eliminado exitosamente.',
        ];

        $this->redirect('/achievements/manage');
    }
}
