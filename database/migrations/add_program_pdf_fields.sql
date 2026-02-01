-- Agrega columnas para almacenar PDF del programa
ALTER TABLE programs
    ADD COLUMN IF NOT EXISTS pdf_path VARCHAR(255) NULL COMMENT 'Ruta del PDF original del programa',
    ADD COLUMN IF NOT EXISTS pdf_original_name VARCHAR(255) NULL COMMENT 'Nombre original del PDF cargado';
