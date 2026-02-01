<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Decision extends Model
{
    /**
     * Crea una decisión (método original para compatibilidad)
     */
    public function createLegacy(int $sessionId, int $stepNumber, int $optionChosen, array $scoresImpact, ?string $feedbackType): int
    {
        $sql = 'INSERT INTO decisions (session_id, step_number, option_chosen, scores_impact, feedback_type, timestamp)
                VALUES (:session_id, :step_number, :option_chosen, :scores_impact, :feedback_type, NOW())';

        $this->query($sql, [
            'session_id' => $sessionId,
            'step_number' => $stepNumber,
            'option_chosen' => $optionChosen,
            'scores_impact' => json_encode($scoresImpact, JSON_UNESCAPED_UNICODE),
            'feedback_type' => $feedbackType,
        ]);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Crea una decisión (nuevo método flexible con array)
     *
     * @param array $data Datos de la decisión
     * @return int ID de la decisión creada
     */
    public function create(array $data): int
    {
        $fields = [
            'session_id' => (int)($data['session_id'] ?? 0),
            'stage' => (int)($data['stage'] ?? 1),
            'step_number' => (int)($data['step_number'] ?? 1),
            'option_chosen' => (int)($data['option_chosen'] ?? 0),
            'scores_impact' => $data['scores_impact'] ?? json_encode([]),
            'feedback_type' => $data['feedback_type'] ?? null,
        ];

        $sql = 'INSERT INTO decisions (
                    session_id, stage, step_number, option_chosen,
                    scores_impact, feedback_type, timestamp
                ) VALUES (
                    :session_id, :stage, :step_number, :option_chosen,
                    :scores_impact, :feedback_type, NOW()
                )';

        $this->query($sql, $fields);

        return (int) $this->db->lastInsertId();
    }

    public function listBySession(int $sessionId): array
    {
        $rows = $this->fetchAll(
            'SELECT id, session_id, stage, step_number, option_chosen, scores_impact, feedback_type, timestamp
             FROM decisions
             WHERE session_id = :session_id
             ORDER BY stage ASC, step_number ASC, id ASC',
            ['session_id' => $sessionId]
        );

        foreach ($rows as &$row) {
            $decoded = json_decode((string) ($row['scores_impact'] ?? '{}'), true);
            $row['scores_impact'] = is_array($decoded) ? $decoded : [];
        }
        unset($row);

        return $rows;
    }

    /**
     * Obtiene la opción elegida en una etapa específica
     *
     * @param int $sessionId
     * @param int $stage Número de etapa (1, 2, 3)
     * @return int Índice de la opción elegida (0-2)
     */
    public function getChoiceByStage(int $sessionId, int $stage): int
    {
        $row = $this->fetchOne(
            'SELECT option_chosen FROM decisions
             WHERE session_id = :session_id AND stage = :stage
             ORDER BY id DESC
             LIMIT 1',
            [
                'session_id' => $sessionId,
                'stage' => $stage,
            ]
        );

        return $row ? (int)$row['option_chosen'] : 0;
    }
}
