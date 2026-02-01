-- ================================================================
-- Script: Seed usuarios de prueba + reset de password
-- Password universal: password123
-- Hash bcrypt: $2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.
-- ================================================================

USE rolplay_edu;

-- Crear/actualizar usuarios base (evita FK faltantes)
INSERT INTO users (id, name, email, password, role, active, email_verified, created_at, updated_at)
VALUES
  (1,  'Administrador',     'admin@rolplayedu.com',   '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin',      1, 1, NOW(), NOW()),
  (2,  'Carlos Rodriguez',  'admin@sena.edu.co',      '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin',      1, 1, NOW(), NOW()),
  (3,  'Maria Gonzalez',    'admin2@sena.edu.co',     '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin',      1, 1, NOW(), NOW()),
  (4,  'Juan Perez',        'instructor@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', 1, 1, NOW(), NOW()),
  (5,  'Ana Martinez',      'instructor2@sena.edu.co','$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', 1, 1, NOW(), NOW()),
  (6,  'Luis Sanchez',      'instructor3@sena.edu.co','$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', 1, 1, NOW(), NOW()),
  (7,  'Pedro Garcia',      'aprendiz1@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (8,  'Laura Torres',      'aprendiz2@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (9,  'Diego Ramirez',     'aprendiz3@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (10, 'Camila Lopez',      'aprendiz4@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (11, 'Andres Herrera',    'aprendiz5@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (12, 'Valentina Diaz',    'aprendiz6@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (13, 'Sebastian Morales', 'aprendiz7@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (14, 'Isabella Castro',   'aprendiz8@sena.edu.co',  '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (15, 'Miguel Angel Vargas','aprendiz9@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW()),
  (16, 'Sofia Jimenez',     'aprendiz10@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz',   1, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE
  name = VALUES(name),
  password = VALUES(password),
  role = VALUES(role),
  active = VALUES(active),
  email_verified = VALUES(email_verified),
  updated_at = NOW();

-- Verificacion
SELECT
  'Passwords updated' AS mensaje,
  COUNT(*) AS usuarios_actualizados
FROM users
WHERE password = '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.';
