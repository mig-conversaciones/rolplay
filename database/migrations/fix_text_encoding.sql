-- ================================================================
-- FIX: Reparar textos con mojibake (latin1/cp1252/cp850) a UTF-8
-- Ejecutar en rolplay_edu (hacer backup antes).
-- ================================================================

USE rolplay_edu;

-- Helper: patrones tÃ­picos de mojibake
-- latin1/cp1252 => Ã Â â �
-- cp850/cp437   => ├ │

-- users
UPDATE users
SET name = CONVERT(CAST(CONVERT(name USING latin1) AS BINARY) USING utf8mb4)
WHERE name REGEXP '[ÃÂâ�]';

UPDATE users
SET name = CONVERT(CAST(CONVERT(name USING cp850) AS BINARY) USING utf8mb4)
WHERE name REGEXP '[├│]';

UPDATE users
SET ficha = CONVERT(CAST(CONVERT(ficha USING latin1) AS BINARY) USING utf8mb4)
WHERE ficha REGEXP '[ÃÂâ�]';

UPDATE users
SET ficha = CONVERT(CAST(CONVERT(ficha USING cp850) AS BINARY) USING utf8mb4)
WHERE ficha REGEXP '[├│]';

UPDATE users
SET programa = CONVERT(CAST(CONVERT(programa USING latin1) AS BINARY) USING utf8mb4)
WHERE programa REGEXP '[ÃÂâ�]';

UPDATE users
SET programa = CONVERT(CAST(CONVERT(programa USING cp850) AS BINARY) USING utf8mb4)
WHERE programa REGEXP '[├│]';

-- programs
UPDATE programs
SET title = CONVERT(CAST(CONVERT(title USING latin1) AS BINARY) USING utf8mb4)
WHERE title REGEXP '[ÃÂâ�]';

UPDATE programs
SET title = CONVERT(CAST(CONVERT(title USING cp850) AS BINARY) USING utf8mb4)
WHERE title REGEXP '[├│]';

UPDATE programs
SET codigo_programa = CONVERT(CAST(CONVERT(codigo_programa USING latin1) AS BINARY) USING utf8mb4)
WHERE codigo_programa REGEXP '[ÃÂâ�]';

UPDATE programs
SET codigo_programa = CONVERT(CAST(CONVERT(codigo_programa USING cp850) AS BINARY) USING utf8mb4)
WHERE codigo_programa REGEXP '[├│]';

UPDATE programs
SET competencias_text = CONVERT(CAST(CONVERT(competencias_text USING latin1) AS BINARY) USING utf8mb4)
WHERE competencias_text REGEXP '[ÃÂâ�]';

UPDATE programs
SET competencias_text = CONVERT(CAST(CONVERT(competencias_text USING cp850) AS BINARY) USING utf8mb4)
WHERE competencias_text REGEXP '[├│]';

UPDATE programs
SET perfil_egreso_text = CONVERT(CAST(CONVERT(perfil_egreso_text USING latin1) AS BINARY) USING utf8mb4)
WHERE perfil_egreso_text REGEXP '[ÃÂâ�]';

UPDATE programs
SET perfil_egreso_text = CONVERT(CAST(CONVERT(perfil_egreso_text USING cp850) AS BINARY) USING utf8mb4)
WHERE perfil_egreso_text REGEXP '[├│]';

UPDATE programs
SET resultado_gemini = CONVERT(CAST(CONVERT(resultado_gemini USING latin1) AS BINARY) USING utf8mb4)
WHERE resultado_gemini REGEXP '[ÃÂâ�]';

UPDATE programs
SET resultado_gemini = CONVERT(CAST(CONVERT(resultado_gemini USING cp850) AS BINARY) USING utf8mb4)
WHERE resultado_gemini REGEXP '[├│]';

-- scenarios
UPDATE scenarios
SET title = CONVERT(CAST(CONVERT(title USING latin1) AS BINARY) USING utf8mb4)
WHERE title REGEXP '[ÃÂâ�]';

UPDATE scenarios
SET title = CONVERT(CAST(CONVERT(title USING cp850) AS BINARY) USING utf8mb4)
WHERE title REGEXP '[├│]';

UPDATE scenarios
SET description = CONVERT(CAST(CONVERT(description USING latin1) AS BINARY) USING utf8mb4)
WHERE description REGEXP '[ÃÂâ�]';

UPDATE scenarios
SET description = CONVERT(CAST(CONVERT(description USING cp850) AS BINARY) USING utf8mb4)
WHERE description REGEXP '[├│]';

