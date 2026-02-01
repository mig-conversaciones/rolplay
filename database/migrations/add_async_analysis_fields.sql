-- Agrega campos para seguimiento de analisis asincrono
ALTER TABLE programs
ADD COLUMN estado_analisis ENUM('PENDIENTE', 'PROCESANDO', 'COMPLETADO', 'ERROR') DEFAULT 'PENDIENTE',
ADD COLUMN resultado_gemini LONGTEXT NULL;
