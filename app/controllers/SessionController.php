<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Decision;
use App\Models\GameSession;
use App\Models\Program;
use App\Models\ProgramSoftSkill;
use App\Services\DynamicScenarioService;

final class SessionController extends Controller
{
    /**
     * Inicia una sesión dinámica de 3 etapas para un programa
     */
    public function startDynamic(string $programId): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $programIdInt = (int) $programId;
        if ($programIdInt <= 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'ID de programa inválido',
            ];
            $this->redirect('/learner/routes');
            return;
        }

        // Verificar que el programa existe y tiene soft skills
        $programModel = new Program();
        $program = $programModel->findById($programIdInt);

        if (!$program) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Programa no encontrado',
            ];
            $this->redirect('/learner/routes');
            return;
        }

        // Obtener soft skills del programa
        $softSkillModel = new ProgramSoftSkill();
        $softSkills = $softSkillModel->getByProgramId($programIdInt);

        if (empty($softSkills) || count($softSkills) < 5) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Este programa aún no tiene soft skills identificadas. Contacta al instructor.',
            ];
            $this->redirect('/learner/routes');
            return;
        }

        try {
            // Generar etapa 1
            $dynamicService = new DynamicScenarioService();
            $stage1 = $dynamicService->generateStage1($program, $softSkills);

            // Crear sesión en BD
            $sessionModel = new GameSession();
            $sessionId = $sessionModel->create([
                'user_id' => (int) $user['id'],
                'scenario_id' => null, // Sesión dinámica sin escenario fijo
                'program_id' => $programIdInt,
                'is_dynamic' => true,
                'current_stage' => 1,
                'stage1_json' => json_encode($stage1),
                'stage2_json' => null,
                'stage3_json' => null,
                'status' => 'in_progress',
                'scores_json' => json_encode([]), // Inicializar scores vacíos
            ]);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Escenario dinámico generado. ¡Comienza tu simulación!',
            ];

            $this->redirect('/sessions/' . $sessionId . '/play');

        } catch (\Throwable $e) {
            error_log("Error iniciando sesión dinámica: " . $e->getMessage());
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'No se pudo generar el escenario: ' . $e->getMessage(),
            ];
            $this->redirect('/learner/routes');
        }
    }

    /**
     * Muestra la etapa actual de la sesión dinámica
     */
    public function play(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $sessionId = (int) $id;
        if ($sessionId <= 0) {
            $this->redirect('/profile');
            return;
        }

        $sessionModel = new GameSession();
        $session = $sessionModel->findByIdForUser($sessionId, (int) $user['id']);

        if (!$session) {
            http_response_code(404);
            $this->view('errors/404', [
                'title' => 'Sesión no encontrada',
            ]);
            return;
        }

        // Verificar que es sesión dinámica
        if (!($session['is_dynamic'] ?? false)) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Esta no es una sesión dinámica',
            ];
            $this->redirect('/sessions/' . $sessionId);
            return;
        }

        // Si ya está completada, redirigir a resultados
        if ($session['status'] === 'completed') {
            $this->redirect('/sessions/' . $sessionId . '/results');
            return;
        }

        // Obtener contenido de la etapa actual
        $currentStage = (int) ($session['current_stage'] ?? 1);
        $stageContent = null;

        switch ($currentStage) {
            case 1:
                $stageContent = json_decode((string) $session['stage1_json'], true);
                break;
            case 2:
                $stageContent = json_decode((string) $session['stage2_json'], true);
                break;
            case 3:
                $stageContent = json_decode((string) $session['stage3_json'], true);
                break;
            default:
                $_SESSION['flash'] = [
                    'type' => 'error',
                    'message' => 'Etapa inválida',
                ];
                $this->redirect('/profile');
                return;
        }

        if (!$stageContent) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Error cargando el contenido de la etapa',
            ];
            $this->redirect('/profile');
            return;
        }

        // Obtener decisiones previas
        $decisionModel = new Decision();
        $previousDecisions = $decisionModel->listBySession($sessionId);

        $this->view('sessions/play_dynamic', [
            'title' => 'Escenario Dinámico - Etapa ' . $currentStage,
            'user' => $user,
            'session' => $session,
            'currentStage' => $currentStage,
            'stageContent' => $stageContent,
            'previousDecisions' => $previousDecisions,
        ]);
    }

    /**
     * Procesa la decisión y genera la siguiente etapa (API JSON)
     */
    public function submitDecision(string $id): void
    {
        header('Content-Type: application/json');

        $user = $this->getAuthUser();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            return;
        }

        $sessionId = (int) $id;
        $chosenOption = (int) ($_POST['option'] ?? -1);

        if ($chosenOption < 0 || $chosenOption > 2) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Opción inválida']);
            return;
        }

        $sessionModel = new GameSession();
        $session = $sessionModel->findByIdForUser($sessionId, (int) $user['id']);

        if (!$session || !($session['is_dynamic'] ?? false)) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sesión no encontrada']);
            return;
        }

        try {
            $currentStage = (int) ($session['current_stage'] ?? 1);

            // Obtener contenido de la etapa actual
            $stageContent = json_decode((string) $session["stage{$currentStage}_json"], true);
            $chosenOptionData = $stageContent['options'][$chosenOption] ?? null;

            if (!$chosenOptionData) {
                throw new \RuntimeException('Opción no encontrada en el contenido');
            }

            // Extraer scores
            $scores = $chosenOptionData['scores'] ?? [];

            // Guardar decisión
            $decisionModel = new Decision();
            $decisionModel->create([
                'session_id' => $sessionId,
                'stage' => $currentStage,
                'step_number' => $currentStage,
                'option_chosen' => $chosenOption,
                'scores_impact' => json_encode($scores),
                'feedback_type' => 'neutral',
            ]);

            // Actualizar scores acumulados en la sesión
            $currentScores = json_decode((string) $session['scores_json'], true) ?? [];
            foreach ($scores as $skillName => $points) {
                $currentScores[$skillName] = ($currentScores[$skillName] ?? 0) + $points;
            }
            $sessionModel->updateScores($sessionId, $currentScores);

            // Generar siguiente etapa si no es la última
            if ($currentStage < 3) {
                $programModel = new Program();
                $program = $programModel->findById((int) $session['program_id']);

                $softSkillModel = new ProgramSoftSkill();
                $softSkills = $softSkillModel->getByProgramId((int) $session['program_id']);

                $dynamicService = new DynamicScenarioService();

                if ($currentStage === 1) {
                    // Generar etapa 2
                    $stage1Content = json_decode((string) $session['stage1_json'], true);
                    $stage2 = $dynamicService->generateStage2(
                        $stage1Content,
                        $chosenOption,
                        $softSkills,
                        $program
                    );
                    $sessionModel->saveStage($sessionId, 2, $stage2);
                    $sessionModel->updateStage($sessionId, 2);

                    echo json_encode([
                        'success' => true,
                        'next_stage' => 2,
                        'message' => 'Avanzando a Etapa 2...',
                    ]);

                } elseif ($currentStage === 2) {
                    // Generar etapa 3
                    $stage1Content = json_decode((string) $session['stage1_json'], true);
                    $stage2Content = json_decode((string) $session['stage2_json'], true);

                    $decisionStage1 = $decisionModel->getChoiceByStage($sessionId, 1);

                    $stage3 = $dynamicService->generateStage3(
                        $stage1Content,
                        $stage2Content,
                        $decisionStage1,
                        $chosenOption,
                        $softSkills,
                        $currentScores,
                        $program
                    );
                    $sessionModel->saveStage($sessionId, 3, $stage3);
                    $sessionModel->updateStage($sessionId, 3);

                    echo json_encode([
                        'success' => true,
                        'next_stage' => 3,
                        'message' => 'Avanzando a Etapa Final...',
                    ]);
                }

            } else {
                // Sesión completada
                $sessionModel->complete($sessionId);

                // Verificar y desbloquear logros
                $achievementModel = new \App\Models\Achievement();
                $newUnlocks = $achievementModel->checkAndUnlockAchievements((int) $user['id']);

                echo json_encode([
                    'success' => true,
                    'completed' => true,
                    'message' => 'Escenario completado',
                    'achievements_unlocked' => count($newUnlocks),
                    'new_achievements' => $newUnlocks,
                ]);
            }

        } catch (\Throwable $e) {
            error_log("Error procesando decisión: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error procesando decisión: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Muestra los resultados finales de la sesión dinámica
     */
    public function results(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $sessionId = (int) $id;
        $sessionModel = new GameSession();
        $session = $sessionModel->findByIdForUser($sessionId, (int) $user['id']);

        if (!$session) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Sesión no encontrada']);
            return;
        }

        if ($session['status'] !== 'completed') {
            $_SESSION['flash'] = [
                'type' => 'warning',
                'message' => 'Esta sesión aún no está completada',
            ];
            $this->redirect('/sessions/' . $sessionId . '/play');
            return;
        }

        // Obtener decisiones
        $decisionModel = new Decision();
        $decisions = $decisionModel->listBySession($sessionId);

        // Obtener soft skills del programa
        $softSkillModel = new ProgramSoftSkill();
        $softSkills = $softSkillModel->getByProgramId((int) $session['program_id']);

        // Obtener feedback de etapa 3
        $stage3Content = json_decode((string) $session['stage3_json'], true);
        $overallFeedback = $stage3Content['overall_feedback'] ?? '';

        // Calcular scores finales
        $finalScores = json_decode((string) $session['scores_json'], true) ?? [];

        // Obtener logros del usuario para mostrar recientes
        $achievementModel = new \App\Models\Achievement();
        $recentAchievements = [];

        // Obtener logros desbloqueados en los últimos 5 minutos
        $query = "
            SELECT a.*, ua.unlocked_at
            FROM achievements a
            INNER JOIN user_achievements ua ON a.id = ua.achievement_id
            WHERE ua.user_id = :user_id
              AND ua.unlocked_at >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)
            ORDER BY ua.unlocked_at DESC
        ";
        $recentAchievements = $achievementModel->query($query, ['user_id' => (int)$user['id']]);

        $this->view('sessions/results_dynamic', [
            'title' => 'Resultados del Escenario',
            'user' => $user,
            'session' => $session,
            'decisions' => $decisions,
            'softSkills' => $softSkills,
            'finalScores' => $finalScores,
            'overallFeedback' => $overallFeedback,
            'recentAchievements' => $recentAchievements,
        ]);
    }

    public function show(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $sessionId = (int) $id;
        if ($sessionId <= 0) {
            $this->redirect('/profile');
            return;
        }

        $sessionModel = new GameSession();
        $session = $sessionModel->findByIdForUser($sessionId, (int) $user['id']);

        if (!$session) {
            http_response_code(404);
            $this->view('errors/404', [
                'title' => 'Sesion no encontrada',
            ]);
            return;
        }

        $decisionModel = new Decision();
        $decisions = $decisionModel->listBySession($sessionId);

        $this->view('sessions/show', [
            'title' => 'Detalle de sesion',
            'user' => $user,
            'session' => $session,
            'decisions' => $decisions,
        ]);
    }
}