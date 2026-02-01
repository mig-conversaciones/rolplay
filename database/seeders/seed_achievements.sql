-- ================================================================
-- Seeder de Logros Base - RolPlay EDU
-- ================================================================
-- Este seeder crea los logros iniciales del sistema de gamificación
-- Categorías: progreso, excelencia, social, especial, general
-- ================================================================

-- Limpieza de datos previos (opcional, comentar si no se desea)
-- DELETE FROM user_achievements;
-- DELETE FROM achievements;

-- ================================================================
-- LOGROS DE PROGRESO
-- ================================================================

INSERT INTO achievements (
    name,
    description,
    icon,
    category,
    points,
    requirement_type,
    requirement_value,
    is_active
) VALUES
(
    'Primer Paso',
    'Completa tu primera simulación de escenario laboral.',
    'fa-shoe-prints',
    'progreso',
    10,
    'sessions_completed',
    1,
    1
),
(
    'Aprendiz Dedicado',
    'Completa 5 simulaciones de escenarios laborales.',
    'fa-graduation-cap',
    'progreso',
    25,
    'sessions_completed',
    5,
    1
),
(
    'Practicante Avanzado',
    'Completa 10 simulaciones de escenarios laborales.',
    'fa-user-graduate',
    'progreso',
    50,
    'sessions_completed',
    10,
    1
),
(
    'Veterano SENA',
    'Completa 25 simulaciones de escenarios laborales.',
    'fa-medal',
    'progreso',
    100,
    'sessions_completed',
    25,
    1
),
(
    'Maestro de Escenarios',
    'Completa 50 simulaciones de escenarios laborales.',
    'fa-crown',
    'progreso',
    200,
    'sessions_completed',
    50,
    1
);

-- ================================================================
-- LOGROS DE EXCELENCIA
-- ================================================================

INSERT INTO achievements (
    name,
    description,
    icon,
    category,
    points,
    requirement_type,
    requirement_value,
    is_active
) VALUES
(
    'Desempeño Sólido',
    'Alcanza un promedio de 60% en tus competencias.',
    'fa-star',
    'excelencia',
    20,
    'avg_score',
    60,
    1
),
(
    'Competente',
    'Alcanza un promedio de 70% en tus competencias.',
    'fa-star-half-alt',
    'excelencia',
    40,
    'avg_score',
    70,
    1
),
(
    'Destacado',
    'Alcanza un promedio de 80% en tus competencias.',
    'fa-certificate',
    'excelencia',
    75,
    'avg_score',
    80,
    1
),
(
    'Excelente',
    'Alcanza un promedio de 90% en tus competencias.',
    'fa-trophy',
    'excelencia',
    150,
    'avg_score',
    90,
    1
),
(
    'Perfección',
    'Alcanza un promedio de 95% o más en tus competencias.',
    'fa-gem',
    'excelencia',
    250,
    'avg_score',
    95,
    1
);

-- ================================================================
-- LOGROS DE COMPETENCIAS ESPECÍFICAS
-- ================================================================

INSERT INTO achievements (
    name,
    description,
    icon,
    category,
    points,
    requirement_type,
    requirement_value,
    is_active
) VALUES
(
    'Comunicador Efectivo',
    'Alcanza 80 puntos o más en Comunicación.',
    'fa-comments',
    'excelencia',
    50,
    'competence_comunicacion',
    80,
    1
),
(
    'Líder Natural',
    'Alcanza 80 puntos o más en Liderazgo.',
    'fa-user-tie',
    'excelencia',
    50,
    'competence_liderazgo',
    80,
    1
),
(
    'Espíritu de Equipo',
    'Alcanza 80 puntos o más en Trabajo en Equipo.',
    'fa-users',
    'excelencia',
    50,
    'competence_trabajo_equipo',
    80,
    1
),
(
    'Decisiones Acertadas',
    'Alcanza 80 puntos o más en Toma de Decisiones.',
    'fa-lightbulb',
    'excelencia',
    50,
    'competence_toma_decisiones',
    80,
    1
),
(
    'Maestro de Competencias',
    'Alcanza 90 puntos o más en las 4 competencias transversales.',
    'fa-award',
    'excelencia',
    200,
    'all_competences',
    90,
    1
);

-- ================================================================
-- LOGROS SOCIALES
-- ================================================================

INSERT INTO achievements (
    name,
    description,
    icon,
    category,
    points,
    requirement_type,
    requirement_value,
    is_active
) VALUES
(
    'Primeros Contactos',
    'Interactúa con 5 personas diferentes en simulaciones.',
    'fa-handshake',
    'social',
    15,
    'interactions_count',
    5,
    1
),
(
    'Red de Colaboración',
    'Interactúa con 10 personas diferentes en simulaciones.',
    'fa-project-diagram',
    'social',
    30,
    'interactions_count',
    10,
    1
),
(
    'Conector Social',
    'Interactúa con 25 personas diferentes en simulaciones.',
    'fa-network-wired',
    'social',
    75,
    'interactions_count',
    25,
    1
);

