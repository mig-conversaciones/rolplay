<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Decision;
use App\Models\GameSession;
use App\Models\Scenario;
use App\Models\Achievement;

final class PlayerController extends Controller
{
    public function startSession(string $scenarioId): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->jsonError('No autenticado', 401);
            return;
        }

        $sid = (int) $scenarioId;
        if ($sid <= 0) {
            $this->jsonError('Escenario invalido', 422);
            return;
        }

        $scenarioModel = new Scenario();
        $scenario = $scenarioModel->findActiveById($sid);
        if (!$scenario) {
            $this->jsonError('Escenario no encontrado', 404);
            return;
        }

        $sessionModel = new GameSession();
        $sessionId = $sessionModel->start((int) $user['id'], $sid);

        $this->jsonSuccess('Sesion iniciada', [
            'session_id' => $sessionId,
            'scenario_id' => $sid,
        ]);
    }

    public function recordDecision(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->jsonError('No autenticado', 401);
            return;
        }

        $sessionId = (int) ($_POST['session_id'] ?? 0);
        $stepNumber = (int) ($_POST['step_number'] ?? 0);
        $optionChosen = (int) ($_POST['option_chosen'] ?? -1);
        $feedbackType = isset($_POST['feedback_type']) ? (string) $_POST['feedback_type'] : null;

        $scoresImpactRaw = $_POST['scores_impact'] ?? '{}';
        $scoresImpact = json_decode((string) $scoresImpactRaw, true);
        if (!is_array($scoresImpact)) {
            $scoresImpact = [];
        }

        $totalSteps = (int) ($_POST['total_steps'] ?? 0);
        $decisionsCount = (int) ($_POST['decisions_count'] ?? 0);

        if ($sessionId <= 0 || $optionChosen < 0) {
            $this->jsonError('Datos insuficientes', 422);
            return;
        }

        $decisionModel = new Decision();
        $decisionModel->create($sessionId, $stepNumber, $optionChosen, $scoresImpact, $feedbackType);

        $sessionModel = new GameSession();
        $currentScores = $sessionModel->getScores($sessionId);

        foreach ($scoresImpact as $key => $value) {
            $currentScores[$key] = (int) ($currentScores[$key] ?? 0) + (int) $value;
        }

        $completionPercentage = 0.0;
        if ($totalSteps > 0) {
            $completionPercentage = min(100.0, ($decisionsCount / $totalSteps) * 100.0);
        }

        $sessionModel->updateScores($sessionId, $currentScores, $decisionsCount, $completionPercentage);

        // Verificar y desbloquear logros cuando se completa la sesión
        $unlockedAchievements = [];
        if ($completionPercentage >= 100.0) {
            $achievementModel = new Achievement();
            $unlockedAchievements = $achievementModel->checkAndUnlockAchievements((int)$user['id']);
        }

        $this->jsonSuccess('Decision registrada', [
            'scores' => $currentScores,
            'completion_percentage' => $completionPercentage,
            'unlocked_achievements' => $unlockedAchievements,
        ]);
    }
}