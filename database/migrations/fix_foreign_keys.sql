-- ================================================================
-- Corrección URGENTE: Foreign Keys de user_achievements
-- ================================================================
-- Problema: user_achievements apunta a achievements_old_backup
-- Solución: Reapuntar a achievements
-- ================================================================

USE rolplay_edu;

-- Paso 1: Eliminar constraint incorrecta
SELECT 'Eliminando foreign key incorrecta...' AS '';

ALTER TABLE user_achievements
DROP FOREIGN KEY IF EXISTS user_achievements_ibfk_2;

-- Paso 2: Eliminar constraint de usuario también (por si acaso)
ALTER TABLE user_achievements
DROP FOREIGN KEY IF EXISTS user_achievements_ibfk_1;

-- Paso 3: Recrear ambas foreign keys correctamente
SELECT 'Recreando foreign keys correctas...' AS '';

ALTER TABLE user_achievements
ADD CONSTRAINT user_achievements_ibfk_1
    FOREIGN KEY (user_id)
    REFERENCES users(id)
    ON DELETE CASCADE;

ALTER TABLE user_achievements
ADD CONSTRAINT user_achievements_ibfk_2
    FOREIGN KEY (achievement_id)
    REFERENCES achievements(id)
    ON DELETE CASCADE;

-- Paso 4: Verificar que las constraints están correctas
SELECT 'Verificando foreign keys...' AS '';

SELECT
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'rolplay_edu'
  AND TABLE_NAME = 'user_achievements'
  AND CONSTRAINT_NAME LIKE '%ibfk%';

SELECT 'Foreign keys corregidas correctamente' AS '';
