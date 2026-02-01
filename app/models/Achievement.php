<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Models\SystemSetting;

/**
 * Modelo Achievement: gestiona logros desbloqueables
 * RF-018: Sistema de Logros y Gamificación
 */
final class Achievement extends Model
{
    /**
     * Obtiene todos los logros disponibles
     */
    public function getAll(): array
    {
        $query = "
            SELECT
                id,
                name,
                description,
                icon,
                category,
                requirement_type,
                requirement_value,
                points,
                is_active,
                created_at
            FROM achievements
            ORDER BY is_active DESC, category, points ASC
        ";

        return $this->query($query)->fetchAll();
    }

    /**
     * Obtiene un logro por ID
     */
    public function findById(int $id): ?array
    {
        $query = "
            SELECT
                id,
                name,
                description,
                icon,
                category,
                requirement_type,
                requirement_value,
                points,
                is_active,
                created_at
            FROM achievements
            WHERE id = :id
        ";

        $result = $this->query($query, ['id' => $id])->fetch();
        return $result ?: null;
    }

    /**
     * Obtiene logros de un usuario específico
     */
    public function getUserAchievements(int $userId): array
    {
        $query = "
            SELECT
                a.id,
                a.name,
                a.description,
                a.icon,
                a.category,
                a.points,
                ua.unlocked_at,
                1 as is_unlocked
            FROM achievements a
            INNER JOIN user_achievements ua ON a.id = ua.achievement_id
            WHERE ua.user_id = :user_id
            ORDER BY ua.unlocked_at DESC
        ";

        return $this->query($query, ['user_id' => $userId])->fetchAll();
    }

    /**
     * Obtiene todos los logros con estado de desbloqueo para un usuario
     */
    public function getAllWithUserStatus(int $userId): array
    {
        $query = "
            SELECT
                a.id,
                a.name,
                a.description,
                a.icon,
                a.category,
                a.requirement_type,
                a.requirement_value,
                a.points,
                ua.unlocked_at,
                CASE WHEN ua.achievement_id IS NOT NULL THEN 1 ELSE 0 END as is_unlocked
            FROM achievements a
            LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = :user_id
            WHERE a.is_active = 1
            ORDER BY is_unlocked DESC, a.category, a.points ASC
        ";

        return $this->query($query, ['user_id' => $userId])->fetchAll();
    }

    /**
     * Verifica si un usuario tiene un logro desbloqueado
     */
    public function hasUnlocked(int $userId, int $achievementId): bool
    {
        $query = "
            SELECT COUNT(*) as count
            FROM user_achievements
            WHERE user_id = :user_id AND achievement_id = :achievement_id
        ";

        $result = $this->query($query, [
            'user_id' => $userId,
            'achievement_id' => $achievementId,
        ])->fetch();

        return ((int)($result['count'] ?? 0)) > 0;
    }

    /**
     * Desbloquea un logro para un usuario
     */
    public function unlock(int $userId, int $achievementId): bool
    {
        // Verificar si ya está desbloqueado
        if ($this->hasUnlocked($userId, $achievementId)) {
            return false;
        }

        // Obtener puntos del logro
        $achievement = $this->findById($achievementId);
        if (!$achievement) {
            return false;
        }

        $query = "
            INSERT INTO user_achievements (user_id, achievement_id, unlocked_at)
            VALUES (:user_id, :achievement_id, NOW())
        ";

        $success = $this->query($query, [
            'user_id' => $userId,
            'achievement_id' => $achievementId,
        ]);

        // Actualizar puntos del usuario en user_stats
        if ($success) {
            $this->addPointsToUser($userId, (int)$achievement['points']);
        }

        return $success;
    }

    /**
     * Agrega puntos al usuario en user_stats
     */
    private function addPointsToUser(int $userId, int $points): void
    {
        $query = "
            INSERT INTO user_stats (user_id, total_points, achievements_unlocked)
            VALUES (:user_id, :points, 1)
            ON DUPLICATE KEY UPDATE
                total_points = total_points + :points,
                achievements_unlocked = achievements_unlocked + 1
        ";

        $this->query($query, [
            'user_id' => $userId,
            'points' => $points,
        ]);
    }

    /**
     * Verifica y desbloquea logros automáticamente para un usuario
     * Esta función se debe llamar después de cada sesión completada
     */
    public function checkAndUnlockAchievements(int $userId): array
    {
        if (SystemSetting::get('gamification_achievements_enabled', '1') !== '1') {
            return [];
        }

        $unlockedAchievements = [];

        // Obtener estadísticas del usuario
        $stats = $this->getUserStats($userId);

        // Obtener todos los logros activos que no están desbloqueados
        $query = "
            SELECT a.*
            FROM achievements a
            LEFT JOIN user_achievements ua ON a.id = ua.achievement_id AND ua.user_id = :user_id
            WHERE a.is_active = 1 AND ua.achievement_id IS NULL
        ";

        $achievements = $this->query($query, ['user_id' => $userId])->fetchAll();

        // Verificar cada logro
        foreach ($achievements as $achievement) {
            if ($this->meetsRequirement($stats, $achievement)) {
                if ($this->unlock($userId, (int)$achievement['id'])) {
                    $unlockedAchievements[] = $achievement;
                }
            }
        }

        return $unlockedAchievements;
    }

