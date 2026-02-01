-- ================================================================
-- Script: Limpiar Base de Datos - RolPlay EDU
-- ================================================================
-- Este script limpia COMPLETAMENTE toda la base de datos
-- ADVERTENCIA: Esta acción NO SE PUEDE DESHACER
-- Solo ejecutar en entorno de desarrollo/pruebas
-- ================================================================

USE rolplay_edu;

-- Mostrar advertencia
SELECT '⚠️  ADVERTENCIA: Este script eliminará TODOS los datos de la base de datos' AS WARNING;
SELECT 'Presiona Ctrl+C para cancelar o espera 3 segundos para continuar...' AS INFO;

-- Pausa de seguridad (comentar si deseas ejecución inmediata)
-- SELECT SLEEP(3);

-- ================================================================
-- PASO 1: Deshabilitar verificación de foreign keys
-- ================================================================
SET FOREIGN_KEY_CHECKS = 0;

-- ================================================================
-- PASO 2: Truncar todas las tablas (en orden de dependencias)
-- ================================================================

-- Tablas dependientes (primero)
TRUNCATE TABLE notifications;
TRUNCATE TABLE user_achievements;
TRUNCATE TABLE decisions;
TRUNCATE TABLE sessions;
TRUNCATE TABLE user_stats;

-- Tablas intermedias
TRUNCATE TABLE routes;
TRUNCATE TABLE scenarios;
TRUNCATE TABLE programs;
TRUNCATE TABLE achievements;

-- Tabla principal (último)
TRUNCATE TABLE users;

-- ================================================================
-- PASO 3: Re-habilitar verificación de foreign keys
-- ================================================================
SET FOREIGN_KEY_CHECKS = 1;

-- ================================================================
-- PASO 4: Reiniciar auto-increment de todas las tablas
-- ================================================================
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE programs AUTO_INCREMENT = 1;
ALTER TABLE scenarios AUTO_INCREMENT = 1;
ALTER TABLE sessions AUTO_INCREMENT = 1;
ALTER TABLE decisions AUTO_INCREMENT = 1;
ALTER TABLE routes AUTO_INCREMENT = 1;
ALTER TABLE achievements AUTO_INCREMENT = 1;
ALTER TABLE user_achievements AUTO_INCREMENT = 1;
ALTER TABLE user_stats AUTO_INCREMENT = 1;
ALTER TABLE notifications AUTO_INCREMENT = 1;

-- ================================================================
-- PASO 5: Verificar limpieza
-- ================================================================
SELECT '✅ Base de datos limpiada exitosamente' AS STATUS;

SELECT 'users' AS tabla, COUNT(*) AS registros FROM users
UNION ALL
SELECT 'programs', COUNT(*) FROM programs
UNION ALL
SELECT 'scenarios', COUNT(*) FROM scenarios
UNION ALL
SELECT 'sessions', COUNT(*) FROM sessions
UNION ALL
SELECT 'decisions', COUNT(*) FROM decisions
UNION ALL
SELECT 'routes', COUNT(*) FROM routes
UNION ALL
SELECT 'achievements', COUNT(*) FROM achievements
UNION ALL
SELECT 'user_achievements', COUNT(*) FROM user_achievements
UNION ALL
SELECT 'user_stats', COUNT(*) FROM user_stats
UNION ALL
SELECT 'notifications', COUNT(*) FROM notifications;

-- ================================================================
-- RESULTADO ESPERADO
-- ================================================================
-- Todas las tablas deben mostrar 0 registros
-- Los auto_increment deben estar reiniciados a 1
-- ================================================================
