-- ================================================================
-- Script de Migración: Correcciones de Discrepancias del Esquema
-- ================================================================
-- Este script corrige las diferencias entre el esquema actual (rolplay_edu.sql)
-- y la lógica implementada en el código PHP
--
-- IMPORTANTE: Ejecutar este script DESPUÉS de tener una copia de respaldo
-- de la base de datos actual
-- ================================================================

USE rolplay_edu;

-- ================================================================
-- PROBLEMA 1: Tabla `achievements` tiene estructura incorrecta
-- ================================================================

-- La tabla actual tiene: criteria_json, badge_icon, rarity
-- El código espera: icon, category, requirement_type, requirement_value, is_active

-- Paso 1: Verificar estructura actual
SELECT 'ANTES: Estructura de achievements' AS '';
DESCRIBE achievements;

-- Paso 2: Renombrar tabla actual como backup
ALTER TABLE achievements RENAME TO achievements_old_backup;

-- Paso 3: Crear nueva tabla con estructura correcta
CREATE TABLE achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(50) DEFAULT 'fa-trophy' COMMENT 'Icono Font Awesome',
    category VARCHAR(50) DEFAULT 'general' COMMENT 'Categoría: general, progreso, maestria, especial',
    requirement_type VARCHAR(50) NOT NULL COMMENT 'sessions_completed, avg_score, best_score, total_points, etc.',
    requirement_value INT NOT NULL COMMENT 'Valor mínimo requerido',
    points INT DEFAULT 100 COMMENT 'Puntos que otorga el logro',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_requirement_type (requirement_type),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Paso 4: Migrar datos antiguos SI EXISTEN (convertir estructura)
-- NOTA: Si no hay datos en achievements_old_backup, esto no hará nada
INSERT INTO achievements (name, description, icon, category, requirement_type, requirement_value, points, is_active)
SELECT
    name,
    description,
    COALESCE(badge_icon, 'fa-trophy') as icon,
    CASE
        WHEN rarity = 'common' THEN 'progreso'
        WHEN rarity = 'rare' THEN 'maestria'
        WHEN rarity = 'epic' THEN 'especial'
        WHEN rarity = 'legendary' THEN 'especial'
        ELSE 'general'
    END as category,
    'sessions_completed' as requirement_type, -- Default, debe ajustarse manualmente
    1 as requirement_value, -- Default, debe ajustarse manualmente
    COALESCE(points, 100) as points,
    1 as is_active
FROM achievements_old_backup
WHERE id IS NOT NULL;

-- Verificar migración
SELECT 'DESPUES: Nueva estructura de achievements' AS '';
SELECT COUNT(*) as total_achievements FROM achievements;

-- ================================================================
-- PROBLEMA 2: Tabla `sessions` - scenario_id debe ser NULLABLE
-- ================================================================

SELECT 'ANTES: Estructura de sessions (scenario_id)' AS '';
SHOW COLUMNS FROM sessions WHERE Field = 'scenario_id';

-- Hacer scenario_id NULLABLE para soportar sesiones dinámicas
ALTER TABLE sessions
MODIFY COLUMN scenario_id INT NULL COMMENT 'NULL para sesiones dinámicas, ID para escenarios estáticos';

SELECT 'DESPUES: scenario_id ahora es NULLABLE' AS '';
SHOW COLUMNS FROM sessions WHERE Field = 'scenario_id';

-- ================================================================
-- PROBLEMA 3: Tabla `sessions` - Falta columna `status`
-- ================================================================

-- Verificar si la columna ya existe
SELECT 'Verificando existencia de columna status...' AS '';
SELECT COUNT(*) as column_exists
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'sessions'
  AND COLUMN_NAME = 'status';

-- Agregar columna status si no existe
SET @col_exists = (SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = 'rolplay_edu'
      AND TABLE_NAME = 'sessions'
      AND COLUMN_NAME = 'status');

