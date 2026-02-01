-- ================================================================
-- Script: Datos de Prueba - RolPlay EDU
-- ================================================================
-- Este script crea usuarios de prueba con cada rol y datos de ejemplo
-- para navegar y probar todas las funcionalidades del sistema
-- ================================================================

USE rolplay_edu;

-- ================================================================
-- PASO 1: Crear usuarios de prueba (con contraseñas conocidas)
-- ================================================================
-- NOTA: Todas las contraseñas están hasheadas con PASSWORD_BCRYPT
-- Contraseña para todos los usuarios: "password123"
-- Hash: $2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.

INSERT INTO users (name, email, password, role, email_verified, active, ficha, programa) VALUES
-- Administradores
('Carlos Rodríguez', 'admin@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin', TRUE, TRUE, NULL, NULL),
('María González', 'admin2@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin', TRUE, TRUE, NULL, NULL),

-- Instructores
('Juan Pérez', 'instructor@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', TRUE, TRUE, NULL, NULL),
('Ana Martínez', 'instructor2@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', TRUE, TRUE, NULL, NULL),
('Luis Sánchez', 'instructor3@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', TRUE, TRUE, NULL, NULL),

-- Aprendices
('Pedro García', 'aprendiz1@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468101', 'Análisis y Desarrollo de Software'),
('Laura Torres', 'aprendiz2@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468101', 'Análisis y Desarrollo de Software'),
('Diego Ramírez', 'aprendiz3@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468102', 'Gestión Administrativa'),
('Camila López', 'aprendiz4@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468102', 'Gestión Administrativa'),
('Andrés Herrera', 'aprendiz5@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468103', 'Técnico en Enfermería'),
('Valentina Díaz', 'aprendiz6@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468103', 'Técnico en Enfermería'),
('Sebastián Morales', 'aprendiz7@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468104', 'Mantenimiento Electrónico e Instrumental Industrial'),
('Isabella Castro', 'aprendiz8@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468104', 'Mantenimiento Electrónico e Instrumental Industrial'),
('Miguel Ángel Vargas', 'aprendiz9@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468105', 'Producción Agropecuaria'),
('Sofía Jiménez', 'aprendiz10@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', TRUE, TRUE, '2468105', 'Producción Agropecuaria');

-- ================================================================
-- PASO 2: Crear escenarios de prueba
-- ================================================================

