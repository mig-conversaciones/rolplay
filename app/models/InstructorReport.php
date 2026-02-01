<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class InstructorReport extends Model
{
    public function platformSummary(): array
    {
        $row = $this->fetchOne(
            'SELECT
                (SELECT COUNT(*) FROM users WHERE role = "aprendiz" AND active = 1) AS total_aprendices,
                (SELECT COUNT(*) FROM sessions) AS total_sessions,
                (SELECT COUNT(*) FROM sessions WHERE completion_percentage >= 100) AS completed_sessions,
                (SELECT COALESCE(AVG(final_score), 0) FROM sessions) AS avg_score,
                (SELECT COALESCE(MAX(final_score), 0) FROM sessions) AS best_score'
        ) ?? [];

        return [
            'total_aprendices' => (int) ($row['total_aprendices'] ?? 0),
            'total_sessions' => (int) ($row['total_sessions'] ?? 0),
            'completed_sessions' => (int) ($row['completed_sessions'] ?? 0),
            'avg_score' => (float) ($row['avg_score'] ?? 0),
            'best_score' => (int) ($row['best_score'] ?? 0),
        ];
    }

    public function aprendizPerformance(int $limit = 50, ?string $ficha = null): array
    {
        $limit = max(1, min(200, $limit));
        $where = '';
        $params = [];

        if ($ficha !== null && $ficha !== '') {
            $where = 'WHERE ficha = :ficha';
            $params['ficha'] = $ficha;
        }

        $sql = "SELECT user_id, name, email, ficha, total_sessions, completed_sessions, average_score, best_score, achievements_count, total_points
                FROM user_performance
                {$where}
                ORDER BY total_points DESC, average_score DESC
                LIMIT {$limit}";

        return $this->fetchAll($sql, $params);
    }

    public function scenarioPerformance(int $limit = 20, ?string $area = null): array
    {
        $limit = max(1, min(100, $limit));
        $params = [];
        $areaFilter = '';
        if ($area !== null && $area !== '') {
            $areaFilter = 'AND sc.area = :area';
            $params['area'] = $area;
        }

        $sql = "SELECT sc.id, sc.title, sc.area, sc.difficulty,
                       COUNT(s.id) AS sessions_count,
                       SUM(CASE WHEN s.completion_percentage >= 100 THEN 1 ELSE 0 END) AS completed_count,
                       COALESCE(AVG(s.final_score), 0) AS avg_score
                FROM scenarios sc
                LEFT JOIN sessions s ON s.scenario_id = sc.id
                WHERE sc.active = 1 {$areaFilter}
                GROUP BY sc.id, sc.title, sc.area, sc.difficulty
                ORDER BY sessions_count DESC, avg_score DESC
                LIMIT {$limit}";

        $rows = $this->fetchAll($sql, $params);
        foreach ($rows as &$r) {
            $r['sessions_count'] = (int) ($r['sessions_count'] ?? 0);
            $r['completed_count'] = (int) ($r['completed_count'] ?? 0);
            $r['avg_score'] = (float) ($r['avg_score'] ?? 0);
        }
        unset($r);

        return $rows;
    }

    public function lowPerformanceAlerts(int $minSessions = 2, float $maxAverage = 40.0, int $limit = 20, ?string $ficha = null): array
    {
        $minSessions = max(1, $minSessions);
        $limit = max(1, min(100, $limit));

        $whereFicha = '';
        $params = [
            'min_sessions' => $minSessions,
            'max_average' => $maxAverage,
        ];

        if ($ficha !== null && $ficha !== '') {
            $whereFicha = 'AND ficha = :ficha';
            $params['ficha'] = $ficha;
        }

        $sql = "SELECT user_id, name, email, ficha, total_sessions, completed_sessions, average_score, best_score, total_points
                FROM user_performance
                WHERE total_sessions >= :min_sessions
                  AND average_score <= :max_average
                  {$whereFicha}
                ORDER BY average_score ASC, total_sessions DESC
                LIMIT {$limit}";

        return $this->fetchAll($sql, $params);
    }

    public function availableFichas(): array
    {
        $rows = $this->fetchAll(
            "SELECT DISTINCT ficha
             FROM users
             WHERE role = 'aprendiz' AND ficha IS NOT NULL AND ficha <> ''
             ORDER BY ficha ASC"
        );

        return array_values(array_filter(array_map(static function (array $r): string {
            return (string) ($r['ficha'] ?? '');
        }, $rows)));
    }

    public function availableAreas(): array
    {
        $rows = $this->fetchAll(
            "SELECT DISTINCT area
             FROM scenarios
             WHERE active = 1 AND area IS NOT NULL AND area <> ''
             ORDER BY area ASC"
        );

        return array_values(array_filter(array_map(static function (array $r): string {
            return (string) ($r['area'] ?? '');
        }, $rows)));
    }
}
