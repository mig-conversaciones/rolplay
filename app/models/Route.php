<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Route extends Model
{
    public function listByInstructor(int $instructorId): array
    {
        $rows = $this->fetchAll(
            'SELECT id, name, description, scenarios_json, assigned_groups, start_date, end_date, active, created_at
             FROM routes
             WHERE instructor_id = :instructor_id
             ORDER BY created_at DESC',
            ['instructor_id' => $instructorId]
        );

        foreach ($rows as &$row) {
            $row['scenario_ids'] = $this->decodeJsonArray($row['scenarios_json'] ?? '[]');
            $row['groups'] = $this->decodeJsonArray($row['assigned_groups'] ?? '[]');
            unset($row['scenarios_json'], $row['assigned_groups']);
        }
        unset($row);

        return $rows;
    }

    public function findByIdForInstructor(int $routeId, int $instructorId): ?array
    {
        $row = $this->fetchOne(
            'SELECT id, name, description, scenarios_json, assigned_groups, start_date, end_date, active, created_at
             FROM routes
             WHERE id = :id AND instructor_id = :instructor_id
             LIMIT 1',
            [
                'id' => $routeId,
                'instructor_id' => $instructorId,
            ]
        );

        return $row ? $this->normalizeRouteRow($row) : null;
    }

    public function findActiveById(int $routeId): ?array
    {
        $row = $this->fetchOne(
            'SELECT id, name, description, instructor_id, scenarios_json, assigned_groups, start_date, end_date, active, created_at
             FROM routes
             WHERE id = :id AND active = 1
             LIMIT 1',
            ['id' => $routeId]
        );

        return $row ? $this->normalizeRouteRow($row) : null;
    }

    public function listForLearnerByFicha(string $ficha): array
    {
        $ficha = trim($ficha);
        if ($ficha === '') {
            return [];
        }

        $rows = $this->fetchAll(
            'SELECT id, name, description, scenarios_json, assigned_groups, start_date, end_date, active, created_at
             FROM routes
             WHERE active = 1
             ORDER BY created_at DESC'
        );

        $filtered = [];
        foreach ($rows as $row) {
            $normalized = $this->normalizeRouteRow($row);
            $groups = $normalized['groups'] ?? [];

            // Si no hay grupos asignados, la ruta es visible para todos.
            if (empty($groups) || in_array($ficha, array_map('strval', $groups), true)) {
                $filtered[] = $normalized;
            }
        }

        return $filtered;
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO routes (name, description, instructor_id, scenarios_json, assigned_groups, start_date, end_date, active, created_at, updated_at)
                VALUES (:name, :description, :instructor_id, :scenarios_json, :assigned_groups, :start_date, :end_date, 1, NOW(), NOW())';

        $this->query($sql, [
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'instructor_id' => $data['instructor_id'],
            'scenarios_json' => json_encode(array_values($data['scenario_ids'] ?? []), JSON_UNESCAPED_UNICODE),
            'assigned_groups' => json_encode(array_values($data['groups'] ?? []), JSON_UNESCAPED_UNICODE),
            'start_date' => $data['start_date'] ?: null,
            'end_date' => $data['end_date'] ?: null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    private function decodeJsonArray(string $json): array
    {
        $decoded = json_decode($json, true);
        return is_array($decoded) ? array_values($decoded) : [];
    }

    private function normalizeRouteRow(array $row): array
    {
        $row['scenario_ids'] = $this->decodeJsonArray($row['scenarios_json'] ?? '[]');
        $row['groups'] = $this->decodeJsonArray($row['assigned_groups'] ?? '[]');
        unset($row['scenarios_json'], $row['assigned_groups']);

        return $row;
    }
}
