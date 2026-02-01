<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Program extends Model
{
    public function listByInstructor(int $instructorId): array
    {
        return $this->fetchAll(
            'SELECT id, title, codigo_programa, status, estado_analisis, created_at, updated_at
             FROM programs
             WHERE instructor_id = :instructor_id
             ORDER BY created_at DESC',
            ['instructor_id' => $instructorId]
        );
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO programs (instructor_id, title, codigo_programa, competencias_text, perfil_egreso_text, analysis_json, status, pdf_path, pdf_original_name, created_at, updated_at)
                VALUES (:instructor_id, :title, :codigo_programa, :competencias_text, :perfil_egreso_text, :analysis_json, :status, :pdf_path, :pdf_original_name, NOW(), NOW())';

        $this->query($sql, [
            'instructor_id' => $data['instructor_id'],
            'title' => $data['title'],
            'codigo_programa' => $data['codigo_programa'] ?: null,
            'competencias_text' => $data['competencias_text'] ?? null,
            'perfil_egreso_text' => $data['perfil_egreso_text'] ?? null,
            'analysis_json' => $data['analysis_json'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'pdf_path' => $data['pdf_path'] ?? null,
            'pdf_original_name' => $data['pdf_original_name'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function findByIdForInstructor(int $id, int $instructorId): ?array
    {
        $row = $this->fetchOne(
            'SELECT id, instructor_id, title, codigo_programa, competencias_text, perfil_egreso_text, analysis_json, status, estado_analisis, resultado_gemini, pdf_path, pdf_original_name, created_at, updated_at
             FROM programs
             WHERE id = :id AND instructor_id = :instructor_id
             LIMIT 1',
            [
                'id' => $id,
                'instructor_id' => $instructorId,
            ]
        );

        if (!$row) {
            return null;
        }

        $analysis = json_decode((string) ($row['analysis_json'] ?? ''), true);
        $row['analysis'] = is_array($analysis) ? $analysis : null;

        return $row;
    }

    public function updateAnalysis(int $id, array $analysis, string $status = 'completed'): void
    {
        $this->query(
            'UPDATE programs
             SET analysis_json = :analysis_json,
                 status = :status,
                 updated_at = NOW()
             WHERE id = :id',
            [
                'analysis_json' => json_encode($analysis, JSON_UNESCAPED_UNICODE),
                'status' => $status,
                'id' => $id,
            ]
        );
    }

    public function updateAnalysisAsyncFields(int $id, string $estadoAnalisis, ?array $resultadoGemini = null): void
    {
        $this->query(
            'UPDATE programs
             SET estado_analisis = :estado_analisis,
                 resultado_gemini = :resultado_gemini,
                 updated_at = NOW()
             WHERE id = :id',
            [
                'estado_analisis' => $estadoAnalisis,
                'resultado_gemini' => $resultadoGemini !== null
                    ? json_encode($resultadoGemini, JSON_UNESCAPED_UNICODE)
                    : null,
                'id' => $id,
            ]
        );
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->query(
            'UPDATE programs
             SET status = :status,
                 updated_at = NOW()
             WHERE id = :id',
            [
                'status' => $status,
                'id' => $id,
            ]
        );
    }

    public function updateSoftSkillsInfo(int $id, string $sector, bool $generated): void
    {
        $this->query(
            'UPDATE programs
             SET sector = :sector,
                 soft_skills_generated = :generated,
                 updated_at = NOW()
             WHERE id = :id',
            [
                'sector' => $sector,
                'id' => $id,
                'generated' => $generated ? 1 : 0,
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->query('DELETE FROM programs WHERE id = :id', ['id' => $id]);
    }
}
