<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\GameSession;
use App\Models\Route;
use App\Models\Scenario;

final class ScenarioController extends Controller
{
    public function index(): void
    {
        $model = new Scenario();
        $user = $this->getAuthUser();
        $scenarios = $model->allActive();

        if ($user && ($user['role'] ?? '') === 'aprendiz') {
            $ficha = trim((string) ($user['ficha'] ?? ''));
            if ($ficha !== '') {
                $routeModel = new Route();
                $routes = $routeModel->listForLearnerByFicha($ficha);
                $routeScenarioIds = [];
                foreach ($routes as $route) {
                    foreach (($route['scenario_ids'] ?? []) as $sid) {
                        $sidInt = (int) $sid;
                        if ($sidInt > 0) {
                            $routeScenarioIds[] = $sidInt;
                        }
                    }
                }
                $routeScenarioIds = array_values(array_unique($routeScenarioIds));

                if (!empty($routeScenarioIds)) {
                    $sessionModel = new GameSession();
                    $completedIds = $sessionModel->completedScenarioIdsByUser((int) $user['id']);
                    $remaining = array_values(array_diff($routeScenarioIds, $completedIds));

                    // Hasta completar toda la ruta, ocultar escenarios ya entrenados.
                    if (!empty($remaining)) {
                        $scenarios = $model->listActiveByIds($remaining);
                    }
                }
            }
        }

        $this->view('scenarios/index', [
            'title' => 'Escenarios',
            'scenarios' => $scenarios,
            'user' => $user,
        ]);
    }

    public function show(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $scenarioId = (int) $id;
        if ($scenarioId <= 0) {
            $this->redirect('/scenarios');
            return;
        }

        $model = new Scenario();
        $scenario = $model->findActiveById($scenarioId);

        if (!$scenario) {
            http_response_code(404);
            $this->view('errors/404', [
                'title' => 'Escenario no encontrado',
            ]);
            return;
        }

        $this->view('scenarios/show', [
            'title' => $scenario['title'],
            'scenario' => $scenario,
            'user' => $user,
        ]);
    }
}
