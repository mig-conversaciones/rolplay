-- ============================================
-- Migración: Soporte para Escenarios Dinámicos con IA
-- Fecha: 27 de Enero de 2026
-- Descripción: Añade soporte para generación dinámica en 3 etapas
--              y soft skills personalizadas por programa
-- ============================================

USE rolplay_edu;

-- ============================================
-- Nueva Tabla: program_soft_skills
-- Descripción: Soft skills identificadas por programa mediante análisis de sector con IA
-- ============================================
CREATE TABLE IF NOT EXISTS program_soft_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NOT NULL,
    soft_skill_name VARCHAR(100) NOT NULL COMMENT 'Ej: Comunicación Técnica, Liderazgo Ágil',
    weight DECIMAL(5,2) NOT NULL DEFAULT 20.00 COMMENT 'Peso porcentual (la suma de todas debe ser 100)',
    criteria_json TEXT NOT NULL COMMENT 'Array JSON de criterios de evaluación específicos',
    description TEXT NULL COMMENT 'Descripción contextualizada al sector laboral',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE,
    INDEX idx_program (program_id),
    INDEX idx_soft_skill_name (soft_skill_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Soft skills personalizadas identificadas por programa';

-- ============================================
-- Modificación Tabla: programs
-- Añadir campos para análisis de sector
-- ============================================
ALTER TABLE programs
ADD COLUMN IF NOT EXISTS sector VARCHAR(100) NULL COMMENT 'Sector laboral identificado (tecnología, salud, comercio, etc.)',
ADD COLUMN IF NOT EXISTS soft_skills_generated BOOLEAN DEFAULT FALSE COMMENT 'Indica si ya se identificaron las soft skills del programa',
ADD INDEX IF NOT EXISTS idx_sector (sector),
ADD INDEX IF NOT EXISTS idx_soft_skills_generated (soft_skills_generated);

-- ============================================
-- Modificación Tabla: sessions
-- Añadir campos para generación dinámica de escenarios
-- ============================================
ALTER TABLE sessions
ADD COLUMN IF NOT EXISTS program_id INT NULL COMMENT 'Programa asociado a esta sesión',
ADD COLUMN IF NOT EXISTS context_json TEXT NULL COMMENT 'Contexto completo de la sesión (soft skills, programa, historial)',
ADD COLUMN IF NOT EXISTS current_stage INT DEFAULT 0 COMMENT 'Etapa actual del escenario dinámico (0=no iniciado, 1-3=etapas)',
ADD COLUMN IF NOT EXISTS stage1_json TEXT NULL COMMENT 'Contenido JSON generado en etapa 1',
ADD COLUMN IF NOT EXISTS stage2_json TEXT NULL COMMENT 'Contenido JSON generado en etapa 2 (basado en respuesta etapa 1)',
ADD COLUMN IF NOT EXISTS stage3_json TEXT NULL COMMENT 'Contenido JSON generado en etapa 3 (cierre y evaluación)',
ADD COLUMN IF NOT EXISTS is_dynamic BOOLEAN DEFAULT FALSE COMMENT 'Indica si es una sesión con generación dinámica',
ADD INDEX IF NOT EXISTS idx_program (program_id),
ADD INDEX IF NOT EXISTS idx_current_stage (current_stage),
ADD INDEX IF NOT EXISTS idx_is_dynamic (is_dynamic),
ADD FOREIGN KEY IF NOT EXISTS fk_sessions_program (program_id) REFERENCES programs(id) ON DELETE SET NULL;

-- ============================================
-- Modificación Tabla: decisions
-- Añadir campos para evaluación granular por etapa
-- ============================================
ALTER TABLE decisions
ADD COLUMN IF NOT EXISTS stage INT NOT NULL DEFAULT 1 COMMENT 'En qué etapa del escenario se tomó esta decisión (1, 2, 3)',
ADD COLUMN IF NOT EXISTS soft_skills_evaluated JSON NULL COMMENT 'Soft skills evaluadas en esta decisión con sus puntos',
ADD INDEX IF NOT EXISTS idx_stage (stage);

-- ============================================
-- Tabla de Ejemplo: Inserción de datos de prueba
-- ============================================

-- Insertar soft skills de ejemplo para el programa con ID 1 (si existe)
INSERT INTO program_soft_skills (program_id, soft_skill_name, weight, criteria_json, description)
SELECT 1, 'Comunicación Técnica', 25.00,
       JSON_ARRAY(
           'Claridad en explicaciones técnicas',
           'Uso adecuado de terminología',
           'Adaptación del mensaje a la audiencia',
           'Documentación efectiva de procesos'
       ),
       'Capacidad de comunicar conceptos técnicos de manera clara a audiencias diversas en el sector tecnológico'
WHERE EXISTS (SELECT 1 FROM programs WHERE id = 1)
AND NOT EXISTS (SELECT 1 FROM program_soft_skills WHERE program_id = 1 AND soft_skill_name = 'Comunicación Técnica');

INSERT INTO program_soft_skills (program_id, soft_skill_name, weight, criteria_json, description)
SELECT 1, 'Trabajo en Equipo Ágil', 20.00,
       JSON_ARRAY(
           'Colaboración efectiva en equipos multidisciplinarios',
           'Adaptación a metodologías ágiles',
           'Resolución constructiva de conflictos',
           'Contribución activa en reuniones'
       ),
       'Habilidad para trabajar eficientemente en equipos que usan metodologías ágiles'
WHERE EXISTS (SELECT 1 FROM programs WHERE id = 1)
AND NOT EXISTS (SELECT 1 FROM program_soft_skills WHERE program_id = 1 AND soft_skill_name = 'Trabajo en Equipo Ágil');

INSERT INTO program_soft_skills (program_id, soft_skill_name, weight, criteria_json, description)
SELECT 1, 'Resolución de Problemas', 20.00,
       JSON_ARRAY(
           'Identificación precisa de problemas',
           'Análisis de causas raíz',
           'Propuesta de soluciones viables',
           'Evaluación de alternativas'
       ),
       'Capacidad de identificar, analizar y resolver problemas técnicos de manera sistemática'
WHERE EXISTS (SELECT 1 FROM programs WHERE id = 1)
AND NOT EXISTS (SELECT 1 FROM program_soft_skills WHERE program_id = 1 AND soft_skill_name = 'Resolución de Problemas');

INSERT INTO program_soft_skills (program_id, soft_skill_name, weight, criteria_json, description)
SELECT 1, 'Pensamiento Crítico', 20.00,
       JSON_ARRAY(
           'Análisis objetivo de información',
           'Cuestionamiento de suposiciones',
           'Evaluación de evidencias',
           'Toma de decisiones fundamentadas'
       ),
       'Habilidad para analizar situaciones de manera objetiva y tomar decisiones basadas en evidencias'
WHERE EXISTS (SELECT 1 FROM programs WHERE id = 1)
AND NOT EXISTS (SELECT 1 FROM program_soft_skills WHERE program_id = 1 AND soft_skill_name = 'Pensamiento Crítico');

INSERT INTO program_soft_skills (program_id, soft_skill_name, weight, criteria_json, description)
SELECT 1, 'Adaptabilidad al Cambio', 15.00,
       JSON_ARRAY(
           'Flexibilidad ante nuevos requerimientos',
           'Aprendizaje continuo de tecnologías',
           'Gestión efectiva de la incertidumbre',
           'Innovación y mejora continua'
       ),
       'Capacidad de adaptarse rápidamente a cambios tecnológicos y nuevos requerimientos'
WHERE EXISTS (SELECT 1 FROM programs WHERE id = 1)
AND NOT EXISTS (SELECT 1 FROM program_soft_skills WHERE program_id = 1 AND soft_skill_name = 'Adaptabilidad al Cambio');

-- ============================================
-- Actualizar programas existentes
-- ============================================

-- Marcar el programa 1 como que tiene soft skills si se insertaron los datos
UPDATE programs
SET soft_skills_generated = TRUE,
    sector = 'tecnologia'
WHERE id = 1
AND EXISTS (SELECT 1 FROM program_soft_skills WHERE program_id = 1);

-- ============================================
-- Verificación de la migración
-- ============================================

-- Mostrar estructura de la nueva tabla
SELECT 'Estructura de program_soft_skills:' AS info;
DESCRIBE program_soft_skills;

-- Mostrar nuevas columnas en programs
SELECT 'Nuevas columnas en programs:' AS info;
SHOW COLUMNS FROM programs LIKE 'sector';
SHOW COLUMNS FROM programs LIKE 'soft_skills_generated';

-- Mostrar nuevas columnas en sessions
SELECT 'Nuevas columnas en sessions:' AS info;
SHOW COLUMNS FROM sessions LIKE 'program_id';
SHOW COLUMNS FROM sessions LIKE 'current_stage';
SHOW COLUMNS FROM sessions LIKE 'is_dynamic';

-- Mostrar nuevas columnas en decisions
SELECT 'Nuevas columnas en decisions:' AS info;
SHOW COLUMNS FROM decisions LIKE 'stage';
SHOW COLUMNS FROM decisions LIKE 'soft_skills_evaluated';

-- Contar soft skills insertadas
SELECT 'Soft skills de ejemplo insertadas:' AS info;
SELECT COUNT(*) as total FROM program_soft_skills WHERE program_id = 1;

-- ============================================
-- Fin de la migración
-- ============================================

SELECT '✅ Migración completada exitosamente' AS resultado;
SELECT 'Ahora el sistema soporta:' AS info;
SELECT '  - Soft skills personalizadas por programa' AS caracteristica;
SELECT '  - Generación dinámica de escenarios en 3 etapas' AS caracteristica;
SELECT '  - Evaluación granular por etapa' AS caracteristica;
SELECT '  - Análisis de sector laboral con IA' AS caracteristica;
