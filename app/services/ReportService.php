<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\GameSession;
use App\Models\InstructorReport;
use TCPDF;

/**
 * Servicio de generación de reportes PDF
 * RF-014: Reportes y Análisis
 */
final class ReportService
{
    private User $userModel;
    private GameSession $sessionModel;
    private InstructorReport $reportModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->sessionModel = new GameSession();
        $this->reportModel = new InstructorReport();
    }

    /**
     * Genera reporte individual de un aprendiz en PDF
     */
    public function generateIndividualReport(int $userId, int $instructorId): string
    {
        // Obtener datos del aprendiz
        $user = $this->userModel->findById($userId);
        if (!$user || $user['role'] !== 'aprendiz') {
            throw new \Exception('Aprendiz no encontrado');
        }

        // Obtener estadísticas
        $stats = $this->reportModel->getAprendizPerformance($userId);
        $sessions = $this->reportModel->getSessionHistory($userId);

        // Crear PDF
        $pdf = $this->createPDF();

        // Agregar página
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->SetTextColor(57, 169, 0); // Verde SENA
        $pdf->Cell(0, 15, 'RolPlay EDU - Reporte Individual', 0, 1, 'C');

        // Línea separadora
        $pdf->SetDrawColor(57, 169, 0);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, 35, 195, 35);

        // Información del aprendiz
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 48, 77); // Azul SENA
        $pdf->Cell(0, 10, 'Información del Aprendiz', 0, 1);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 7, 'Nombre:', 0, 0, 'L', false);
        $pdf->Cell(0, 7, $user['name'], 0, 1);
        $pdf->Cell(40, 7, 'Email:', 0, 0, 'L', false);
        $pdf->Cell(0, 7, $user['email'], 0, 1);
        $pdf->Cell(40, 7, 'Ficha:', 0, 0, 'L', false);
        $pdf->Cell(0, 7, $user['ficha'] ?? 'N/A', 0, 1);
        $pdf->Cell(40, 7, 'Fecha:', 0, 0, 'L', false);
        $pdf->Cell(0, 7, date('d/m/Y H:i'), 0, 1);

        // Estadísticas generales
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 48, 77);
        $pdf->Cell(0, 10, 'Estadísticas Generales', 0, 1);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0, 0, 0);

        // Crear tabla de estadísticas
        $pdf->SetFillColor(240, 240, 240);
        $pdf->Cell(90, 8, 'Sesiones Totales', 1, 0, 'L', true);
        $pdf->Cell(90, 8, $stats['total_sessions'] ?? 0, 1, 1, 'C');
        $pdf->Cell(90, 8, 'Sesiones Completadas', 1, 0, 'L', true);
        $pdf->Cell(90, 8, $stats['completed_sessions'] ?? 0, 1, 1, 'C');
        $pdf->Cell(90, 8, 'Promedio General', 1, 0, 'L', true);
        $pdf->Cell(90, 8, number_format((float)($stats['average_score'] ?? 0), 1), 1, 1, 'C');
        $pdf->Cell(90, 8, 'Mejor Puntuación', 1, 0, 'L', true);
        $pdf->Cell(90, 8, $stats['best_score'] ?? 0, 1, 1, 'C');
        $pdf->Cell(90, 8, 'Puntos Totales', 1, 0, 'L', true);
        $pdf->Cell(90, 8, $stats['total_points'] ?? 0, 1, 1, 'C');

        // Competencias
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 48, 77);
        $pdf->Cell(0, 10, 'Promedios por Competencia', 0, 1);

        // Gráfico de barras ASCII (simple)
        $competences = [
            'Comunicación' => (float)($stats['avg_comunicacion'] ?? 0),
            'Liderazgo' => (float)($stats['avg_liderazgo'] ?? 0),
            'Trabajo en Equipo' => (float)($stats['avg_trabajo_equipo'] ?? 0),
            'Toma de Decisiones' => (float)($stats['avg_toma_decisiones'] ?? 0),
        ];

        $pdf->SetFont('helvetica', '', 10);
        foreach ($competences as $name => $value) {
            $barWidth = ($value / 100) * 120; // Escala a 120mm
            $pdf->Cell(60, 7, $name, 0, 0);
            $pdf->SetFillColor(57, 169, 0);
            $pdf->Cell($barWidth, 7, number_format($value, 1), 1, 0, 'C', true);
            $pdf->Ln();
        }

        // Historial de sesiones
        if (!empty($sessions)) {
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor(0, 48, 77);
            $pdf->Cell(0, 10, 'Historial de Sesiones', 0, 1);

            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetFillColor(220, 220, 220);
            $pdf->Cell(70, 7, 'Escenario', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Fecha', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Puntuación', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Estado', 1, 0, 'C', true);
            $pdf->Cell(30, 7, 'Completitud', 1, 1, 'C', true);

            $pdf->SetFont('helvetica', '', 8);
            foreach ($sessions as $session) {
                $pdf->Cell(70, 6, substr($session['scenario_title'] ?? 'N/A', 0, 30), 1, 0);
                $pdf->Cell(30, 6, date('d/m/Y', strtotime($session['started_at'])), 1, 0, 'C');
                $pdf->Cell(30, 6, $session['final_score'] ?? 'N/A', 1, 0, 'C');
                $pdf->Cell(30, 6, $session['status'] ?? 'N/A', 1, 0, 'C');
                $pdf->Cell(30, 6, number_format((float)($session['completion_percentage'] ?? 0), 0) . '%', 1, 1, 'C');
            }
        }

        // Recomendaciones
        $pdf->AddPage();
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 48, 77);
        $pdf->Cell(0, 10, 'Recomendaciones', 0, 1);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(0, 6, $this->generateRecommendations($competences), 0, 'L');

        // Generar PDF
        $filename = 'reporte_individual_' . $userId . '_' . date('YmdHis') . '.pdf';
        $filepath = $this->getSaveDirectory() . '/' . $filename;

        $pdf->Output($filepath, 'F');

        return $filepath;
    }

    /**
     * Genera reporte grupal por ficha en PDF
     */
    public function generateGroupReport(string $ficha, int $instructorId): string
    {
        // Obtener aprendices de la ficha
        $aprendices = $this->reportModel->getLearnersByFicha($ficha);

        if (empty($aprendices)) {
            throw new \Exception('No hay aprendices en esta ficha');
        }

        // Crear PDF
        $pdf = $this->createPDF();
        $pdf->AddPage();

        // Título
        $pdf->SetFont('helvetica', 'B', 24);
        $pdf->SetTextColor(57, 169, 0);
        $pdf->Cell(0, 15, 'RolPlay EDU - Reporte Grupal', 0, 1, 'C');

        $pdf->SetDrawColor(57, 169, 0);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(15, 35, 195, 35);

        // Información de la ficha
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 48, 77);
        $pdf->Cell(0, 10, 'Información de la Ficha', 0, 1);

        $pdf->SetFont('helvetica', '', 11);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 7, 'Ficha:', 0, 0);
        $pdf->Cell(0, 7, $ficha, 0, 1);
        $pdf->Cell(40, 7, 'Total Aprendices:', 0, 0);
        $pdf->Cell(0, 7, (string)count($aprendices), 0, 1);
        $pdf->Cell(40, 7, 'Fecha:', 0, 0);
        $pdf->Cell(0, 7, date('d/m/Y H:i'), 0, 1);

        // Tabla de aprendices
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(0, 48, 77);
        $pdf->Cell(0, 10, 'Rendimiento Individual', 0, 1);

        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell(60, 7, 'Nombre', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Sesiones', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Completadas', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Promedio', 1, 0, 'C', true);
        $pdf->Cell(30, 7, 'Puntos', 1, 1, 'C', true);

        $pdf->SetFont('helvetica', '', 7);
        $totalSessions = 0;
        $totalCompleted = 0;
        $totalAvg = 0;
        $totalPoints = 0;

        foreach ($aprendices as $aprendiz) {
            $pdf->Cell(60, 6, substr($aprendiz['name'], 0, 25), 1, 0);
            $pdf->Cell(30, 6, $aprendiz['total_sessions'] ?? 0, 1, 0, 'C');
            $pdf->Cell(30, 6, $aprendiz['completed_sessions'] ?? 0, 1, 0, 'C');
            $pdf->Cell(30, 6, number_format((float)($aprendiz['average_score'] ?? 0), 1), 1, 0, 'C');
            $pdf->Cell(30, 6, $aprendiz['total_points'] ?? 0, 1, 1, 'C');

            $totalSessions += (int)($aprendiz['total_sessions'] ?? 0);
            $totalCompleted += (int)($aprendiz['completed_sessions'] ?? 0);
            $totalAvg += (float)($aprendiz['average_score'] ?? 0);
            $totalPoints += (int)($aprendiz['total_points'] ?? 0);
        }

        // Promedios generales
        $count = count($aprendices);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->SetFillColor(57, 169, 0);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->Cell(60, 7, 'PROMEDIOS', 1, 0, 'C', true);
        $pdf->Cell(30, 7, number_format($totalSessions / $count, 1), 1, 0, 'C', true);
        $pdf->Cell(30, 7, number_format($totalCompleted / $count, 1), 1, 0, 'C', true);
        $pdf->Cell(30, 7, number_format($totalAvg / $count, 1), 1, 0, 'C', true);
        $pdf->Cell(30, 7, number_format($totalPoints / $count, 0), 1, 1, 'C', true);

        // Generar PDF
        $filename = 'reporte_grupal_' . str_replace(' ', '_', $ficha) . '_' . date('YmdHis') . '.pdf';
        $filepath = $this->getSaveDirectory() . '/' . $filename;

        $pdf->Output($filepath, 'F');

        return $filepath;
    }

    /**
     * Crea una instancia de TCPDF con configuración base
     */
    private function createPDF(): TCPDF
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configuración del documento
        $pdf->SetCreator('RolPlay EDU');
        $pdf->SetAuthor('SENA - Centro Agropecuario de Buga');
        $pdf->SetTitle('Reporte RolPlay EDU');
        $pdf->SetSubject('Análisis de Desempeño');

        // Márgenes
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);

        // Quitar header y footer por defecto
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);

        return $pdf;
    }

    /**
     * Obtiene el directorio donde guardar los reportes
     */
    private function getSaveDirectory(): string
    {
        $dir = dirname(__DIR__, 2) . '/public/reports';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir;
    }

    /**
     * Genera recomendaciones basadas en competencias
     */
    private function generateRecommendations(array $competences): string
    {
        $recommendations = "Basado en tu desempeño:\n\n";

        foreach ($competences as $name => $value) {
            if ($value < 50) {
                $recommendations .= "• {$name}: Necesitas mejorar significativamente en esta área. ";
                $recommendations .= "Te recomendamos practicar más escenarios enfocados en esta competencia.\n\n";
            } elseif ($value < 70) {
                $recommendations .= "• {$name}: Buen progreso, pero aún hay espacio para mejorar. ";
                $recommendations .= "Continúa practicando para alcanzar la excelencia.\n\n";
            } else {
                $recommendations .= "• {$name}: ¡Excelente desempeño! Mantén este nivel de rendimiento.\n\n";
            }
        }

        $recommendations .= "Recuerda que la práctica constante es clave para el desarrollo de competencias transversales.";

        return $recommendations;
    }
}
