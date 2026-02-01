-- ============================================
-- MIGRACIÓN: Corregir discrepancias en achievements y user_stats
-- Fecha: 29 de Enero de 2026
-- Problema: Columnas faltantes que causan errores en el modelo
-- Error original: "Unknown column 'is_active' in 'where clause'"
-- ============================================

USE rolplay_edu;

-- ============================================
-- 1. CORRECCIONES EN TABLA achievements
-- ============================================

-- 1.1 Añadir columna 'icon' (el modelo usa 'icon', la BD tiene 'badge_icon')
-- Copiamos los valores de badge_icon a icon
ALTER TABLE achievements
ADD COLUMN IF NOT EXISTS icon VARCHAR(50) NULL COMMENT 'Clase de Font Awesome' AFTER description;

-- Copiar datos de badge_icon a icon si badge_icon existe
UPDATE achievements SET icon = badge_icon WHERE icon IS NULL AND badge_icon IS NOT NULL;

-- 1.2 Añadir columna 'requirement_type' - CRÍTICO para el sistema de logros
ALTER TABLE achievements
ADD COLUMN IF NOT EXISTS requirement_type VARCHAR(50) NULL COMMENT 'Tipo de requisito: sessions_completed, avg_score, best_score, total_points, achievements_count' AFTER category;

-- 1.3 Añadir columna 'requirement_value' - CRÍTICO para el sistema de logros
ALTER TABLE achievements
ADD COLUMN IF NOT EXISTS requirement_value INT NULL COMMENT 'Valor numérico del requisito' AFTER requirement_type;

-- 1.4 Añadir columna 'is_active' - ESTE ES EL ERROR REPORTADO
ALTER TABLE achievements
ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1 COMMENT 'Si el logro está activo' AFTER points;

-- 1.5 Crear índice para is_active si no existe
CREATE INDEX IF NOT EXISTS idx_achievements_active ON achievements(is_active);

-- ============================================
-- 2. CORRECCIONES EN TABLA user_stats
-- ============================================

-- 2.1 Añadir columna 'achievements_unlocked' - Usada por Achievement::addPointsToUser()
ALTER TABLE user_stats
ADD COLUMN IF NOT EXISTS achievements_unlocked INT DEFAULT 0 COMMENT 'Total de logros desbloqueados' AFTER total_points;

-- ============================================
-- 3. ACTUALIZAR LOGROS EXISTENTES CON VALORES POR DEFECTO
-- ============================================

-- Configurar requirement_type y requirement_value basados en la categoría
-- Esto es una estimación, ajustar según los logros específicos del sistema

UPDATE achievements
SET requirement_type = 'sessions_completed',
    requirement_value = 1,
    is_active = 1
WHERE requirement_type IS NULL AND category = 'progreso';

UPDATE achievements
SET requirement_type = 'best_score',
    requirement_value = 90,
    is_active = 1
WHERE requirement_type IS NULL AND category = 'excelencia';

UPDATE achievements
SET requirement_type = 'total_sessions',
    requirement_value = 5,
    is_active = 1
WHERE requirement_type IS NULL AND category = 'social';

UPDATE achievements
SET requirement_type = 'total_points',
    requirement_value = 100,
    is_active = 1
WHERE requirement_type IS NULL AND (category = 'especial' OR category = 'general' OR category IS NULL);

-- Asegurar que todos los logros tengan is_active = 1 por defecto
UPDATE achievements SET is_active = 1 WHERE is_active IS NULL;

-- ============================================
-- 4. VERIFICACIÓN
-- ============================================

-- Mostrar estructura actualizada de achievements
-- DESCRIBE achievements;

-- Mostrar estructura actualizada de user_stats
-- DESCRIBE user_stats;

-- ============================================
-- FIN DE LA MIGRACIÓN
-- ============================================
