-- ============================================
-- Migración: Agregar columna is_active a tabla scenarios
-- Fecha: 27 de Enero de 2026
-- Descripción: Agrega la columna is_active si no existe
-- ============================================

USE rolplay_edu;

-- Agregar columna is_active si no existe
ALTER TABLE scenarios
ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT TRUE AFTER is_ai_generated,
ADD INDEX IF NOT EXISTS idx_active (is_active);

-- Actualizar escenarios existentes para que estén activos por defecto
UPDATE scenarios
SET is_active = TRUE
WHERE is_active IS NULL;

-- ============================================
-- FIN DE LA MIGRACIÓN
-- ============================================
