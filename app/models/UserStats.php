<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class UserStats extends Model
{
    public function recalculateForUser(int $userId): void
    {
        $summary = $this->fetchOne(
            'SELECT COUNT(*) AS total_sessions,
                    SUM(CASE WHEN completion_percentage >= 100 THEN 1 ELSE 0 END) AS completed_sessions,
                    COALESCE(SUM(final_score), 0) AS total_points,
                    COALESCE(AVG(final_score), 0) AS average_score
             FROM sessions
             WHERE user_id = :user_id',
            ['user_id' => $userId]
        ) ?? [];

        $completedScenarioRows = $this->fetchAll(
            'SELECT DISTINCT scenario_id
             FROM sessions
             WHERE user_id = :user_id AND completion_percentage >= 100
             ORDER BY scenario_id ASC',
            ['user_id' => $userId]
        );
        $completedScenarioIds = array_map(static fn (array $r): int => (int) $r['scenario_id'], $completedScenarioRows);

        $bestCompetence = $this->computeBestCompetence($userId);

        $sql = 'INSERT INTO user_stats (user_id, total_sessions, completed_sessions, total_points, average_score, best_competence, scenarios_completed_ids, last_activity)
                VALUES (:user_id, :total_sessions, :completed_sessions, :total_points, :average_score, :best_competence, :scenarios_completed_ids, NOW())
                ON DUPLICATE KEY UPDATE
                    total_sessions = VALUES(total_sessions),
                    completed_sessions = VALUES(completed_sessions),
                    total_points = VALUES(total_points),
                    average_score = VALUES(average_score),
                    best_competence = VALUES(best_competence),
                    scenarios_completed_ids = VALUES(scenarios_completed_ids),
                    last_activity = NOW()';

        $this->query($sql, [
            'user_id' => $userId,
            'total_sessions' => (int) ($summary['total_sessions'] ?? 0),
            'completed_sessions' => (int) ($summary['completed_sessions'] ?? 0),
            'total_points' => (int) ($summary['total_points'] ?? 0),
            'average_score' => (float) ($summary['average_score'] ?? 0),
            'best_competence' => $bestCompetence,
            'scenarios_completed_ids' => json_encode($completedScenarioIds, JSON_UNESCAPED_UNICODE),
        ]);
    }

    private function computeBestCompetence(int $userId): ?string
    {
        $rows = $this->fetchAll(
            'SELECT scores_json
             FROM sessions
             WHERE user_id = :user_id AND completion_percentage >= 100',
            ['user_id' => $userId]
        );

        if (empty($rows)) {
            return null;
        }

        $totals = [];
        foreach ($rows as $row) {
            $scores = json_decode((string) ($row['scores_json'] ?? '{}'), true);
            if (!is_array($scores)) {
                continue;
            }
            foreach ($scores as $k => $v) {
                $key = (string) $k;
                $totals[$key] = (int) ($totals[$key] ?? 0) + (int) $v;
            }
        }

        if (empty($totals)) {
            return null;
        }

        arsort($totals);
        $bestKey = array_key_first($totals);
        return $bestKey !== null ? (string) $bestKey : null;
    }
}