INSERT INTO scenarios (program_id, title, description, area, difficulty, steps_json, is_ai_generated, image_url, estimated_duration, is_active) VALUES
-- Escenario 1: Tecnología - Básico
(NULL, 'Gestión de Incidente de Seguridad Informática', 'Un empleado reporta que su computador está actuando de forma extraña. Debes diagnosticar y resolver el problema manteniendo la comunicación con el usuario y documentando el proceso.', 'tecnologia', 'basico',
'{"steps":[{"id":1,"situation":"Recibes un ticket: \"Mi computador está muy lento y aparecen ventanas extrañas\". ¿Qué haces primero?","options":[{"id":1,"text":"Llamar inmediatamente al usuario para obtener más información","impact":{"comunicacion":15,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}},{"id":2,"text":"Revisar el historial de tickets similares en el sistema","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":15}},{"id":3,"text":"Ir directamente al puesto de trabajo del usuario","impact":{"comunicacion":10,"liderazgo":15,"trabajo_equipo":5,"toma_decisiones":5}}]},{"id":2,"situation":"Identificas posible malware. El usuario te pregunta si perdió información. ¿Cómo respondes?","options":[{"id":1,"text":"Ser honesto: \"Aún no lo sé, pero haré todo lo posible por recuperarla\"","impact":{"comunicacion":20,"liderazgo":10,"trabajo_equipo":15,"toma_decisiones":10}},{"id":2,"text":"Tranquilizarlo: \"No te preocupes, todo estará bien\"","impact":{"comunicacion":10,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}},{"id":3,"text":"Ser técnico: \"Necesito realizar un análisis forense primero\"","impact":{"comunicacion":5,"liderazgo":15,"trabajo_equipo":5,"toma_decisiones":15}}]},{"id":3,"situation":"Necesitas aislar el equipo de la red. El usuario tiene una presentación en 2 horas. ¿Qué haces?","options":[{"id":1,"text":"Explicar la situación y buscar alternativas juntos","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":20,"toma_decisiones":15}},{"id":2,"text":"Aislar inmediatamente sin explicaciones (seguridad primero)","impact":{"comunicacion":5,"liderazgo":20,"trabajo_equipo":5,"toma_decisiones":20}},{"id":3,"text":"Esperar a después de la presentación","impact":{"comunicacion":15,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}}]}]}',
FALSE, 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b', 15, TRUE),

-- Escenario 2: Comercio - Intermedio
(NULL, 'Conflicto con Cliente Insatisfecho', 'Un cliente llega molesto reclamando por un producto defectuoso. Debes gestionar la situación manteniendo la calma y encontrando una solución satisfactoria.', 'comercio', 'intermedio',
'{"steps":[{"id":1,"situation":"El cliente está muy molesto y levanta la voz. ¿Cuál es tu primera acción?","options":[{"id":1,"text":"Mantener la calma y escuchar activamente sin interrumpir","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":10,"toma_decisiones":10}},{"id":2,"text":"Defender la política de la empresa inmediatamente","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":15}},{"id":3,"text":"Ofrecer un descuento de inmediato para calmarlo","impact":{"comunicacion":10,"liderazgo":5,"trabajo_equipo":5,"toma_decisiones":5}}]},{"id":2,"situation":"El cliente exige hablar con el gerente. Tú tienes autoridad para resolver. ¿Qué haces?","options":[{"id":1,"text":"Explicar que puedes ayudar y mostrar confianza en tu capacidad","impact":{"comunicacion":15,"liderazgo":20,"trabajo_equipo":10,"toma_decisiones":15}},{"id":2,"text":"Llamar al gerente inmediatamente","impact":{"comunicacion":5,"liderazgo":5,"trabajo_equipo":15,"toma_decisiones":10}},{"id":3,"text":"Negarte y mantener tu posición","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":5}}]},{"id":3,"situation":"Revisas el producto y efectivamente está defectuoso. ¿Qué solución ofreces?","options":[{"id":1,"text":"Reemplazo inmediato + disculpa formal + gesto de compensación","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":15,"toma_decisiones":20}},{"id":2,"text":"Solo reemplazo según política","impact":{"comunicacion":10,"liderazgo":10,"trabajo_equipo":10,"toma_decisiones":15}},{"id":3,"text":"Reembolso completo sin más preguntas","impact":{"comunicacion":15,"liderazgo":5,"trabajo_equipo":5,"toma_decisiones":10}}]}]}',
FALSE, 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d', 20, TRUE),

-- Escenario 3: Salud - Avanzado
(NULL, 'Emergencia Médica en Urgencias', 'Llegan simultáneamente dos pacientes con condiciones que requieren atención inmediata. Debes priorizar y coordinar al equipo médico eficientemente.', 'salud', 'avanzado',
'{"steps":[{"id":1,"situation":"Paciente A: Dolor torácico (posible infarto). Paciente B: Hemorragia severa. ¿Qué priorizas?","options":[{"id":1,"text":"Evaluar rápidamente ambos y dividir equipo según gravedad","impact":{"comunicacion":15,"liderazgo":20,"trabajo_equipo":20,"toma_decisiones":20}},{"id":2,"text":"Atender primero dolor torácico (protocolos cardiovasculares)","impact":{"comunicacion":10,"liderazgo":15,"trabajo_equipo":10,"toma_decisiones":15}},{"id":3,"text":"Atender primero hemorragia (riesgo de shock)","impact":{"comunicacion":10,"liderazgo":15,"trabajo_equipo":10,"toma_decisiones":15}}]},{"id":2,"situation":"El equipo está abrumado. Una enfermera nueva se paraliza. ¿Qué haces?","options":[{"id":1,"text":"Asignarle tarea específica y simple, con supervisión","impact":{"comunicacion":20,"liderazgo":20,"trabajo_equipo":20,"toma_decisiones":15}},{"id":2,"text":"Decirle que se retire si no puede manejar la presión","impact":{"comunicacion":5,"liderazgo":5,"trabajo_equipo":5,"toma_decisiones":10}},{"id":3,"text":"Ignorarla y enfocarte en los pacientes críticos","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":15}}]},{"id":3,"situation":"Familiar exige información inmediata mientras atiendes emergencia. ¿Cómo manejas?","options":[{"id":1,"text":"Pedir a otro miembro que informe mientras continúas","impact":{"comunicacion":20,"liderazgo":20,"trabajo_equipo":20,"toma_decisiones":20}},{"id":2,"text":"Informar brevemente tú mismo y volver a la emergencia","impact":{"comunicacion":15,"liderazgo":10,"trabajo_equipo":10,"toma_decisiones":10}},{"id":3,"text":"Pedirle que espere sin dar explicaciones","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":15}}]}]}',
FALSE, 'https://images.unsplash.com/photo-1516549655169-df83a0774514', 25, TRUE),

-- Escenario 4: Industrial - Intermedio
(NULL, 'Falla en Línea de Producción', 'La línea de producción principal se detiene a mitad de turno. Debes diagnosticar el problema y coordinar la reparación minimizando el tiempo de inactividad.', 'industrial', 'intermedio',
'{"steps":[{"id":1,"situation":"La línea se detiene súbitamente. Varios operarios te miran esperando instrucciones. ¿Qué haces?","options":[{"id":1,"text":"Reunir al equipo, asignar roles y comenzar diagnóstico sistemático","impact":{"comunicacion":20,"liderazgo":20,"trabajo_equipo":20,"toma_decisiones":15}},{"id":2,"text":"Comenzar diagnóstico tú solo mientras otros esperan","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":15}},{"id":3,"text":"Llamar inmediatamente a mantenimiento externo","impact":{"comunicacion":10,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":10}}]},{"id":2,"situation":"Identificas que es un problema eléctrico. El electricista está en otra planta. ¿Qué decides?","options":[{"id":1,"text":"Intentar reparación básica con equipo disponible (tienes capacitación)","impact":{"comunicacion":15,"liderazgo":20,"trabajo_equipo":15,"toma_decisiones":20}},{"id":2,"text":"Esperar al electricista (seguridad primero)","impact":{"comunicacion":10,"liderazgo":10,"trabajo_equipo":10,"toma_decisiones":15}},{"id":3,"text":"Pedir al supervisor que decida","impact":{"comunicacion":10,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}}]},{"id":3,"situation":"Durante reparación, gerencia presiona por reanudar. Falta verificar seguridad. ¿Cómo respondes?","options":[{"id":1,"text":"Explicar importancia de verificación completa, mantener posición","impact":{"comunicacion":20,"liderazgo":20,"trabajo_equipo":15,"toma_decisiones":20}},{"id":2,"text":"Ceder a la presión y reanudar","impact":{"comunicacion":5,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}},{"id":3,"text":"Hacer verificación rápida y reanudar","impact":{"comunicacion":15,"liderazgo":15,"trabajo_equipo":15,"toma_decisiones":15}}]}]}',
FALSE, 'https://images.unsplash.com/photo-1581092160562-40aa08e78837', 20, TRUE),

-- Escenario 5: Agropecuario - Básico
(NULL, 'Detección de Plaga en Cultivo', 'Observas signos inusuales en un sector del cultivo que podrían indicar una plaga. Debes evaluar la situación y tomar medidas preventivas.', 'agropecuario', 'basico',
'{"steps":[{"id":1,"situation":"Notas manchas en las hojas de un sector. ¿Cuál es tu primer paso?","options":[{"id":1,"text":"Documentar con fotos y consultar manual de plagas","impact":{"comunicacion":15,"liderazgo":10,"trabajo_equipo":10,"toma_decisiones":15}},{"id":2,"text":"Aplicar fungicida inmediatamente","impact":{"comunicacion":5,"liderazgo":15,"trabajo_equipo":5,"toma_decisiones":5}},{"id":3,"text":"Consultar con compañeros de experiencia","impact":{"comunicacion":20,"liderazgo":10,"trabajo_equipo":20,"toma_decisiones":10}}]},{"id":2,"situation":"Confirmas que es una plaga. El sector afectado está cerca de otros cultivos. ¿Qué haces?","options":[{"id":1,"text":"Aislar sector y notificar al equipo para monitoreo conjunto","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":20,"toma_decisiones":20}},{"id":2,"text":"Solo aislar sin notificar (evitar alarma)","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":10}},{"id":3,"text":"Tratar inmediatamente toda el área circundante","impact":{"comunicacion":10,"liderazgo":15,"trabajo_equipo":10,"toma_decisiones":15}}]},{"id":3,"situation":"El agrónomo recomienda un tratamiento costoso. Tu jefe duda por presupuesto. ¿Cómo contribuyes?","options":[{"id":1,"text":"Presentar datos de pérdidas potenciales vs costo de tratamiento","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":15,"toma_decisiones":20}},{"id":2,"text":"Apoyar la decisión del jefe sin opinar","impact":{"comunicacion":5,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}},{"id":3,"text":"Sugerir tratamiento alternativo más económico","impact":{"comunicacion":15,"liderazgo":15,"trabajo_equipo":15,"toma_decisiones":15}}]}]}',
FALSE, 'https://images.unsplash.com/photo-1574943320219-553eb213f72d', 15, TRUE),

-- Escenario 6: General - Básico
(NULL, 'Trabajo en Equipo: Proyecto Interdisciplinario', 'Se te asigna un proyecto que requiere colaboración con personas de otras áreas. Debes coordinar y contribuir efectivamente al resultado común.', 'general', 'basico',
'{"steps":[{"id":1,"situation":"Primera reunión de equipo. Nadie se conoce. ¿Cómo inicias?","options":[{"id":1,"text":"Proponer ronda de presentaciones y expectativas del proyecto","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":20,"toma_decisiones":10}},{"id":2,"text":"Ir directo al tema del proyecto (tiempo es valioso)","impact":{"comunicacion":5,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":15}},{"id":3,"text":"Esperar que el líder asignado dirija la reunión","impact":{"comunicacion":10,"liderazgo":5,"trabajo_equipo":15,"toma_decisiones":5}}]},{"id":2,"situation":"Hay desacuerdo sobre el enfoque del proyecto. Dos miembros discuten. ¿Qué haces?","options":[{"id":1,"text":"Proponer escuchar ambas posturas y votar democráticamente","impact":{"comunicacion":20,"liderazgo":20,"trabajo_equipo":20,"toma_decisiones":15}},{"id":2,"text":"Mantenerte neutral y no intervenir","impact":{"comunicacion":5,"liderazgo":5,"trabajo_equipo":10,"toma_decisiones":5}},{"id":3,"text":"Apoyar la postura que consideras más acertada","impact":{"comunicacion":15,"liderazgo":10,"trabajo_equipo":10,"toma_decisiones":15}}]},{"id":3,"situation":"Un miembro no cumple con sus tareas. El equipo se atrasa. ¿Cómo abordas la situación?","options":[{"id":1,"text":"Hablar en privado para entender qué sucede y ofrecer apoyo","impact":{"comunicacion":20,"liderazgo":15,"trabajo_equipo":20,"toma_decisiones":15}},{"id":2,"text":"Reportar al coordinador del proyecto","impact":{"comunicacion":10,"liderazgo":10,"trabajo_equipo":5,"toma_decisiones":10}},{"id":3,"text":"Redistribuir sus tareas entre el resto del equipo","impact":{"comunicacion":15,"liderazgo":15,"trabajo_equipo":15,"toma_decisiones":15}}]}]}',
FALSE, 'https://images.unsplash.com/photo-1522071820081-009f0129c71c', 15, TRUE);

-- ================================================================
-- PASO 3: Crear sesiones de prueba completadas
-- ================================================================

-- Sesiones para aprendiz1 (Pedro García)
INSERT INTO sessions (user_id, scenario_id, started_at, completed_at, scores_json, final_score, decisions_count, completion_percentage) VALUES
(6, 1, '2026-01-20 09:00:00', '2026-01-20 09:18:00', '{"comunicacion":55,"liderazgo":30,"trabajo_equipo":35,"toma_decisiones":40}', 160, 3, 100.00),
(6, 2, '2026-01-21 10:30:00', '2026-01-21 10:52:00', '{"comunicacion":45,"liderazgo":50,"trabajo_equipo":40,"toma_decisiones":50}', 185, 3, 100.00),
(6, 6, '2026-01-22 14:15:00', '2026-01-22 14:33:00', '{"comunicacion":60,"liderazgo":50,"trabajo_equipo":60,"toma_decisiones":45}', 215, 3, 100.00);

-- Sesiones para aprendiz2 (Laura Torres)
INSERT INTO sessions (user_id, scenario_id, started_at, completed_at, scores_json, final_score, decisions_count, completion_percentage) VALUES
(7, 1, '2026-01-20 11:00:00', '2026-01-20 11:20:00', '{"comunicacion":40,"liderazgo":45,"trabajo_equipo":50,"toma_decisiones":45}', 180, 3, 100.00),
(7, 6, '2026-01-21 15:00:00', '2026-01-21 15:18:00', '{"comunicacion":55,"liderazgo":40,"trabajo_equipo":55,"toma_decisiones":40}', 190, 3, 100.00),
(7, 3, '2026-01-23 09:30:00', '2026-01-23 09:58:00', '{"comunicacion":70,"liderazgo":65,"trabajo_equipo":70,"toma_decisiones":65}', 270, 3, 100.00),
(7, 2, '2026-01-24 11:00:00', '2026-01-24 11:25:00', '{"comunicacion":60,"liderazgo":55,"trabajo_equipo":50,"toma_decisiones":60}', 225, 3, 100.00);

-- Sesiones para aprendiz3 (Diego Ramírez)
INSERT INTO sessions (user_id, scenario_id, started_at, completed_at, scores_json, final_score, decisions_count, completion_percentage) VALUES
(8, 2, '2026-01-19 10:00:00', '2026-01-19 10:25:00', '{"comunicacion":70,"liderazgo":60,"trabajo_equipo":55,"toma_decisiones":70}', 255, 3, 100.00),
(8, 6, '2026-01-20 14:00:00', '2026-01-20 14:20:00', '{"comunicacion":65,"liderazgo":55,"trabajo_equipo":60,"toma_decisiones":55}', 235, 3, 100.00),
(8, 4, '2026-01-22 16:00:00', '2026-01-22 16:28:00', '{"comunicacion":60,"liderazgo":70,"trabajo_equipo":65,"toma_decisiones":70}', 265, 3, 100.00),
(8, 1, '2026-01-24 09:00:00', '2026-01-24 09:20:00', '{"comunicacion":50,"liderazgo":60,"trabajo_equipo":55,"toma_decisiones":65}', 230, 3, 100.00),
(8, 5, '2026-01-25 13:00:00', '2026-01-25 13:22:00', '{"comunicacion":65,"liderazgo":60,"trabajo_equipo":70,"toma_decisiones":60}', 255, 3, 100.00);

-- Sesiones para aprendiz4 (Camila López)
INSERT INTO sessions (user_id, scenario_id, started_at, completed_at, scores_json, final_score, decisions_count, completion_percentage) VALUES
(9, 2, '2026-01-21 10:00:00', '2026-01-21 10:28:00', '{"comunicacion":75,"liderazgo":65,"trabajo_equipo":70,"toma_decisiones":75}', 285, 3, 100.00),
(9, 6, '2026-01-22 11:30:00', '2026-01-22 11:50:00', '{"comunicacion":70,"liderazgo":60,"trabajo_equipo":75,"toma_decisiones":65}', 270, 3, 100.00);

-- Sesiones para aprendiz5 (Andrés Herrera)
INSERT INTO sessions (user_id, scenario_id, started_at, completed_at, scores_json, final_score, decisions_count, completion_percentage) VALUES
(10, 3, '2026-01-20 08:00:00', '2026-01-20 08:30:00', '{"comunicacion":80,"liderazgo":75,"trabajo_equipo":80,"toma_decisiones":80}', 315, 3, 100.00),
(10, 6, '2026-01-21 09:00:00', '2026-01-21 09:20:00', '{"comunicacion":75,"liderazgo":70,"trabajo_equipo":75,"toma_decisiones":70}', 290, 3, 100.00),
(10, 2, '2026-01-23 10:30:00', '2026-01-23 10:58:00', '{"comunicacion":70,"liderazgo":75,"trabajo_equipo":70,"toma_decisiones":75}', 290, 3, 100.00);

-- Sesión en progreso para aprendiz6 (Valentina Díaz)
INSERT INTO sessions (user_id, scenario_id, started_at, completed_at, scores_json, final_score, decisions_count, completion_percentage) VALUES
(11, 1, '2026-01-27 08:00:00', NULL, '{"comunicacion":0,"liderazgo":0,"trabajo_equipo":0,"toma_decisiones":0}', 0, 0, 0.00);

-- ================================================================
-- PASO 4: Crear rutas de aprendizaje
-- ================================================================

INSERT INTO routes (name, description, instructor_id, scenarios_json, assigned_groups, start_date, end_date, active) VALUES
('Ruta de Inducción - Competencias Blandas', 'Ruta diseñada para desarrollar competencias transversales en nuevos aprendices. Incluye escenarios básicos de todas las áreas.', 3, '[6, 1, 5]', '["2468101", "2468102"]', '2026-01-15', '2026-02-15', TRUE),
('Especialización en Atención al Cliente', 'Ruta enfocada en desarrollo de habilidades de servicio al cliente y manejo de situaciones difíciles.', 4, '[2, 6]', '["2468102"]', '2026-01-20', '2026-02-28', TRUE),
('Liderazgo en Situaciones Críticas', 'Escenarios avanzados para desarrollar liderazgo bajo presión y toma de decisiones rápidas.', 3, '[3, 4]', '["2468103", "2468104"]', '2026-01-25', '2026-03-15', TRUE);

-- ================================================================
-- PASO 5: Crear estadísticas de usuarios
-- ================================================================

INSERT INTO user_stats (user_id, total_sessions, completed_sessions, total_points, average_score, best_competence, scenarios_completed_ids, last_activity) VALUES
(6, 3, 3, 560, 186.67, 'comunicacion', '[1, 2, 6]', '2026-01-22 14:33:00'),
(7, 4, 4, 865, 216.25, 'trabajo_equipo', '[1, 6, 3, 2]', '2026-01-24 11:25:00'),
(8, 5, 5, 1240, 248.00, 'toma_decisiones', '[2, 6, 4, 1, 5]', '2026-01-25 13:22:00'),
(9, 2, 2, 555, 277.50, 'comunicacion', '[2, 6]', '2026-01-22 11:50:00'),
(10, 3, 3, 895, 298.33, 'comunicacion', '[3, 6, 2]', '2026-01-23 10:58:00'),
(11, 1, 0, 0, 0.00, NULL, '[]', '2026-01-27 08:00:00');

-- ================================================================
-- PASO 6: Crear notificaciones de prueba
-- ================================================================

INSERT INTO notifications (user_id, type, title, message, is_read, link, created_at) VALUES
-- Para aprendiz1 (Pedro García)
(6, 'achievement', '¡Logro Desbloqueado!', 'Has desbloqueado el logro "Primer Paso" por completar tu primera simulación.', TRUE, '/achievements', '2026-01-20 09:18:00'),
(6, 'route', 'Nueva Ruta Asignada', 'Se te ha asignado la ruta "Ruta de Inducción - Competencias Blandas".', TRUE, '/routes', '2026-01-20 08:00:00'),
(6, 'achievement', '¡Nuevo Logro!', 'Has desbloqueado "Aprendiz Dedicado" por completar 5 simulaciones.', FALSE, '/achievements', '2026-01-22 14:33:00'),

-- Para aprendiz2 (Laura Torres)
(7, 'route', 'Ruta Asignada', 'Tu instructor te ha asignado una nueva ruta de aprendizaje.', TRUE, '/routes', '2026-01-20 10:00:00'),
(7, 'instructor', 'Mensaje del Instructor', 'Excelente progreso en la ruta de competencias blandas.', FALSE, '/profile', '2026-01-24 12:00:00'),

-- Para aprendiz3 (Diego Ramírez)
(8, 'achievement', '¡Logro Desbloqueado!', 'Has desbloqueado "Practicante Avanzado" por completar 10 simulaciones.', FALSE, '/achievements', '2026-01-25 13:22:00'),
(8, 'system', 'Nuevo Contenido Disponible', 'Se han agregado nuevos escenarios en el área de Tecnología.', FALSE, '/scenarios', '2026-01-26 09:00:00'),

-- Para todos los aprendices
(6, 'system', 'Actualización del Sistema', 'RolPlay EDU ha sido actualizado con nuevas funcionalidades.', FALSE, '/', '2026-01-27 00:00:00'),
(7, 'system', 'Actualización del Sistema', 'RolPlay EDU ha sido actualizado con nuevas funcionalidades.', FALSE, '/', '2026-01-27 00:00:00'),
(8, 'system', 'Actualización del Sistema', 'RolPlay EDU ha sido actualizado con nuevas funcionalidades.', FALSE, '/', '2026-01-27 00:00:00'),
(9, 'system', 'Actualización del Sistema', 'RolPlay EDU ha sido actualizado con nuevas funcionalidades.', FALSE, '/', '2026-01-27 00:00:00'),
(10, 'system', 'Actualización del Sistema', 'RolPlay EDU ha sido actualizado con nuevas funcionalidades.', FALSE, '/', '2026-01-27 00:00:00');

-- ================================================================
-- PASO 7: Poblar logros desde el seeder
-- ================================================================
-- NOTA: Ejecutar el archivo seed_achievements.sql para cargar los 42 logros base
-- SOURCE database/seeders/seed_achievements.sql;

-- ================================================================
-- PASO 8: Desbloquear algunos logros para usuarios de prueba
-- ================================================================

-- Logros para aprendiz1 (Pedro García) - 3 sesiones completadas
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
(6, 1, '2026-01-20 09:18:00'),  -- Primer Paso
(6, 31, '2026-01-20 09:18:00'), -- Bienvenido a RolPlay EDU
(6, 37, '2026-01-22 14:33:00'); -- Básico Dominado

-- Logros para aprendiz2 (Laura Torres) - 4 sesiones completadas
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
(7, 1, '2026-01-20 11:20:00'),  -- Primer Paso
(7, 31, '2026-01-20 11:00:00'), -- Bienvenido a RolPlay EDU
(7, 37, '2026-01-21 15:18:00'), -- Básico Dominado
(7, 2, '2026-01-24 11:25:00');  -- Aprendiz Dedicado

-- Logros para aprendiz3 (Diego Ramírez) - 5 sesiones completadas
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
(8, 1, '2026-01-19 10:25:00'),  -- Primer Paso
(8, 31, '2026-01-19 09:00:00'), -- Bienvenido a RolPlay EDU
(8, 2, '2026-01-22 16:28:00'),  -- Aprendiz Dedicado
(8, 33, '2026-01-19 10:25:00'), -- Comercio Exitoso
(8, 37, '2026-01-19 10:25:00'), -- Básico Dominado
(8, 24, '2026-01-25 13:22:00'); -- Explorador (3 áreas)

-- Logros para aprendiz4 (Camila López) - 2 sesiones, alto rendimiento
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
(9, 1, '2026-01-21 10:28:00'),  -- Primer Paso
(9, 31, '2026-01-21 09:00:00'), -- Bienvenido a RolPlay EDU
(9, 6, '2026-01-21 10:28:00'),  -- Desempeño Sólido (60%)
(9, 33, '2026-01-21 10:28:00'); -- Comercio Exitoso

-- Logros para aprendiz5 (Andrés Herrera) - 3 sesiones, excelente rendimiento
INSERT INTO user_achievements (user_id, achievement_id, unlocked_at) VALUES
(10, 1, '2026-01-20 08:30:00'),  -- Primer Paso
(10, 31, '2026-01-20 07:30:00'), -- Bienvenido a RolPlay EDU
(10, 6, '2026-01-20 08:30:00'),  -- Desempeño Sólido
(10, 7, '2026-01-21 09:20:00'),  -- Competente (70%)
(10, 8, '2026-01-23 10:58:00'),  -- Destacado (80%)
(10, 34, '2026-01-20 08:30:00'), -- Salud es Vida
(10, 27, '2026-01-20 08:30:00'); -- Madrugador
-- ================================================================
-- PASO 9: Verificar datos insertados
-- ================================================================

SELECT '✅ Datos de prueba cargados exitosamente' AS STATUS;

SELECT '👥 RESUMEN DE USUARIOS' AS '';
SELECT role, COUNT(*) as cantidad FROM users GROUP BY role;

SELECT '' AS '';
SELECT '🎭 ESCENARIOS CREADOS' AS '';
SELECT area, COUNT(*) as cantidad FROM scenarios GROUP BY area;

SELECT '' AS '';
SELECT '🎮 SESIONES COMPLETADAS' AS '';
SELECT u.name, COUNT(s.id) as sesiones_completadas
FROM users u
LEFT JOIN sessions s ON u.id = s.user_id AND s.completed_at IS NOT NULL
WHERE u.role = 'aprendiz'
GROUP BY u.id, u.name;

SELECT '' AS '';
SELECT '🏆 LOGROS DESBLOQUEADOS' AS '';
SELECT u.name, COUNT(ua.id) as logros
FROM users u
LEFT JOIN user_achievements ua ON u.id = ua.user_id
WHERE u.role = 'aprendiz'
GROUP BY u.id, u.name;

SELECT '' AS '';
SELECT '📧 CREDENCIALES DE ACCESO' AS '';
SELECT '══════════════════════════════════════════════════════' AS '';
SELECT 'Todos los usuarios tienen la contraseña: password123' AS INFO;
SELECT '══════════════════════════════════════════════════════' AS '';
SELECT '' AS '';

SELECT role, email, name
FROM users
ORDER BY
    FIELD(role, 'admin', 'instructor', 'aprendiz'),
    id;

-- ================================================================
-- FIN DEL SCRIPT DE DATOS DE PRUEBA
-- ================================================================