SET @query = IF(@col_exists = 0,
    'ALTER TABLE sessions ADD COLUMN status ENUM(''pending'', ''in_progress'', ''completed'', ''abandoned'') DEFAULT ''pending'' COMMENT ''Estado de la sesión'' AFTER is_dynamic',
    'SELECT ''La columna status ya existe'' AS resultado'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Agregar índice para status
SET @idx_exists = (SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = 'rolplay_edu'
      AND TABLE_NAME = 'sessions'
      AND INDEX_NAME = 'idx_status');

SET @query_idx = IF(@idx_exists = 0,
    'ALTER TABLE sessions ADD INDEX idx_status (status)',
    'SELECT ''El índice idx_status ya existe'' AS resultado'
);

PREPARE stmt_idx FROM @query_idx;
EXECUTE stmt_idx;
DEALLOCATE PREPARE stmt_idx;

-- Actualizar sesiones existentes con completed_at a status='completed'
UPDATE sessions
SET status = 'completed'
WHERE completed_at IS NOT NULL AND status = 'pending';

SELECT 'Columna status agregada y datos migrados' AS '';

-- ================================================================
-- PROBLEMA 4: Tabla `user_stats` - Falta columna `achievements_unlocked`
-- ================================================================

-- Verificar si la columna ya existe
SELECT 'Verificando existencia de columna achievements_unlocked...' AS '';
SELECT COUNT(*) as column_exists
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'user_stats'
  AND COLUMN_NAME = 'achievements_unlocked';

-- Agregar columna achievements_unlocked si no existe
SET @col_exists_ach = (SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = 'rolplay_edu'
      AND TABLE_NAME = 'user_stats'
      AND COLUMN_NAME = 'achievements_unlocked');

SET @query_ach = IF(@col_exists_ach = 0,
    'ALTER TABLE user_stats ADD COLUMN achievements_unlocked INT DEFAULT 0 COMMENT ''Total de logros desbloqueados'' AFTER total_points',
    'SELECT ''La columna achievements_unlocked ya existe'' AS resultado'
);

PREPARE stmt_ach FROM @query_ach;
EXECUTE stmt_ach;
DEALLOCATE PREPARE stmt_ach;

-- Agregar índice para achievements_unlocked
SET @idx_exists_ach = (SELECT COUNT(*)
    FROM information_schema.STATISTICS
    WHERE TABLE_SCHEMA = 'rolplay_edu'
      AND TABLE_NAME = 'user_stats'
      AND INDEX_NAME = 'idx_achievements_unlocked');

SET @query_idx_ach = IF(@idx_exists_ach = 0,
    'ALTER TABLE user_stats ADD INDEX idx_achievements_unlocked (achievements_unlocked)',
    'SELECT ''El índice idx_achievements_unlocked ya existe'' AS resultado'
);

PREPARE stmt_idx_ach FROM @query_idx_ach;
EXECUTE stmt_idx_ach;
DEALLOCATE PREPARE stmt_idx_ach;

-- Calcular achievements_unlocked para usuarios existentes
UPDATE user_stats us
SET achievements_unlocked = (
    SELECT COUNT(*)
    FROM user_achievements ua
    WHERE ua.user_id = us.user_id
)
WHERE user_id IS NOT NULL;

SELECT 'Columna achievements_unlocked agregada y datos calculados' AS '';

-- ================================================================
-- PROBLEMA 5: Eliminar columna obsoleta `context_json` de sessions
-- ================================================================

-- La columna context_json no se usa en el código actual
-- Está siendo reemplazada por stage1_json, stage2_json, stage3_json

SELECT 'Verificando existencia de columna context_json (obsoleta)...' AS '';
SELECT COUNT(*) as column_exists
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'sessions'
  AND COLUMN_NAME = 'context_json';

-- OPCIONAL: Comentar las siguientes líneas si quieres mantener context_json por seguridad
-- SET @col_exists_ctx = (SELECT COUNT(*)
--     FROM information_schema.COLUMNS
--     WHERE TABLE_SCHEMA = 'rolplay_edu'
--       AND TABLE_NAME = 'sessions'
--       AND COLUMN_NAME = 'context_json');

-- SET @query_ctx = IF(@col_exists_ctx > 0,
--     'ALTER TABLE sessions DROP COLUMN context_json',
--     'SELECT ''La columna context_json no existe'' AS resultado'
-- );

-- PREPARE stmt_ctx FROM @query_ctx;
-- EXECUTE stmt_ctx;
-- DEALLOCATE PREPARE stmt_ctx;

SELECT 'NOTA: context_json mantenida por seguridad (puedes eliminarla manualmente si lo deseas)' AS '';

-- ================================================================
-- VERIFICACIONES FINALES
-- ================================================================

SELECT '================================================================' AS '';
SELECT 'VERIFICACIONES FINALES DEL ESQUEMA' AS '';
SELECT '================================================================' AS '';

-- Verificar estructura de achievements
SELECT 'achievements: Columnas críticas' AS '';
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'achievements'
  AND COLUMN_NAME IN ('icon', 'category', 'requirement_type', 'requirement_value', 'is_active')
ORDER BY ORDINAL_POSITION;

-- Verificar sessions.scenario_id es NULL
SELECT 'sessions: scenario_id es NULLABLE?' AS '';
SELECT COLUMN_NAME, IS_NULLABLE, COLUMN_TYPE
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'sessions'
  AND COLUMN_NAME = 'scenario_id';

-- Verificar sessions.status existe
SELECT 'sessions: Columna status' AS '';
SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_DEFAULT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'sessions'
  AND COLUMN_NAME = 'status';

-- Verificar user_stats.achievements_unlocked existe
SELECT 'user_stats: Columna achievements_unlocked' AS '';
SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'user_stats'
  AND COLUMN_NAME = 'achievements_unlocked';

-- Conteo de registros
SELECT 'Conteo de registros en tablas clave' AS '';
SELECT
    (SELECT COUNT(*) FROM achievements) as total_achievements,
    (SELECT COUNT(*) FROM program_soft_skills) as total_program_soft_skills,
    (SELECT COUNT(*) FROM sessions WHERE is_dynamic = 1) as total_dynamic_sessions,
    (SELECT COUNT(*) FROM user_achievements) as total_user_achievements,
    (SELECT COUNT(*) FROM user_stats) as total_user_stats;

-- ================================================================
-- OPCIONAL: Cargar logros predefinidos si achievements está vacía
-- ================================================================

SET @achievements_count = (SELECT COUNT(*) FROM achievements);

-- Solo ejecutar si achievements está vacía
SELECT 'Total de logros en la tabla:' AS '', @achievements_count AS count;

-- Si quieres cargar los logros automáticamente, descomentar lo siguiente:
-- SOURCE database/seeders/seed_achievements.sql;

SELECT '================================================================' AS '';
SELECT 'MIGRACIÓN COMPLETADA' AS '';
SELECT '================================================================' AS '';
SELECT 'Próximos pasos:' AS '';
SELECT '1. Verificar que todas las verificaciones finales sean correctas' AS '';
SELECT '2. Si achievements está vacía, ejecutar: SOURCE database/seeders/seed_achievements.sql' AS '';
SELECT '3. Si users/programs están vacíos, ejecutar: SOURCE database/seeders/seed_test_data.sql' AS '';
SELECT '4. Probar el flujo completo del sistema dinámico' AS '';
SELECT '================================================================' AS '';