-- ================================================================
-- LOGROS ESPECIALES
-- ================================================================

INSERT INTO achievements (
    name,
    description,
    icon,
    category,
    points,
    requirement_type,
    requirement_value,
    is_active
) VALUES
(
    'Racha de 3',
    'Completa 3 simulaciones consecutivas con 80% o más.',
    'fa-fire',
    'especial',
    40,
    'streak',
    3,
    1
),
(
    'Racha de 5',
    'Completa 5 simulaciones consecutivas con 80% o más.',
    'fa-fire-alt',
    'especial',
    80,
    'streak',
    5,
    1
),
(
    'Imparable',
    'Completa 10 simulaciones consecutivas con 80% o más.',
    'fa-infinity',
    'especial',
    150,
    'streak',
    10,
    1
),
(
    'Explorador',
    'Completa escenarios de 3 áreas diferentes.',
    'fa-compass',
    'especial',
    35,
    'areas_explored',
    3,
    1
),
(
    'Versátil',
    'Completa escenarios de 5 áreas diferentes.',
    'fa-globe',
    'especial',
    70,
    'areas_explored',
    5,
    1
),
(
    'Velocista',
    'Completa una simulación en menos de 10 minutos.',
    'fa-stopwatch',
    'especial',
    30,
    'completion_time',
    10,
    1
),
(
    'Madrugador',
    'Completa una simulación antes de las 8:00 AM.',
    'fa-sun',
    'especial',
    20,
    'early_bird',
    1,
    1
),
(
    'Noctámbulo',
    'Completa una simulación después de las 10:00 PM.',
    'fa-moon',
    'especial',
    20,
    'night_owl',
    1,
    1
);

-- ================================================================
-- LOGROS GENERALES
-- ================================================================

INSERT INTO achievements (
    name,
    description,
    icon,
    category,
    points,
    requirement_type,
    requirement_value,
    is_active
) VALUES
(
    'Bienvenido a RolPlay EDU',
    'Inicia sesión en la plataforma por primera vez.',
    'fa-hand-peace',
    'general',
    5,
    'first_login',
    1,
    1
),
(
    'Perfil Completo',
    'Completa toda la información de tu perfil de usuario.',
    'fa-id-card',
    'general',
    10,
    'profile_complete',
    1,
    1
),
(
    'Tecnología en Acción',
    'Completa un escenario del área de Tecnología.',
    'fa-laptop-code',
    'general',
    15,
    'area_tecnologia',
    1,
    1
),
(
    'Comercio Exitoso',
    'Completa un escenario del área de Comercio.',
    'fa-store',
    'general',
    15,
    'area_comercio',
    1,
    1
),
(
    'Salud es Vida',
    'Completa un escenario del área de Salud.',
    'fa-heartbeat',
    'general',
    15,
    'area_salud',
    1,
    1
),
(
    'Ingenio Industrial',
    'Completa un escenario del área Industrial.',
    'fa-industry',
    'general',
    15,
    'area_industrial',
    1,
    1
),
(
    'Agro Sostenible',
    'Completa un escenario del área Agropecuario.',
    'fa-leaf',
    'general',
    15,
    'area_agropecuario',
    1,
    1
),
(
    'Básico Dominado',
    'Completa un escenario de nivel Básico.',
    'fa-check',
    'general',
    10,
    'difficulty_basico',
    1,
    1
),
(
    'Intermedio Superado',
    'Completa un escenario de nivel Intermedio.',
    'fa-check-double',
    'general',
    20,
    'difficulty_intermedio',
    1,
    1
),
(
    'Avanzado Conquistado',
    'Completa un escenario de nivel Avanzado.',
    'fa-crown',
    'general',
    40,
    'difficulty_avanzado',
    1,
    1
),
(
    'Coleccionista',
    'Desbloquea 10 logros diferentes.',
    'fa-boxes',
    'general',
    50,
    'achievements_unlocked',
    10,
    1
),
(
    'Acumulador de Puntos',
    'Alcanza 500 puntos de logros totales.',
    'fa-coins',
    'general',
    100,
    'total_achievement_points',
    500,
    1
),
(
    'Leyenda de RolPlay',
    'Alcanza 1000 puntos de logros totales.',
    'fa-trophy',
    'general',
    250,
    'total_achievement_points',
    1000,
    1
);

-- ================================================================
-- RESUMEN DEL SEEDER
-- ================================================================
-- Total de logros creados: 42
--
-- Distribución por categoría:
-- - Progreso: 5 logros (10-200 puntos)
-- - Excelencia: 10 logros (20-250 puntos)
-- - Social: 3 logros (15-75 puntos)
-- - Especial: 8 logros (20-150 puntos)
-- - General: 16 logros (5-250 puntos)
--
-- Puntos totales posibles: 2,255 puntos
-- ================================================================

-- Verificar inserción
SELECT
    category,
    COUNT(*) as cantidad,
    SUM(points) as puntos_totales
FROM achievements
GROUP BY category
ORDER BY
    FIELD(category, 'progreso', 'excelencia', 'social', 'especial', 'general');