    /**
     * Obtiene estadísticas del usuario para verificar logros
     */
    private function getUserStats(int $userId): array
    {
        $query = "
            SELECT
                COUNT(DISTINCT s.id) as total_sessions,
                COUNT(DISTINCT CASE WHEN s.completion_percentage = 100 THEN s.id END) as completed_sessions,
                COALESCE(AVG(s.final_score), 0) as avg_score,
                COALESCE(MAX(s.final_score), 0) as best_score,
                COALESCE(us.total_points, 0) as total_points,
                COALESCE(us.achievements_unlocked, 0) as achievements_unlocked
            FROM sessions s
            LEFT JOIN user_stats us ON us.user_id = s.user_id
            WHERE s.user_id = :user_id
        ";

        $result = $this->query($query, ['user_id' => $userId])->fetch();
        return $result ?: [];
    }

    /**
     * Verifica si el usuario cumple con el requisito de un logro
     */
    private function meetsRequirement(array $stats, array $achievement): bool
    {
        $type = $achievement['requirement_type'];
        $value = (int)$achievement['requirement_value'];

        switch ($type) {
            case 'sessions_completed':
                return ((int)$stats['completed_sessions']) >= $value;

            case 'total_sessions':
                return ((int)$stats['total_sessions']) >= $value;

            case 'avg_score':
                return ((float)$stats['avg_score']) >= $value;

            case 'best_score':
                return ((int)$stats['best_score']) >= $value;

            case 'total_points':
                return ((int)$stats['total_points']) >= $value;

            case 'achievements_count':
                return ((int)$stats['achievements_unlocked']) >= $value;

            default:
                return false;
        }
    }

    /**
     * Obtiene el ranking de usuarios por puntos totales
     */
    public function getRanking(int $limit = 10): array
    {
        $query = "
            SELECT
                u.id,
                u.name,
                u.email,
                u.ficha,
                us.total_points,
                us.achievements_unlocked,
                COUNT(DISTINCT s.id) as total_sessions,
                COALESCE(AVG(s.final_score), 0) as avg_score
            FROM users u
            INNER JOIN user_stats us ON u.id = us.user_id
            LEFT JOIN sessions s ON u.id = s.user_id
            WHERE u.role = 'aprendiz' AND us.total_points > 0
            GROUP BY u.id, u.name, u.email, u.ficha, us.total_points, us.achievements_unlocked
            ORDER BY us.total_points DESC, us.achievements_unlocked DESC
            LIMIT :limit
        ";

        return $this->query($query, ['limit' => $limit])->fetchAll();
    }

    /**
     * Obtiene el ranking por competencia específica
     */
    public function getRankingByCompetence(string $competence, int $limit = 10): array
    {
        $validCompetences = ['comunicacion', 'liderazgo', 'trabajo_equipo', 'toma_decisiones'];
        if (!in_array($competence, $validCompetences, true)) {
            return [];
        }

        $query = "
            SELECT
                u.id,
                u.name,
                u.email,
                u.ficha,
                COALESCE(AVG(JSON_EXTRACT(s.scores_json, '$.{$competence}')), 0) as avg_competence_score,
                COUNT(DISTINCT s.id) as total_sessions
            FROM users u
            INNER JOIN sessions s ON u.id = s.user_id
            WHERE u.role = 'aprendiz' AND s.scores_json IS NOT NULL
            GROUP BY u.id, u.name, u.email, u.ficha
            HAVING avg_competence_score > 0
            ORDER BY avg_competence_score DESC
            LIMIT :limit
        ";

        return $this->query($query, ['limit' => $limit])->fetchAll();
    }

    /**
     * Obtiene el ranking por soft skill dinámica específica
     * Trabaja con cualquier nombre de soft skill almacenado en scores_json
     *
     * @param string $softSkillName Nombre de la soft skill (ej: "Comunicación Efectiva")
     * @param int $limit Número máximo de resultados
     * @return array Ranking de usuarios por esa soft skill
     */
    public function getRankingByDynamicSoftSkill(string $softSkillName, int $limit = 10): array
    {
        if (empty($softSkillName)) {
            return [];
        }

        // Usar JSON_EXTRACT para buscar la clave dinámica en el JSON
        $query = "
            SELECT
                u.id,
                u.name,
                u.email,
                u.ficha,
                u.programa,
                COALESCE(AVG(JSON_EXTRACT(s.scores_json, :json_path)), 0) as avg_skill_score,
                COUNT(DISTINCT s.id) as total_sessions
            FROM users u
            INNER JOIN sessions s ON u.id = s.user_id
            WHERE u.role = 'aprendiz'
              AND s.status = 'completed'
              AND s.scores_json IS NOT NULL
              AND JSON_EXTRACT(s.scores_json, :json_path) IS NOT NULL
            GROUP BY u.id, u.name, u.email, u.ficha, u.programa
            HAVING avg_skill_score > 0
            ORDER BY avg_skill_score DESC
            LIMIT :limit
        ";

        $jsonPath = '$.' . json_encode($softSkillName, JSON_UNESCAPED_UNICODE);
        $jsonPath = trim($jsonPath, '"'); // Remover comillas extras

        return $this->query($query, [
            'json_path' => $jsonPath,
            'limit' => $limit
        ])->fetchAll();
    }

