<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * Modelo para gestionar las soft skills identificadas por programa
 */
final class ProgramSoftSkill extends Model
{
    protected string $table = 'program_soft_skills';

    /**
     * Obtiene todas las soft skills de un programa
     *
     * @param int $programId
     * @return array
     */
    public function getByProgramId(int $programId): array
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                program_id,
                soft_skill_name,
                weight,
                criteria_json,
                description,
                created_at,
                updated_at
            FROM {$this->table}
            WHERE program_id = ?
            ORDER BY weight DESC
        ");

        $stmt->execute([$programId]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Decodificar JSON de criterios
        foreach ($results as &$skill) {
            $skill['criteria_json'] = json_decode($skill['criteria_json'], true) ?? [];
            $skill['weight'] = (float)$skill['weight'];
        }

        return $results;
    }

    /**
     * Guarda las soft skills identificadas para un programa
     *
     * @param int $programId
     * @param array $softSkills Array de soft skills desde el análisis de IA
     * @return int Número de soft skills guardadas
     */
    public function saveSoftSkills(int $programId, array $softSkills): int
    {
        // Primero eliminar soft skills existentes del programa
        $this->deleteByProgramId($programId);

        $count = 0;
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (
                program_id,
                soft_skill_name,
                weight,
                criteria_json,
                description
            ) VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($softSkills as $skill) {
            $softSkillName = $skill['nombre'] ?? $skill['name'] ?? '';
            $weight = (float)($skill['peso'] ?? $skill['weight'] ?? 20.0);
            $criteriaJson = json_encode($skill['criterios'] ?? $skill['criteria'] ?? []);
            $description = $skill['descripcion'] ?? $skill['description'] ?? '';

            if (empty($softSkillName)) {
                continue;
            }

            $stmt->execute([
                $programId,
                $softSkillName,
                $weight,
                $criteriaJson,
                $description
            ]);

            $count++;
        }

        return $count;
    }

    /**
     * Elimina todas las soft skills de un programa
     *
     * @param int $programId
     * @return bool
     */
    public function deleteByProgramId(int $programId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE program_id = ?");
        return $stmt->execute([$programId]);
    }

    /**
     * Obtiene una soft skill específica por ID
     *
     * @param int $id
     * @return array|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                program_id,
                soft_skill_name,
                weight,
                criteria_json,
                description,
                created_at,
                updated_at
            FROM {$this->table}
            WHERE id = ?
        ");

        $stmt->execute([$id]);
        $skill = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$skill) {
            return null;
        }

        $skill['criteria_json'] = json_decode($skill['criteria_json'], true) ?? [];
        $skill['weight'] = (float)$skill['weight'];

        return $skill;
    }

    /**
     * Verifica si un programa ya tiene soft skills identificadas
     *
     * @param int $programId
     * @return bool
     */
    public function hasSoftSkills(int $programId): bool
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM {$this->table}
            WHERE program_id = ?
        ");

        $stmt->execute([$programId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Valida que los pesos de las soft skills sumen 100
     *
     * @param int $programId
     * @return array ['valid' => bool, 'sum' => float]
     */
    public function validateWeights(int $programId): array
    {
        $stmt = $this->db->prepare("
            SELECT SUM(weight) as total_weight
            FROM {$this->table}
            WHERE program_id = ?
        ");

        $stmt->execute([$programId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $sum = (float)($result['total_weight'] ?? 0);
        $valid = abs($sum - 100.0) < 0.01; // Tolerancia de 0.01 para errores de redondeo

        return [
            'valid' => $valid,
            'sum' => $sum
        ];
    }

    /**
     * Obtiene estadísticas de soft skills por sector
     *
     * @param string $sector
     * @return array
     */
    public function getStatsBySector(string $sector): array
    {
        $stmt = $this->db->prepare("
            SELECT
                pss.soft_skill_name,
                COUNT(*) as frequency,
                AVG(pss.weight) as avg_weight
            FROM {$this->table} pss
            INNER JOIN programs p ON pss.program_id = p.id
            WHERE p.sector = ?
            GROUP BY pss.soft_skill_name
            ORDER BY frequency DESC, avg_weight DESC
        ");

        $stmt->execute([$sector]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene las soft skills más comunes en todos los programas
     *
     * @param int $limit
     * @return array
     */
    public function getMostCommon(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT
                soft_skill_name,
                COUNT(*) as frequency,
                AVG(weight) as avg_weight
            FROM {$this->table}
            GROUP BY soft_skill_name
            ORDER BY frequency DESC, avg_weight DESC
            LIMIT ?
        ");

        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