UPDATE scenarios
SET steps_json = CONVERT(CAST(CONVERT(steps_json USING latin1) AS BINARY) USING utf8mb4)
WHERE steps_json REGEXP '[ÃÂâ�]';

UPDATE scenarios
SET steps_json = CONVERT(CAST(CONVERT(steps_json USING cp850) AS BINARY) USING utf8mb4)
WHERE steps_json REGEXP '[├│]';

-- routes
UPDATE routes
SET name = CONVERT(CAST(CONVERT(name USING latin1) AS BINARY) USING utf8mb4)
WHERE name REGEXP '[ÃÂâ�]';

UPDATE routes
SET name = CONVERT(CAST(CONVERT(name USING cp850) AS BINARY) USING utf8mb4)
WHERE name REGEXP '[├│]';

UPDATE routes
SET description = CONVERT(CAST(CONVERT(description USING latin1) AS BINARY) USING utf8mb4)
WHERE description REGEXP '[ÃÂâ�]';

UPDATE routes
SET description = CONVERT(CAST(CONVERT(description USING cp850) AS BINARY) USING utf8mb4)
WHERE description REGEXP '[├│]';

UPDATE routes
SET assigned_groups = CONVERT(CAST(CONVERT(assigned_groups USING latin1) AS BINARY) USING utf8mb4)
WHERE assigned_groups REGEXP '[ÃÂâ�]';

UPDATE routes
SET assigned_groups = CONVERT(CAST(CONVERT(assigned_groups USING cp850) AS BINARY) USING utf8mb4)
WHERE assigned_groups REGEXP '[├│]';

-- achievements (column name is "name" in the current schema)
UPDATE achievements
SET name = CONVERT(CAST(CONVERT(name USING latin1) AS BINARY) USING utf8mb4)
WHERE name REGEXP '[ÃÂâ�]';

UPDATE achievements
SET name = CONVERT(CAST(CONVERT(name USING cp850) AS BINARY) USING utf8mb4)
WHERE name REGEXP '[├│]';

UPDATE achievements
SET description = CONVERT(CAST(CONVERT(description USING latin1) AS BINARY) USING utf8mb4)
WHERE description REGEXP '[ÃÂâ�]';

UPDATE achievements
SET description = CONVERT(CAST(CONVERT(description USING cp850) AS BINARY) USING utf8mb4)
WHERE description REGEXP '[├│]';

-- notifications
UPDATE notifications
SET title = CONVERT(CAST(CONVERT(title USING latin1) AS BINARY) USING utf8mb4)
WHERE title REGEXP '[ÃÂâ�]';

UPDATE notifications
SET title = CONVERT(CAST(CONVERT(title USING cp850) AS BINARY) USING utf8mb4)
WHERE title REGEXP '[├│]';

UPDATE notifications
SET message = CONVERT(CAST(CONVERT(message USING latin1) AS BINARY) USING utf8mb4)
WHERE message REGEXP '[ÃÂâ�]';

UPDATE notifications
SET message = CONVERT(CAST(CONVERT(message USING cp850) AS BINARY) USING utf8mb4)
WHERE message REGEXP '[├│]';

-- sessions/decisions JSON content
UPDATE sessions
SET scores_json = CONVERT(CAST(CONVERT(scores_json USING latin1) AS BINARY) USING utf8mb4)
WHERE scores_json REGEXP '[ÃÂâ�]';

UPDATE sessions
SET scores_json = CONVERT(CAST(CONVERT(scores_json USING cp850) AS BINARY) USING utf8mb4)
WHERE scores_json REGEXP '[├│]';

UPDATE decisions
SET scores_impact = CONVERT(CAST(CONVERT(scores_impact USING latin1) AS BINARY) USING utf8mb4)
WHERE scores_impact REGEXP '[ÃÂâ�]';

UPDATE decisions
SET scores_impact = CONVERT(CAST(CONVERT(scores_impact USING cp850) AS BINARY) USING utf8mb4)
WHERE scores_impact REGEXP '[├│]';

-- system_settings
UPDATE system_settings
SET setting_value = CONVERT(CAST(CONVERT(setting_value USING latin1) AS BINARY) USING utf8mb4)
WHERE setting_value REGEXP '[ÃÂâ�]';

UPDATE system_settings
SET setting_value = CONVERT(CAST(CONVERT(setting_value USING cp850) AS BINARY) USING utf8mb4)
WHERE setting_value REGEXP '[├│]';
