<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Models\UserStats;

final class GameSession extends Model
{
    public function start(int $userId, int $scenarioId): int
    {
        $sql = 'INSERT INTO sessions (user_id, scenario_id, scores_json, final_score, decisions_count, completion_percentage, started_at)
                VALUES (:user_id, :scenario_id, :scores_json, 0, 0, 0, NOW())';

        $this->query($sql, [
            'user_id' => $userId,
            'scenario_id' => $scenarioId,
            'scores_json' => json_encode(new \stdClass(), JSON_UNESCAPED_UNICODE),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function getScores(int $sessionId): array
    {
        $row = $this->fetchOne('SELECT scores_json FROM sessions WHERE id = :id LIMIT 1', ['id' => $sessionId]);
        if (!$row) {
            return [];
        }

        $scores = json_decode((string) $row['scores_json'], true);
        return is_array($scores) ? $scores : [];
    }

    /**
     * Actualiza los scores de la sesión
     *
     * @param int $sessionId ID de la sesión
     * @param array $scores Array de puntajes por competencia
     * @param int|null $decisionsCount Número de decisiones (opcional)
     * @param float|null $completionPercentage Porcentaje de completitud (opcional)
     */
    public function updateScores(int $sessionId, array $scores, ?int $decisionsCount = null, ?float $completionPercentage = null): void
    {
        $finalScore = 0;
        foreach ($scores as $value) {
            $finalScore += (int) $value;
        }

        // Si no se proporcionan decisionsCount y completionPercentage, solo actualizar scores
        if ($decisionsCount === null && $completionPercentage === null) {
            $sql = 'UPDATE sessions
                    SET scores_json = :scores_json,
                        final_score = :final_score
                    WHERE id = :id';

            $this->query($sql, [
                'scores_json' => json_encode($scores, JSON_UNESCAPED_UNICODE),
                'final_score' => $finalScore,
                'id' => $sessionId,
            ]);
        } else {
            // Actualización completa (sesiones estáticas)
            $sql = 'UPDATE sessions
                    SET scores_json = :scores_json,
                        final_score = :final_score,
                        decisions_count = :decisions_count,
                        completion_percentage = :completion_percentage,
                        completed_at = CASE WHEN :completion_percentage >= 100 THEN NOW() ELSE completed_at END
                    WHERE id = :id';

            $this->query($sql, [
                'scores_json' => json_encode($scores, JSON_UNESCAPED_UNICODE),
                'final_score' => $finalScore,
                'decisions_count' => $decisionsCount ?? 0,
                'completion_percentage' => $completionPercentage ?? 0,
                'id' => $sessionId,
            ]);

            // Recalcular estadisticas acumuladas cuando la sesion se completa
            if ($completionPercentage >= 100.0) {
                $row = $this->fetchOne('SELECT user_id FROM sessions WHERE id = :id LIMIT 1', ['id' => $sessionId]);
                if ($row && isset($row['user_id'])) {
                    $stats = new UserStats();
                    $stats->recalculateForUser((int) $row['user_id']);
                }
            }
        }
    }

    public function historyByUser(int $userId, int $limit = 20): array
    {
        $limit = max(1, min(100, $limit));
        $sql = "SELECT s.id, s.scenario_id, sc.title AS scenario_title, sc.area, sc.difficulty,
                       s.final_score, s.decisions_count, s.completion_percentage, s.started_at, s.completed_at,
                       s.scores_json
                FROM sessions s
                INNER JOIN scenarios sc ON sc.id = s.scenario_id
                WHERE s.user_id = :user_id
                ORDER BY s.started_at DESC
                LIMIT {$limit}";

        $rows = $this->fetchAll($sql, ['user_id' => $userId]);
        foreach ($rows as &$row) {
            $decoded = json_decode((string) ($row['scores_json'] ?? '{}'), true);
            $row['scores'] = is_array($decoded) ? $decoded : [];
            unset($row['scores_json']);
        }
        unset($row);

        return $rows;
    }

    public function aggregateStatsByUser(int $userId): array
    {
        $sql = 'SELECT COUNT(*) AS total_sessions,
                       SUM(CASE WHEN completion_percentage >= 100 THEN 1 ELSE 0 END) AS completed_sessions,
                       COALESCE(SUM(final_score), 0) AS total_points,
                       COALESCE(AVG(final_score), 0) AS average_score,
                       COALESCE(MAX(final_score), 0) AS best_score,
                       COALESCE(MAX(started_at), NULL) AS last_activity
                FROM sessions
                WHERE user_id = :user_id';

        $row = $this->fetchOne($sql, ['user_id' => $userId]) ?? [];

        return [
            'total_sessions' => (int) ($row['total_sessions'] ?? 0),
            'completed_sessions' => (int) ($row['completed_sessions'] ?? 0),
            'total_points' => (int) ($row['total_points'] ?? 0),
            'average_score' => (float) ($row['average_score'] ?? 0),
            'best_score' => (int) ($row['best_score'] ?? 0),
            'last_activity' => $row['last_activity'] ?? null,
        ];
    }

    public function completedScenarioIdsByUser(int $userId): array
    {
        $rows = $this->fetchAll(
            'SELECT DISTINCT scenario_id
             FROM sessions
             WHERE user_id = :user_id
               AND scenario_id IS NOT NULL
               AND (completion_percentage >= 100 OR status = "completed" OR completed_at IS NOT NULL)',
            ['user_id' => $userId]
        );

        $ids = [];
        foreach ($rows as $row) {
            $sid = (int) ($row['scenario_id'] ?? 0);
            if ($sid > 0) {
                $ids[] = $sid;
            }
        }

        return array_values(array_unique($ids));
    }

    /**
     * Obtiene estadísticas globales del sistema
     *
     * @return array
     */
    public function getGlobalStats(): array
    {
        $sql = 'SELECT
                    COUNT(*) as total_sessions,
                    COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_sessions,
                    COUNT(CASE WHEN status IN ("pending", "in_progress") THEN 1 END) as active_sessions,
                    COUNT(CASE WHEN is_dynamic = 1 THEN 1 END) as dynamic_sessions,
                    COALESCE(AVG(CASE WHEN status = "completed" THEN final_score END), 0) as avg_score
                FROM sessions';

        $row = $this->fetchOne($sql, []) ?? [];

        return [
            'total_sessions' => (int) ($row['total_sessions'] ?? 0),
            'completed_sessions' => (int) ($row['completed_sessions'] ?? 0),
            'active_sessions' => (int) ($row['active_sessions'] ?? 0),
            'dynamic_sessions' => (int) ($row['dynamic_sessions'] ?? 0),
            'avg_score' => (float) ($row['avg_score'] ?? 0),
        ];
    }

    public function findByIdForUser(int $sessionId, int $userId): ?array
    {
        // Modificado para soportar sesiones dinámicas (LEFT JOIN en vez de INNER JOIN)
        $row = $this->fetchOne(
            'SELECT s.id, s.user_id, s.scenario_id, s.program_id, s.started_at, s.completed_at,
                    s.final_score, s.decisions_count, s.completion_percentage, s.scores_json,
                    s.is_dynamic, s.current_stage, s.stage1_json, s.stage2_json, s.stage3_json, s.status,
                    sc.title AS scenario_title, sc.area, sc.difficulty, sc.description
             FROM sessions s
             LEFT JOIN scenarios sc ON sc.id = s.scenario_id
             WHERE s.id = :id AND s.user_id = :user_id
             LIMIT 1',
            [
                'id' => $sessionId,
                'user_id' => $userId,
            ]
        );

        if (!$row) {
            return null;
        }

        $decoded = json_decode((string) ($row['scores_json'] ?? '{}'), true);
        $row['scores'] = is_array($decoded) ? $decoded : [];
        unset($row['scores_json']);

        return $row;
    }

    /**
     * Crea una nueva sesión (genérico, soporta dinámicas y estáticas)
     *
     * @param array $data Datos de la sesión
     * @return int ID de la sesión creada
     */
    public function create(array $data): int
    {
        $fields = [
            'user_id' => (int)($data['user_id'] ?? 0),
            'scenario_id' => isset($data['scenario_id']) ? (int)$data['scenario_id'] : null,
            'program_id' => isset($data['program_id']) ? (int)$data['program_id'] : null,
            'is_dynamic' => (bool)($data['is_dynamic'] ?? false),
            'current_stage' => (int)($data['current_stage'] ?? 0),
            'stage1_json' => $data['stage1_json'] ?? null,
            'stage2_json' => $data['stage2_json'] ?? null,
            'stage3_json' => $data['stage3_json'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'scores_json' => $data['scores_json'] ?? json_encode([]),
            'final_score' => (int)($data['final_score'] ?? 0),
            'decisions_count' => (int)($data['decisions_count'] ?? 0),
            'completion_percentage' => (float)($data['completion_percentage'] ?? 0),
        ];

        $sql = 'INSERT INTO sessions (
                    user_id, scenario_id, program_id, is_dynamic, current_stage,
                    stage1_json, stage2_json, stage3_json, status,
                    scores_json, final_score, decisions_count, completion_percentage,
                    started_at
                ) VALUES (
                    :user_id, :scenario_id, :program_id, :is_dynamic, :current_stage,
                    :stage1_json, :stage2_json, :stage3_json, :status,
                    :scores_json, :final_score, :decisions_count, :completion_percentage,
                    NOW()
                )';

        $this->query($sql, $fields);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Guarda el contenido generado de una etapa
     *
     * @param int $sessionId
     * @param int $stage Número de etapa (1, 2, 3)
     * @param array $content Contenido JSON de la etapa
     */
    public function saveStage(int $sessionId, int $stage, array $content): void
    {
        if ($stage < 1 || $stage > 3) {
            throw new \RuntimeException("Etapa inválida: $stage");
        }

        $column = "stage{$stage}_json";
        $contentJson = json_encode($content, JSON_UNESCAPED_UNICODE);

        $sql = "UPDATE sessions SET {$column} = :content WHERE id = :id";

        $this->query($sql, [
            'content' => $contentJson,
            'id' => $sessionId,
        ]);
    }

    /**
     * Actualiza la etapa actual de la sesión
     *
     * @param int $sessionId
     * @param int $stage Nueva etapa actual
     */
    public function updateStage(int $sessionId, int $stage): void
    {
        $sql = 'UPDATE sessions SET current_stage = :stage WHERE id = :id';

        $this->query($sql, [
            'stage' => $stage,
            'id' => $sessionId,
        ]);
    }


    /**
     * Marca la sesión como completada
     *
     * @param int $sessionId
     */
    public function complete(int $sessionId): void
    {
        $sql = 'UPDATE sessions
                SET status = :status,
                    completion_percentage = 100,
                    completed_at = NOW()
                WHERE id = :id';

        $this->query($sql, [
            'status' => 'completed',
            'id' => $sessionId,
        ]);

        // Recalcular estadísticas del usuario
        $row = $this->fetchOne('SELECT user_id FROM sessions WHERE id = :id LIMIT 1', ['id' => $sessionId]);
        if ($row && isset($row['user_id'])) {
            $stats = new UserStats();
            $stats->recalculateForUser((int) $row['user_id']);
        }
    }
}
