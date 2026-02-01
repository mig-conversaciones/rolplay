<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Scenario extends Model
{
    public function allActive(): array
    {
        return $this->fetchAll(
            'SELECT id, title, description, area, difficulty, estimated_duration, is_ai_generated
             FROM scenarios
             WHERE is_active = 1
             ORDER BY id DESC'
        );
    }

    public function listActive(): array
    {
        return $this->fetchAll(
            'SELECT id, title, description, area, difficulty, estimated_duration, is_ai_generated, is_active
             FROM scenarios
             WHERE is_active = 1
             ORDER BY id DESC'
        );
    }

    public function findActiveById(int $id): ?array
    {
        $scenario = $this->fetchOne(
            'SELECT id, title, description, area, difficulty, steps_json, estimated_duration, is_ai_generated
             FROM scenarios
             WHERE id = :id AND is_active = 1
             LIMIT 1',
            ['id' => $id]
        );

        if (!$scenario) {
            return null;
        }

        $steps = json_decode((string) $scenario['steps_json'], true);
        if (!is_array($steps)) {
            $steps = [];
        }

        $scenario['steps'] = $steps;
        unset($scenario['steps_json']);

        return $scenario;
    }

    public function findById(int $id): ?array
    {
        $scenario = $this->fetchOne(
            'SELECT id, title, description, area, difficulty, steps_json, estimated_duration, is_ai_generated, is_active
             FROM scenarios
             WHERE id = :id
             LIMIT 1',
            ['id' => $id]
        );

        if (!$scenario) {
            return null;
        }

        // Decodificar steps_json si existe
        if (isset($scenario['steps_json'])) {
            $steps = json_decode((string) $scenario['steps_json'], true);
            if (!is_array($steps)) {
                $steps = [];
            }
            $scenario['steps'] = $steps;
            unset($scenario['steps_json']);
        }

        return $scenario;
    }

    public function listActiveBasic(): array
    {
        return $this->fetchAll(
            'SELECT id, title, area, difficulty
             FROM scenarios
             WHERE is_active = 1
             ORDER BY title ASC'
        );
    }

    public function listActiveByIds(array $ids): array
    {
        $ids = array_values(array_filter(array_map('intval', $ids), static fn (int $v): bool => $v > 0));
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $orderList = implode(', ', $ids);
        $sql = "SELECT id, title, description, area, difficulty, estimated_duration, is_ai_generated
                FROM scenarios
                WHERE is_active = 1 AND id IN ({$placeholders})
                ORDER BY FIELD(id, {$orderList})";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll();
    }

    public function listAll(): array
    {
        return $this->fetchAll(
            'SELECT id, title, description, area, difficulty, estimated_duration, is_ai_generated, is_active, created_at
             FROM scenarios
             ORDER BY created_at DESC'
        );
    }

    public function findTitlesByIds(array $ids): array
    {
        $ids = array_values(array_filter(array_map('intval', $ids), static fn (int $v): bool => $v > 0));
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(', ', array_fill(0, count($ids), '?'));
        $sql = "SELECT id, title, area, difficulty
                FROM scenarios
                WHERE id IN ({$placeholders})";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        $rows = $stmt->fetchAll();

        $map = [];
        foreach ($rows as $row) {
            $map[(int) $row['id']] = $row;
        }

        return $map;
    }

    public function listByProgram(int $programId): array
    {
        return $this->fetchAll(
            'SELECT id, program_id, title, area, difficulty, is_ai_generated, created_at
             FROM scenarios
             WHERE program_id = :program_id
             ORDER BY created_at DESC',
            ['program_id' => $programId]
        );
    }

    public function updateStatus(int $id, int $status): bool
    {
        $sql = 'UPDATE scenarios SET is_active = :status, updated_at = NOW() WHERE id = :id';
        $this->query($sql, [
            'id' => $id,
            'status' => $status
        ]);

        return true;
    }

    public function createFromAI(int $programId, array $payload): int
    {
        $title = (string) ($payload['title'] ?? 'Escenario IA');
        $description = (string) ($payload['description'] ?? 'Escenario generado por IA');
        $area = (string) ($payload['area'] ?? 'general');
        $difficulty = (string) ($payload['difficulty'] ?? 'basico');
        $steps = $payload['steps'] ?? [];

        $sql = 'INSERT INTO scenarios (program_id, title, description, area, difficulty, steps_json, is_ai_generated, is_active, created_at, updated_at)
                VALUES (:program_id, :title, :description, :area, :difficulty, :steps_json, 1, 1, NOW(), NOW())';

        $this->query($sql, [
            'program_id' => $programId,
            'title' => $title,
            'description' => $description,
            'area' => $area,
            'difficulty' => $difficulty,
            'steps_json' => json_encode($steps, JSON_UNESCAPED_UNICODE),
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function deleteByProgramId(int $programId): void
    {
        $this->query('DELETE FROM scenarios WHERE program_id = :program_id', ['program_id' => $programId]);
    }
}
