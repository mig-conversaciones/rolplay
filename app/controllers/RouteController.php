<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\GameSession;
use App\Models\Route;
use App\Models\Scenario;

final class RouteController extends Controller
{
    public function index(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        if (!in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $routeModel = new Route();
        $routes = $routeModel->listByInstructor((int) $user['id']);

        $this->view('routes/index', [
            'title' => 'Rutas de aprendizaje',
            'user' => $user,
            'routes' => $routes,
        ]);
    }

    public function show(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        if (!in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $routeId = (int) $id;
        if ($routeId <= 0) {
            $this->redirect('/instructor/routes');
            return;
        }

        $routeModel = new Route();
        $route = $routeModel->findByIdForInstructor($routeId, (int) $user['id']);
        if (!$route) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Ruta no encontrada']);
            return;
        }

        $scenarioModel = new Scenario();
        $scenarioMap = $scenarioModel->findTitlesByIds($route['scenario_ids'] ?? []);

        $orderedScenarios = [];
        foreach (($route['scenario_ids'] ?? []) as $sid) {
            $sidInt = (int) $sid;
            if (isset($scenarioMap[$sidInt])) {
                $orderedScenarios[] = $scenarioMap[$sidInt];
            } else {
                $orderedScenarios[] = [
                    'id' => $sidInt,
                    'title' => 'Escenario #' . $sidInt,
                    'area' => '',
                    'difficulty' => '',
                ];
            }
        }

        $this->view('routes/show', [
            'title' => 'Detalle de ruta',
            'user' => $user,
            'route' => $route,
            'scenarios' => $orderedScenarios,
            'isInstructorView' => true,
        ]);
    }

    public function create(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        if (!in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $scenarioModel = new Scenario();
        $scenarios = $scenarioModel->listActiveBasic();

        $this->view('routes/create', [
            'title' => 'Crear ruta',
            'user' => $user,
            'scenarios' => $scenarios,
            'errors' => [],
            'old' => [
                'name' => '',
                'description' => '',
                'scenario_ids' => [],
                'groups' => '',
                'start_date' => '',
                'end_date' => '',
            ],
        ]);
    }

    public function store(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        if (!in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $scenarioIdsRaw = $_POST['scenario_ids'] ?? [];
        $scenarioIds = [];
        if (is_array($scenarioIdsRaw)) {
            foreach ($scenarioIdsRaw as $sid) {
                $sidInt = (int) $sid;
                if ($sidInt > 0) {
                    $scenarioIds[] = $sidInt;
                }
            }
        }

        $groupsText = $this->sanitize((string) ($_POST['groups'] ?? ''));
        $groups = array_values(array_filter(array_map('trim', preg_split('/[\s,;]+/', $groupsText) ?: [])));

        $data = [
            'name' => $this->sanitize((string) ($_POST['name'] ?? '')),
            'description' => $this->sanitize((string) ($_POST['description'] ?? '')),
            'scenario_ids' => $scenarioIds,
            'groups' => $groups,
            'start_date' => (string) ($_POST['start_date'] ?? ''),
            'end_date' => (string) ($_POST['end_date'] ?? ''),
        ];

        $errors = $this->validate($data, [
            'name' => 'required|min:4|max:255',
        ]);

        if (empty($data['scenario_ids'])) {
            $errors['scenario_ids'][] = 'Selecciona al menos un escenario';
        }

        if (!empty($errors)) {
            $scenarioModel = new Scenario();
            $scenarios = $scenarioModel->listActiveBasic();

            $this->view('routes/create', [
                'title' => 'Crear ruta',
                'user' => $user,
                'scenarios' => $scenarios,
                'errors' => $errors,
                'old' => [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'scenario_ids' => $data['scenario_ids'],
                    'groups' => $groupsText,
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                ],
            ]);
            return;
        }

        $routeModel = new Route();
        $routeModel->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'instructor_id' => (int) $user['id'],
            'scenario_ids' => $data['scenario_ids'],
            'groups' => $data['groups'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);

        $this->redirect('/instructor/routes');
    }

    public function learnerIndex(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $ficha = trim((string) ($user['ficha'] ?? ''));
        $routeModel = new Route();
        $routes = $ficha !== '' ? $routeModel->listForLearnerByFicha($ficha) : [];

        $this->view('routes/learner_index', [
            'title' => 'Mis rutas',
            'user' => $user,
            'routes' => $routes,
            'ficha' => $ficha,
        ]);
    }

    public function learnerShow(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $routeId = (int) $id;
        if ($routeId <= 0) {
            $this->redirect('/routes');
            return;
        }

        $routeModel = new Route();
        $route = $routeModel->findActiveById($routeId);
        if (!$route) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Ruta no encontrada']);
            return;
        }

        $ficha = trim((string) ($user['ficha'] ?? ''));
        $groups = array_map('strval', $route['groups'] ?? []);
        if ($ficha === '' || (!empty($groups) && !in_array($ficha, $groups, true))) {
            $this->redirect('/routes');
            return;
        }

        $scenarioIds = array_values(array_filter(array_map('intval', $route['scenario_ids'] ?? [])));
        $sessionModel = new GameSession();
        $completedIds = $sessionModel->completedScenarioIdsByUser((int) $user['id']);
        $remainingIds = array_values(array_diff($scenarioIds, $completedIds));

        $scenarioModel = new Scenario();
        $visibleIds = !empty($remainingIds) ? $remainingIds : $scenarioIds;
        $scenarioMap = $scenarioModel->findTitlesByIds($visibleIds);

        $orderedScenarios = [];
        foreach ($visibleIds as $sidInt) {
            $orderedScenarios[] = $scenarioMap[$sidInt] ?? [
                'id' => $sidInt,
                'title' => 'Escenario #' . $sidInt,
                'area' => '',
                'difficulty' => '',
            ];
        }

        $this->view('routes/learner_show', [
            'title' => 'Ruta asignada',
            'user' => $user,
            'route' => $route,
            'scenarios' => $orderedScenarios,
            'isInstructorView' => false,
        ]);
    }
}
