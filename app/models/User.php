<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT id, name, email, password, role, ficha, active FROM users WHERE email = :email LIMIT 1',
            ['email' => $email]
        );
    }

    public function create(array $data): int
    {
        $sql = 'INSERT INTO users (name, email, password, role, ficha, active, created_at, updated_at)
                VALUES (:name, :email, :password, :role, :ficha, 1, NOW(), NOW())';

        $this->query($sql, [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'] ?? 'aprendiz',
            'ficha' => $data['ficha'] ?? null,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function findAll(): array
    {
        $sql = 'SELECT id, name, email, role, ficha, programa, active, created_at, updated_at
                FROM users
                ORDER BY created_at DESC';

        return $this->fetchAll($sql);
    }

    public function findByRole(string $role): array
    {
        $sql = 'SELECT id, name, email, role, ficha, programa, active, created_at, updated_at
                FROM users
                WHERE role = :role
                ORDER BY created_at DESC';

        return $this->fetchAll($sql, ['role' => $role]);
    }

    public function findById(int $id): ?array
    {
        $sql = 'SELECT id, name, email, role, ficha, programa, active, created_at, updated_at
                FROM users
                WHERE id = :id
                LIMIT 1';

        return $this->fetchOne($sql, ['id' => $id]);
    }

    public function update(int $id, array $data): bool
    {
        // Construir dinámicamente la query según los campos proporcionados
        $fields = [];
        $params = ['id' => $id];

        if (isset($data['name'])) {
            $fields[] = 'name = :name';
            $params['name'] = $data['name'];
        }

        if (isset($data['email'])) {
            $fields[] = 'email = :email';
            $params['email'] = $data['email'];
        }

        if (isset($data['role'])) {
            $fields[] = 'role = :role';
            $params['role'] = $data['role'];
        }

        if (isset($data['password'])) {
            $fields[] = 'password = :password';
            $params['password'] = $data['password'];
        }

        if (isset($data['ficha'])) {
            $fields[] = 'ficha = :ficha';
            $params['ficha'] = $data['ficha'];
        }

        if (isset($data['programa'])) {
            $fields[] = 'programa = :programa';
            $params['programa'] = $data['programa'];
        }

        if (isset($data['active'])) {
            $fields[] = 'active = :active';
            $params['active'] = $data['active'];
        }

        if (empty($fields)) {
            return false;
        }

        $fields[] = 'updated_at = NOW()';
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';

        $this->query($sql, $params);

        return true;
    }

    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $this->query($sql, ['id' => $id]);

        return true;
    }

    /**
     * Obtiene el conteo de usuarios agrupados por rol
     *
     * @return array
     */
    public function getUsersByRole(): array
    {
        $sql = 'SELECT role, COUNT(*) as count
                FROM users
                WHERE active = 1
                GROUP BY role
                ORDER BY count DESC';

        return $this->fetchAll($sql, []);
    }

    /**
     * Obtiene los usuarios más recientes
     *
     * @param int $limit Número de usuarios a retornar
     * @return array
     */
    public function getRecentUsers(int $limit = 10): array
    {
        $limit = max(1, min(100, $limit));
        $sql = "SELECT id, name, email, role, created_at
                FROM users
                ORDER BY created_at DESC
                LIMIT {$limit}";

        return $this->fetchAll($sql, []);
    }
}
