<?php

declare(strict_types=1);

// Home - Redirige al login
$router->get('/', 'AuthController', 'showLogin');

// Scenarios (aprendiz)
$router->get('/scenarios', 'ScenarioController', 'index');
$router->get('/scenarios/{id}', 'ScenarioController', 'show');
$router->post('/api/sessions/start/{id}', 'PlayerController', 'startSession');
$router->post('/api/decisions', 'PlayerController', 'recordDecision');

// Perfil (aprendiz)
$router->get('/profile', 'ProfileController', 'show');
$router->get('/sessions/{id}', 'SessionController', 'show');

// Notificaciones
$router->get('/notifications', 'NotificationController', 'index');
$router->post('/notifications/{id}/read', 'NotificationController', 'markRead');
$router->post('/notifications/read-all', 'NotificationController', 'markAllRead');

// Sesiones dinámicas (3 etapas con IA)
$router->post('/learner/programs/{id}/start-dynamic', 'SessionController', 'startDynamic');
$router->get('/sessions/{id}/play', 'SessionController', 'play');
$router->post('/sessions/{id}/submit-decision', 'SessionController', 'submitDecision');
$router->get('/sessions/{id}/results', 'SessionController', 'results');

// Instructor
$router->get('/instructor', 'InstructorController', 'dashboard');
$router->get('/instructor/routes', 'RouteController', 'index');
$router->get('/instructor/routes/create', 'RouteController', 'create');
$router->post('/instructor/routes', 'RouteController', 'store');
$router->get('/instructor/routes/{id}', 'RouteController', 'show');
$router->get('/instructor/programs', 'ProgramController', 'index');
$router->get('/instructor/programs/create', 'ProgramController', 'create');
$router->post('/instructor/programs', 'ProgramController', 'store');
$router->get('/instructor/programs/{id}', 'ProgramController', 'show');
$router->post('/instructor/programs/{id}/analyze', 'ProgramController', 'analyze');
$router->post('/instructor/programs/{id}/analyze-async', 'ProgramController', 'analyzeAsync');
$router->get('/instructor/programs/{id}/analysis-status', 'ProgramController', 'analysisStatus');
$router->post('/instructor/programs/{id}/save-scenario', 'ProgramController', 'saveGeneratedScenario');
$router->post('/instructor/programs/{id}/delete', 'ProgramController', 'delete');

// Rutas para aprendiz
$router->get('/routes', 'RouteController', 'learnerIndex');
$router->get('/routes/{id}', 'RouteController', 'learnerShow');
$router->get('/learner/programs', 'ProgramController', 'learnerIndex');

// Achievements (gamificación)
$router->get('/achievements', 'AchievementController', 'index');
$router->get('/achievements/ranking', 'AchievementController', 'ranking');
$router->get('/achievements/manage', 'AchievementController', 'manage');
$router->get('/achievements/create', 'AchievementController', 'create');
$router->post('/achievements', 'AchievementController', 'store');
$router->get('/achievements/{id}/edit', 'AchievementController', 'edit');
$router->post('/achievements/{id}', 'AchievementController', 'update');
$router->post('/achievements/{id}/delete', 'AchievementController', 'delete');
$router->post('/api/achievements/check-unlocks', 'AchievementController', 'checkUnlocks');

// Reports (instructor)
$router->get('/instructor/reports/individual/{id}', 'InstructorController', 'generateIndividualReport');
$router->get('/instructor/reports/group', 'InstructorController', 'generateGroupReport');

// Admin
$router->get('/admin', 'AdminController', 'dashboard');
$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/users/create', 'AdminController', 'createUser');
$router->post('/admin/users', 'AdminController', 'storeUser');
$router->get('/admin/users/{id}/edit', 'AdminController', 'editUser');
$router->post('/admin/users/{id}', 'AdminController', 'updateUser');
$router->post('/admin/users/{id}/delete', 'AdminController', 'deleteUser');
$router->get('/admin/scenarios', 'AdminController', 'scenarios');
$router->post('/admin/scenarios/{id}/toggle', 'AdminController', 'toggleScenario');
$router->get('/admin/settings', 'AdminController', 'settings');
$router->post('/admin/settings', 'AdminController', 'saveSettings');
$router->post('/admin/settings/action', 'AdminController', 'settingsAction');
$router->get('/admin/logs', 'AdminController', 'logs');

// Auth
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->post('/logout', 'AuthController', 'logout');
