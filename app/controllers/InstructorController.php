<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\InstructorReport;
use App\Services\ReportService;

final class InstructorController extends Controller
{
    public function dashboard(): void
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

        $report = new InstructorReport();
        $selectedFicha = isset($_GET['ficha']) ? $this->sanitize((string) $_GET['ficha']) : '';
        $selectedArea = isset($_GET['area']) ? $this->sanitize((string) $_GET['area']) : '';

        $summary = $report->platformSummary();
        $aprendices = $report->aprendizPerformance(60, $selectedFicha !== '' ? $selectedFicha : null);
        $scenarios = $report->scenarioPerformance(30, $selectedArea !== '' ? $selectedArea : null);
        $alerts = $report->lowPerformanceAlerts(2, 40.0, 20, $selectedFicha !== '' ? $selectedFicha : null);
        $fichas = $report->availableFichas();
        $areas = $report->availableAreas();

        $this->view('instructor/dashboard', [
            'title' => 'Dashboard Instructor',
            'user' => $user,
            'summary' => $summary,
            'aprendices' => $aprendices,
            'scenarios' => $scenarios,
            'alerts' => $alerts,
            'fichas' => $fichas,
            'areas' => $areas,
            'selectedFicha' => $selectedFicha,
            'selectedArea' => $selectedArea,
        ]);
    }

    /**
     * Genera reporte individual de un aprendiz en PDF
     */
    public function generateIndividualReport(string $userId): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $aprendizId = (int)$userId;
        if ($aprendizId <= 0) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'ID de aprendiz inválido',
            ];
            $this->redirect('/instructor');
            return;
        }

        try {
            $reportService = new ReportService();
            $filepath = $reportService->generateIndividualReport($aprendizId, (int)$user['id']);

            // Descargar el archivo
            $this->downloadFile($filepath);
        } catch (\Exception $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Error al generar reporte: ' . $e->getMessage(),
            ];
            $this->redirect('/instructor');
        }
    }

    /**
     * Genera reporte grupal por ficha en PDF
     */
    public function generateGroupReport(): void
    {
        $user = $this->getAuthUser();
        if (!$user || !in_array($user['role'], ['instructor', 'admin'], true)) {
            $this->redirect('/');
            return;
        }

        $ficha = $this->sanitize((string)($_GET['ficha'] ?? ''));
        if ($ficha === '') {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Debe especificar una ficha',
            ];
            $this->redirect('/instructor');
            return;
        }

        try {
            $reportService = new ReportService();
            $filepath = $reportService->generateGroupReport($ficha, (int)$user['id']);

            // Descargar el archivo
            $this->downloadFile($filepath);
        } catch (\Exception $e) {
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Error al generar reporte: ' . $e->getMessage(),
            ];
            $this->redirect('/instructor');
        }
    }

    /**
     * Descarga un archivo PDF
     */
    private function downloadFile(string $filepath): void
    {
        if (!file_exists($filepath)) {
            throw new \Exception('Archivo no encontrado');
        }

        $filename = basename($filepath);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        readfile($filepath);

        // Eliminar el archivo después de descargar (opcional)
        // unlink($filepath);

        exit;
    }
}