    /**
     * Obtiene todas las soft skills únicas usadas en sesiones completadas
     * Útil para generar filtros de ranking
     *
     * @return array Lista de nombres de soft skills únicas
     */
    public function getAllUniqueSoftSkills(): array
    {
        $query = "
            SELECT DISTINCT pss.soft_skill_name
            FROM program_soft_skills pss
            INNER JOIN programs p ON pss.program_id = p.id
            WHERE p.soft_skills_generated = 1
            ORDER BY pss.soft_skill_name ASC
        ";

        $result = $this->query($query)->fetchAll();
        return array_column($result, 'soft_skill_name');
    }

    /**
     * Obtiene el ranking global consolidado por soft skills dinámicas
     * Calcula el promedio de todas las soft skills de cada usuario
     *
     * @param int $limit Número máximo de resultados
     * @return array Ranking consolidado
     */
    public function getRankingConsolidatedSoftSkills(int $limit = 10): array
    {
        $query = "
            SELECT
                u.id,
                u.name,
                u.email,
                u.ficha,
                u.programa,
                COUNT(DISTINCT s.id) as total_sessions,
                COALESCE(AVG(s.final_score), 0) as avg_total_score,
                COALESCE(us.total_points, 0) as total_points,
                COALESCE(us.achievements_unlocked, 0) as achievements_unlocked
            FROM users u
            INNER JOIN sessions s ON u.id = s.user_id
            LEFT JOIN user_stats us ON u.id = us.user_id
            WHERE u.role = 'aprendiz'
              AND s.status = 'completed'
              AND s.is_dynamic = 1
            GROUP BY u.id, u.name, u.email, u.ficha, u.programa, us.total_points, us.achievements_unlocked
            HAVING avg_total_score > 0
            ORDER BY avg_total_score DESC, total_points DESC
            LIMIT :limit
        ";

        return $this->query($query, ['limit' => $limit])->fetchAll();
    }

    /**
     * Crea un nuevo logro (admin/instructor)
     */
    public function create(array $data): int
    {
        $query = "
            INSERT INTO achievements (
                name,
                description,
                icon,
                category,
                requirement_type,
                requirement_value,
                points,
                is_active
            ) VALUES (
                :name,
                :description,
                :icon,
                :category,
                :requirement_type,
                :requirement_value,
                :points,
                :is_active
            )
        ";

        $this->query($query, [
            'name' => $data['name'],
            'description' => $data['description'],
            'icon' => $data['icon'] ?? 'fa-trophy',
            'category' => $data['category'] ?? 'general',
            'requirement_type' => $data['requirement_type'],
            'requirement_value' => $data['requirement_value'],
            'points' => $data['points'] ?? 100,
            'is_active' => $data['is_active'] ?? 1,
        ]);

        return (int)$this->db->lastInsertId();
    }

    /**
     * Actualiza un logro existente
     */
    public function update(int $id, array $data): bool
    {
        $query = "
            UPDATE achievements SET
                name = :name,
                description = :description,
                icon = :icon,
                category = :category,
                requirement_type = :requirement_type,
                requirement_value = :requirement_value,
                points = :points,
                is_active = :is_active
            WHERE id = :id
        ";

        $this->query($query, [
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'],
            'icon' => $data['icon'],
            'category' => $data['category'],
            'requirement_type' => $data['requirement_type'],
            'requirement_value' => $data['requirement_value'],
            'points' => $data['points'],
            'is_active' => $data['is_active'] ?? 1,
        ]);
        return true;
    }

    /**
     * Elimina (desactiva) un logro
     */
    public function delete(int $id): bool
    {
        $query = "UPDATE achievements SET is_active = 0 WHERE id = :id";
        $this->query($query, ['id' => $id]);
        return true;
    }

    /**
     * Obtiene estadísticas generales de logros
     */
    public function getStats(): array
    {
        $query = "
            SELECT
                COUNT(*) as total_achievements,
                COUNT(DISTINCT category) as total_categories,
                SUM(points) as total_possible_points,
                (SELECT COUNT(DISTINCT user_id) FROM user_achievements) as users_with_achievements,
                (SELECT COUNT(*) FROM user_achievements) as total_unlocks
            FROM achievements
            WHERE is_active = 1
        ";

        $result = $this->query($query)->fetch();
        return $result ?: [];
    }
}
