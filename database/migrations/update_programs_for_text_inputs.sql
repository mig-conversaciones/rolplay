-- Permite crear programas sin PDF y guardar datos digitados por el instructor
ALTER TABLE programs
ADD COLUMN competencias_text LONGTEXT NULL,
ADD COLUMN perfil_egreso_text LONGTEXT NULL;
