<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Program;
use App\Models\Scenario;
use App\Services\ProgramAnalysisService;
use App\Services\ScenarioGeneratorService;

final class ProgramController extends Controller
{
    private Program $programModel;

    public function __construct()
    {
        $this->programModel = new Program();
    }

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

        $programs = $this->programModel->listByInstructor((int) $user['id']);

        $this->view('programs/index', [
            'title' => 'Programas',
            'user' => $user,
            'programs' => $programs,
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

        $this->view('programs/create', [
            'title' => 'Cargar programa',
            'user' => $user,
            'errors' => [],
            'old' => [
                'title' => '',
                'codigo_programa' => '',
                'competencias' => '',
                'perfil_egreso' => '',
                'analysis_json' => '',
                'analysis_source' => '',
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

        $title = $this->sanitize((string) ($_POST['title'] ?? ''));
        $codigoPrograma = $this->sanitize((string) ($_POST['codigo_programa'] ?? ''));
        $competencias = $this->sanitize((string) ($_POST['competencias'] ?? ''));
        $perfilEgreso = $this->sanitize((string) ($_POST['perfil_egreso'] ?? ''));
        $analysisJsonRaw = (string) ($_POST['analysis_json'] ?? '');
        $analysisSource = $this->sanitize((string) ($_POST['analysis_source'] ?? ''));

        $analysisData = null;
        if ($analysisJsonRaw !== '') {
            $decoded = json_decode($analysisJsonRaw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $analysisData = $decoded;
            }
        }

        if ($analysisData) {
            if ($title === '' && !empty($analysisData['nombre'])) {
                $title = $this->sanitize((string) $analysisData['nombre']);
            }
            if ($codigoPrograma === '' && !empty($analysisData['codigo_programa'])) {
                $codigoPrograma = $this->sanitize((string) $analysisData['codigo_programa']);
            }
            if ($competencias === '' && !empty($analysisData['competencias']) && is_array($analysisData['competencias'])) {
                $competencias = $this->sanitize(implode("\n", array_map('strval', $analysisData['competencias'])));
            }
            if ($perfilEgreso === '' && !empty($analysisData['perfil_egresado'])) {
                $perfilEgreso = $this->sanitize((string) $analysisData['perfil_egresado']);
            }
        }

        $errors = [];
        if ($analysisJsonRaw !== '' && !$analysisData) {
            $errors['analysis_json'][] = 'El analisis generado es invalido. Vuelve a analizar el PDF.';
        }
        if ($analysisJsonRaw === '' || !$analysisData) {
            $errors['analysis_json'][] = 'Debes analizar el PDF antes de guardar el programa.';
        }
        $pdfPath = null;
        $pdfOriginalName = null;
        if (isset($_FILES['program_pdf']) && ($_FILES['program_pdf']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['program_pdf']['error'] !== UPLOAD_ERR_OK) {
                $errors['program_pdf'][] = 'No se pudo cargar el PDF.';
            } else {
                $tmpName = (string) $_FILES['program_pdf']['tmp_name'];
                $originalName = (string) $_FILES['program_pdf']['name'];
                $size = (int) ($_FILES['program_pdf']['size'] ?? 0);

                if ($size <= 0) {
                    $errors['program_pdf'][] = 'El PDF esta vacio.';
                }

                $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                if ($extension !== 'pdf') {
                    $errors['program_pdf'][] = 'El archivo debe ser un PDF.';
                }

                if (empty($errors['program_pdf'])) {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mime = $finfo->file($tmpName);
                    if ($mime !== 'application/pdf') {
                        $errors['program_pdf'][] = 'El archivo no parece ser un PDF valido.';
                    }
                }

                if (empty($errors['program_pdf'])) {
                    $uploadDir = dirname(__DIR__, 2) . '/public/uploads/programs';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    $uniqueName = 'program_' . date('Ymd_His') . '_' . bin2hex(random_bytes(6)) . '.pdf';
                    $destination = $uploadDir . '/' . $uniqueName;
                    if (!move_uploaded_file($tmpName, $destination)) {
                        $errors['program_pdf'][] = 'No se pudo guardar el PDF en el servidor.';
                    } else {
                        $pdfPath = '/uploads/programs/' . $uniqueName;
                        $pdfOriginalName = $originalName;
                    }
                }
            }
        } else {
            $errors['program_pdf'][] = 'El PDF es requerido.';
        }

        $validationErrors = $this->validate([
            'title' => $title,
            'competencias' => $competencias,
            'perfil_egreso' => $perfilEgreso,
        ], [
            'title' => 'required|min:4|max:255',
            'competencias' => 'required|min:20',
            'perfil_egreso' => 'required|min:20',
        ]);

        if (!empty($validationErrors)) {
            $errors = array_merge_recursive($errors, $validationErrors);
        }

        if (!empty($errors)) {
            $this->view('programs/create', [
                'title' => 'Cargar programa',
                'user' => $user,
                'errors' => $errors,
                'old' => [
                    'title' => $title,
                    'codigo_programa' => $codigoPrograma,
                    'competencias' => $competencias,
                    'perfil_egreso' => $perfilEgreso,
                    'analysis_json' => $analysisJsonRaw,
                    'analysis_source' => $analysisSource,
                ],
            ]);
            return;
        }

        $analysisPayload = $analysisData ? json_encode($analysisData, JSON_UNESCAPED_UNICODE) : null;
        $status = $analysisData ? 'completed' : 'pending';

        $programId = $this->programModel->create([
            'instructor_id' => (int) $user['id'],
            'title' => $title,
            'codigo_programa' => $codigoPrograma,
            'competencias_text' => $competencias,
            'perfil_egreso_text' => $perfilEgreso,
            'analysis_json' => $analysisPayload,
            'status' => $status,
            'pdf_path' => $pdfPath,
            'pdf_original_name' => $pdfOriginalName,
        ]);

        if ($analysisData) {
            try {
                $softSkills = $analysisData['soft_skills'] ?? null;
                $sector = $analysisData['sector'] ?? null;
                $softSkillsGenerated = false;

                if (empty($softSkills) || !is_array($softSkills)) {
                    $sectorService = new \App\Services\SectorAnalysisService();
                    $softSkillsData = $sectorService->identifySoftSkills($analysisData);
                    $analysisData['sector'] = $softSkillsData['sector'];
                    $analysisData['soft_skills'] = $softSkillsData['soft_skills'];
                    $softSkillsGenerated = !empty($analysisData['soft_skills']);
                    $analysisData['soft_skills_generated'] = $softSkillsGenerated;
                } else {
                    $analysisData['soft_skills_generated'] = true;
                    $softSkillsGenerated = true;
                }

                $this->programModel->updateAnalysis($programId, $analysisData, 'completed');
                $this->programModel->updateAnalysisAsyncFields($programId, 'COMPLETADO', $analysisData);

                if (!empty($analysisData['sector'])) {
                    $this->programModel->updateSoftSkillsInfo(
                        $programId,
                        (string) $analysisData['sector'],
                        $softSkillsGenerated
                    );
                }

                if (!empty($analysisData['soft_skills']) && is_array($analysisData['soft_skills'])) {
                    $softSkillModel = new \App\Models\ProgramSoftSkill();
                    $softSkillModel->saveSoftSkills($programId, $analysisData['soft_skills']);
                }
            } catch (\Throwable $e) {
                error_log('Error preparando soft skills en store: ' . $e->getMessage());
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => 'Programa creado, pero no se pudieron preparar las soft skills.',
                ];
            }
        }

        $this->redirect('/instructor/programs/' . $programId);
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

        $programId = (int) $id;
        if ($programId <= 0) {
            $this->redirect('/instructor/programs');
            return;
        }

        $program = $this->programModel->findByIdForInstructor($programId, (int) $user['id']);
        if (!$program) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Programa no encontrado']);
            return;
        }

        $scenarioModel = new Scenario();
        $scenarios = $scenarioModel->listByProgram($programId);

        $softSkillModel = new \App\Models\ProgramSoftSkill();
        $softSkills = $softSkillModel->getByProgramId($programId);

        $this->view('programs/show', [
            'program' => $program,
            'scenarios' => $scenarios,
            'softSkills' => $softSkills,
        ]);
    }

    public function analyze(string $id): void
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

        $programId = (int) $id;
        if ($programId <= 0) {
            $this->redirect('/instructor/programs');
            return;
        }

        $program = $this->programModel->findByIdForInstructor($programId, (int) $user['id']);
        if (!$program) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Programa no encontrado']);
            return;
        }

        $this->programModel->updateStatus($programId, 'analyzing');
        $this->programModel->updateAnalysisAsyncFields($programId, 'PROCESANDO');

        try {
            $service = new ProgramAnalysisService();
            $analysis = $service->analyzeInputsAndIdentifySoftSkills(
                (string) $program['title'],
                (string) ($program['codigo_programa'] ?? ''),
                (string) ($program['competencias_text'] ?? ''),
                (string) ($program['perfil_egreso_text'] ?? '')
            );

            $this->programModel->updateAnalysis($programId, $analysis, 'completed');
            $this->programModel->updateAnalysisAsyncFields($programId, 'COMPLETADO', $analysis);

            if (!empty($analysis['soft_skills']) && is_array($analysis['soft_skills'])) {
                $softSkillModel = new \App\Models\ProgramSoftSkill();
                $skillsCount = $softSkillModel->saveSoftSkills($programId, $analysis['soft_skills']);

                if (!empty($analysis['sector'])) {
                    $this->programModel->updateSoftSkillsInfo(
                        $programId,
                        $analysis['sector'],
                        $analysis['soft_skills_generated'] ?? false
                    );
                }

                if (!empty($analysis['_meta']['stub'])) {
                    $stubReason = $analysis['_meta']['reason'] ?? 'Revisa la configuracion del analisis o los datos del programa';
                    $_SESSION['flash'] = [
                        'type' => 'warning',
                        'message' => "Analisis parcial: {$stubReason}",
                    ];
                } else {
                    $_SESSION['flash'] = [
                        'type' => 'success',
                        'message' => "Analisis completado. Se identificaron {$skillsCount} soft skills para el sector: " . ucfirst($analysis['sector'] ?? 'general'),
                    ];
                }
            } else {
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => 'Analisis completado pero no se pudieron identificar soft skills especificas.',
                ];
            }
        } catch (\Throwable $e) {
            $this->programModel->updateStatus($programId, 'error');
            $this->programModel->updateAnalysisAsyncFields($programId, 'ERROR');
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Error al analizar: ' . $e->getMessage(),
            ];
        }

        $this->redirect('/instructor/programs/' . $programId);
    }

    public function analyzeAsync(string $id): void
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

        $programId = (int) $id;
        if ($programId <= 0) {
            http_response_code(400);
            $this->json(['ok' => false, 'message' => 'Programa invalido.']);
            return;
        }

        $program = $this->programModel->findByIdForInstructor($programId, (int) $user['id']);
        if (!$program) {
            http_response_code(404);
            $this->json(['ok' => false, 'message' => 'Programa no encontrado.']);
            return;
        }

        $this->programModel->updateStatus($programId, 'analyzing');
        $this->programModel->updateAnalysisAsyncFields($programId, 'PROCESANDO');

        ignore_user_abort(true);
        set_time_limit(0);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        $payload = json_encode([
            'ok' => true,
            'status' => 'processing',
            'program_id' => $programId,
        ], JSON_UNESCAPED_UNICODE);

        if ($payload === false) {
            $payload = '{"ok":true,"status":"processing"}';
        }

        if (ob_get_level() > 0) {
            @ob_end_clean();
        }
        ob_start();
        echo $payload;
        $size = ob_get_length();

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Encoding: none');
        header('Content-Length: ' . $size);
        header('Connection: close');

        ob_end_flush();
        flush();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        try {
            $service = new ProgramAnalysisService();
            $analysis = $service->analyzeInputsAndIdentifySoftSkills(
                (string) $program['title'],
                (string) ($program['codigo_programa'] ?? ''),
                (string) ($program['competencias_text'] ?? ''),
                (string) ($program['perfil_egreso_text'] ?? '')
            );

            $this->programModel->updateAnalysis($programId, $analysis, 'completed');
            $this->programModel->updateAnalysisAsyncFields($programId, 'COMPLETADO', $analysis);

            if (!empty($analysis['soft_skills']) && is_array($analysis['soft_skills'])) {
                $softSkillModel = new \App\Models\ProgramSoftSkill();
                $softSkillModel->saveSoftSkills($programId, $analysis['soft_skills']);

                if (!empty($analysis['sector'])) {
                    $this->programModel->updateSoftSkillsInfo(
                        $programId,
                        $analysis['sector'],
                        $analysis['soft_skills_generated'] ?? false
                    );
                }
            }

            $notification = new \App\Models\Notification();
            $notification->create(
                (int) $user['id'],
                'system',
                'Analisis de programa completado',
                'El analisis del programa "' . (string) ($program['title'] ?? '') . '" ya finalizo.',
                '/instructor/programs/' . $programId
            );
        } catch (\Throwable $e) {
            $this->programModel->updateStatus($programId, 'error');
            $this->programModel->updateAnalysisAsyncFields($programId, 'ERROR');
            error_log('Program analysis async error: ' . $e->getMessage());

            $notification = new \App\Models\Notification();
            $notification->create(
                (int) $user['id'],
                'system',
                'Error en analisis de programa',
                'El analisis del programa "' . (string) ($program['title'] ?? '') . '" termino con error.',
                '/instructor/programs/' . $programId
            );
        }
    }

    public function analysisStatus(string $id): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            http_response_code(401);
            $this->json(['ok' => false, 'message' => 'No autenticado.']);
            return;
        }

        if (!in_array($user['role'], ['instructor', 'admin'], true)) {
            http_response_code(403);
            $this->json(['ok' => false, 'message' => 'Sin permisos.']);
            return;
        }

        $programId = (int) $id;
        if ($programId <= 0) {
            http_response_code(400);
            $this->json(['ok' => false, 'message' => 'Programa invalido.']);
            return;
        }

        $program = $this->programModel->findByIdForInstructor($programId, (int) $user['id']);
        if (!$program) {
            http_response_code(404);
            $this->json(['ok' => false, 'message' => 'Programa no encontrado.']);
            return;
        }

        $analysisStatus = (string) ($program['estado_analisis'] ?? 'PENDIENTE');
        $response = [
            'ok' => true,
            'status' => $analysisStatus,
        ];

        if ($analysisStatus === 'COMPLETADO') {
            $response['analysis'] = $program['analysis'] ?? null;
        }

        $this->json($response);
    }

    public function saveGeneratedScenario(string $id): void
    {
        header('Content-Type: application/json');
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'message' => 'No autorizado.']);
            return;
        }

        $programId = (int) $id;
        if ($programId <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'message' => 'ID de programa inválido.']);
            return;
        }

        $jsonPayload = file_get_contents('php://input');
        $data = json_decode($jsonPayload, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'message' => 'JSON inválido.']);
            return;
        }

        try {
            // Re-usar la lógica de validación del servicio
            $generator = new \App\Services\ScenarioGeneratorService();
            // La validación se hace a través de un método público temporal o moviendo la lógica
            // Aquí asumimos que podemos instanciar y validar
            $validated = $generator->validateScenarioPayload($data);

            $scenarioModel = new Scenario();
            $scenarioId = $scenarioModel->createFromAI($programId, $validated);

            echo json_encode(['ok' => true, 'message' => 'Escenario guardado con ID: ' . $scenarioId]);
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('Error al guardar escenario IA: ' . $e->getMessage());
            echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Lista de programas disponibles para aprendices
     */
    public function learnerIndex(): void
    {
        $user = $this->getAuthUser();
        if (!$user) {
            $this->redirect('/login');
            return;
        }

        $allPrograms = $this->programModel->query(
            'SELECT p.id, p.title, p.sector, p.status, p.soft_skills_generated,
                    COUNT(pss.id) as soft_skills_count
             FROM programs p
             LEFT JOIN program_soft_skills pss ON pss.program_id = p.id
             WHERE p.status = :status
             GROUP BY p.id
             ORDER BY p.created_at DESC',
            ['status' => 'completed']
        );

        $softSkillModel = new \App\Models\ProgramSoftSkill();
        foreach ($allPrograms as &$program) {
            $skills = $softSkillModel->getByProgramId((int) $program['id']);
            $program['sample_skills'] = array_column(array_slice($skills, 0, 3), 'soft_skill_name');
        }

        $this->view('programs/learner_index', [
            'title' => 'Programas de Formacion',
            'user' => $user,
            'programs' => $allPrograms,
        ]);
    }

    /**
     * Elimina un programa y sus recursos asociados
     */
    public function delete(string $id): void
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

        $programId = (int) $id;
        if ($programId <= 0) {
            $this->redirect('/instructor/programs');
            return;
        }

        $program = $this->programModel->findByIdForInstructor($programId, (int) $user['id']);
        if (!$program) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Programa no encontrado o no tienes permisos para eliminarlo.',
            ];
            $this->redirect('/instructor/programs');
            return;
        }

        try {
            $softSkillModel = new \App\Models\ProgramSoftSkill();
            $softSkillModel->deleteByProgramId($programId);

            $scenarioModel = new Scenario();
            $scenarioModel->deleteByProgramId($programId);

            $this->programModel->delete($programId);

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Programa eliminado correctamente.',
            ];
        } catch (\Throwable $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Error al eliminar el programa: ' . $e->getMessage(),
            ];
        }

        $this->redirect('/instructor/programs');
    }
}
