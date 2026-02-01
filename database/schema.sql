-- ============================================
-- RolPlay EDU - Database Schema
-- Versión: 1.0
-- Fecha: 26 de Enero de 2026
-- ============================================

-- Crear base de datos si no existe
CREATE DATABASE IF NOT EXISTS rolplay_edu
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE rolplay_edu;

-- ============================================
-- Tabla: users
-- Descripción: Usuarios del sistema (aprendices, instructores, admins)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('aprendiz', 'instructor', 'admin') DEFAULT 'aprendiz',
    profile_image VARCHAR(255) NULL,
    ficha VARCHAR(50) NULL COMMENT 'Número de ficha del aprendiz',
    programa VARCHAR(255) NULL COMMENT 'Programa de formación',
    active BOOLEAN DEFAULT TRUE,
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(100) NULL,
    reset_token VARCHAR(100) NULL,
    reset_token_expires TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_role (role),
    INDEX idx_ficha (ficha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: programs
-- Descripción: Programas de formación SENA cargados por instructores
-- ============================================
CREATE TABLE programs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    instructor_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    codigo_programa VARCHAR(50) NULL COMMENT 'Código del programa SENA',
    pdf_path VARCHAR(255) NULL COMMENT 'Ruta del PDF original del programa',
    pdf_original_name VARCHAR(255) NULL COMMENT 'Nombre original del PDF cargado',
    competencias_text LONGTEXT NULL,
    perfil_egreso_text LONGTEXT NULL,
    analysis_json TEXT NULL COMMENT 'Resultado del análisis de IA (Puter.js)',
    status ENUM('pending', 'analyzing', 'completed', 'error') DEFAULT 'pending',
    estado_analisis ENUM('PENDIENTE', 'PROCESANDO', 'COMPLETADO', 'ERROR') DEFAULT 'PENDIENTE',
    resultado_gemini LONGTEXT NULL COMMENT 'Resultado JSON crudo/extendido de Gemini',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_instructor (instructor_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: scenarios
-- Descripción: Escenarios/casos de simulación
-- ============================================
CREATE TABLE scenarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    program_id INT NULL COMMENT 'NULL si es escenario base',
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    area VARCHAR(100) NOT NULL COMMENT 'comercio, salud, tecnologia, etc.',
    difficulty ENUM('basico', 'intermedio', 'avanzado') DEFAULT 'basico',
    steps_json TEXT NOT NULL COMMENT 'Estructura completa del escenario (JSON)',
    is_ai_generated BOOLEAN DEFAULT FALSE,
    image_url VARCHAR(255) NULL,
    estimated_duration INT DEFAULT 15 COMMENT 'Duración estimada en minutos',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_area (area),
    INDEX idx_program (program_id),
    INDEX idx_difficulty (difficulty),
    INDEX idx_active (is_active),
    FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: sessions
-- Descripción: Sesiones de juego (intentos de escenarios)
-- ============================================
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    scenario_id INT NOT NULL,
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    scores_json TEXT NOT NULL COMMENT '{"Comunicación": 25, "Liderazgo": 15, ...}',
    final_score INT DEFAULT 0,
    decisions_count INT DEFAULT 0 COMMENT 'Total de decisiones tomadas',
    completion_percentage DECIMAL(5,2) DEFAULT 0.00,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (scenario_id) REFERENCES scenarios(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_scenario (scenario_id),
    INDEX idx_completed (completed_at),
    INDEX idx_final_score (final_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: decisions
-- Descripción: Decisiones individuales tomadas en cada sesión
-- ============================================
CREATE TABLE decisions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    step_number INT NOT NULL,
    option_chosen INT NOT NULL,
    scores_impact TEXT NOT NULL COMMENT 'JSON con impacto de la decisión',
    feedback_type ENUM('good', 'neutral', 'bad') NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES sessions(id) ON DELETE CASCADE,
    INDEX idx_session (session_id),
    INDEX idx_step (step_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: routes
-- Descripción: Rutas de aprendizaje (secuencias de escenarios)
-- ============================================
CREATE TABLE routes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    instructor_id INT NOT NULL,
    scenarios_json TEXT NOT NULL COMMENT 'Array de scenario_ids en orden',
    assigned_groups TEXT NULL COMMENT 'JSON array de fichas/grupos',
    start_date DATE NULL,
    end_date DATE NULL,
    active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_instructor (instructor_id),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: achievements
-- Descripción: Logros desbloqueables (gamificación)
-- ============================================
CREATE TABLE achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    icon VARCHAR(50) NULL COMMENT 'Clase de Font Awesome',
    category ENUM('progreso', 'excelencia', 'social', 'especial', 'general') DEFAULT 'general',
    points INT DEFAULT 0 COMMENT 'Puntos que otorga el logro',
    requirement_type VARCHAR(50) NULL COMMENT 'Tipo de requisito: sessions_completed, avg_score, etc.',
    requirement_value INT NULL COMMENT 'Valor del requisito',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: user_achievements
-- Descripción: Logros desbloqueados por usuarios
-- ============================================
CREATE TABLE user_achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    achievement_id INT NOT NULL,
    unlocked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_achievement (user_id, achievement_id),
    INDEX idx_user (user_id),
    INDEX idx_achievement (achievement_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: user_stats
-- Descripción: Estadísticas acumuladas de usuarios
-- ============================================
CREATE TABLE user_stats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    total_sessions INT DEFAULT 0,
    completed_sessions INT DEFAULT 0,
    total_points INT DEFAULT 0,
    average_score DECIMAL(5,2) DEFAULT 0.00,
    best_competence VARCHAR(50) NULL,
    achievements_unlocked INT DEFAULT 0 COMMENT 'Total de logros desbloqueados',
    scenarios_completed_ids TEXT NULL COMMENT 'JSON array de IDs completados',
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_total_points (total_points),
    INDEX idx_last_activity (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Tabla: notifications
-- Descripción: Notificaciones para usuarios
-- ============================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('achievement', 'route', 'instructor', 'system') DEFAULT 'system',
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    link VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user (user_id),
    INDEX idx_read (is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Vista: user_performance
-- Descripción: Vista consolidada del rendimiento de usuarios
-- ============================================
CREATE OR REPLACE VIEW user_performance AS
SELECT
    u.id AS user_id,
    u.name,
    u.email,
    u.ficha,
    COUNT(DISTINCT s.id) AS total_sessions,
    COUNT(DISTINCT CASE WHEN s.completed_at IS NOT NULL THEN s.id END) AS completed_sessions,
    ROUND(AVG(s.final_score), 2) AS average_score,
    MAX(s.final_score) AS best_score,
    COUNT(DISTINCT ua.achievement_id) AS achievements_count,
    COALESCE(us.total_points, 0) AS total_points
FROM users u
LEFT JOIN sessions s ON u.id = s.user_id
LEFT JOIN user_achievements ua ON u.id = ua.user_id
LEFT JOIN user_stats us ON u.id = us.user_id
WHERE u.role = 'aprendiz'
GROUP BY u.id, u.name, u.email, u.ficha, us.total_points;

-- ============================================
-- Índices adicionales para optimización
-- ============================================
-- Ya incluidos en las definiciones de tablas

-- ============================================
-- Datos iniciales / Seeders básicos
-- ============================================

-- Usuario administrador por defecto
-- Contraseña: admin123 (cambiar en producción)
INSERT INTO users (name, email, password, role, email_verified, active) VALUES
('Administrador', 'admin@rolplayedu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', TRUE, TRUE);

-- Escenarios base (8 escenarios offline documentados)
-- Se pueden cargar desde archivo JSON o script separado

-- ============================================
-- FIN DEL SCHEMA
-- ============================================

