-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 28-01-2026 a las 19:43:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `rolplay_edu`
--
DROP DATABASE IF EXISTS `rolplay_edu`;
CREATE DATABASE `rolplay_edu` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `rolplay_edu`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `criteria_json` text NOT NULL COMMENT 'Condiciones para desbloquear',
  `badge_icon` varchar(255) DEFAULT NULL,
  `points` int(11) DEFAULT 0 COMMENT 'Puntos que otorga el logro',
  `rarity` enum('common','rare','epic','legendary') DEFAULT 'common',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `decisions`
--

CREATE TABLE `decisions` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `step_number` int(11) NOT NULL,
  `option_chosen` int(11) NOT NULL,
  `scores_impact` text NOT NULL COMMENT 'JSON con impacto de la decisi??n',
  `feedback_type` enum('good','neutral','bad') DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `stage` int(11) NOT NULL DEFAULT 1 COMMENT 'En qu├® etapa del escenario se tom├│ esta decisi├│n (1, 2, 3)',
  `soft_skills_evaluated` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Soft skills evaluadas en esta decisi├│n con sus puntos' CHECK (json_valid(`soft_skills_evaluated`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('achievement','route','instructor','system') DEFAULT 'system',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programs`
--

CREATE TABLE `programs` (
  `id` int(11) NOT NULL,
  `instructor_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `codigo_programa` varchar(50) DEFAULT NULL COMMENT 'C??digo del programa SENA',
  `pdf_path` varchar(255) NOT NULL,
  `analysis_json` text DEFAULT NULL COMMENT 'Resultado del an??lisis de IA (Puter.js)',
  `status` enum('pending','analyzing','completed','error') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `sector` varchar(100) DEFAULT NULL COMMENT 'Sector laboral identificado (tecnolog├¡a, salud, comercio, etc.)',
  `soft_skills_generated` tinyint(1) DEFAULT 0 COMMENT 'Indica si ya se identificaron las soft skills del programa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `program_soft_skills`
--

CREATE TABLE `program_soft_skills` (
  `id` int(11) NOT NULL,
  `program_id` int(11) NOT NULL,
  `soft_skill_name` varchar(100) NOT NULL COMMENT 'Ej: Comunicaci├│n T├®cnica, Liderazgo ├ügil',
  `weight` decimal(5,2) NOT NULL DEFAULT 20.00 COMMENT 'Peso porcentual (la suma de todas debe ser 100)',
  `criteria_json` text NOT NULL COMMENT 'Array JSON de criterios de evaluaci├│n espec├¡ficos',
  `description` text DEFAULT NULL COMMENT 'Descripci├│n contextualizada al sector laboral',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Soft skills personalizadas identificadas por programa';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `routes`
--

CREATE TABLE `routes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `instructor_id` int(11) NOT NULL,
  `scenarios_json` text NOT NULL COMMENT 'Array de scenario_ids en orden',
  `assigned_groups` text DEFAULT NULL COMMENT 'JSON array de fichas/grupos',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `scenarios`
--

CREATE TABLE `scenarios` (
  `id` int(11) NOT NULL,
  `program_id` int(11) DEFAULT NULL COMMENT 'NULL si es escenario base',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `area` varchar(100) NOT NULL COMMENT 'comercio, salud, tecnologia, etc.',
  `difficulty` enum('basico','intermedio','avanzado') DEFAULT 'basico',
  `steps_json` text NOT NULL COMMENT 'Estructura completa del escenario (JSON)',
  `is_ai_generated` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `image_url` varchar(255) DEFAULT NULL,
  `estimated_duration` int(11) DEFAULT 15 COMMENT 'Duraci??n estimada en minutos',
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `scenarios`
--

INSERT INTO `scenarios` (`id`, `program_id`, `title`, `description`, `area`, `difficulty`, `steps_json`, `is_ai_generated`, `is_active`, `image_url`, `estimated_duration`, `active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Cambio de Requisitos', 'El cliente cambia prioridades a mitad del sprint.', 'tecnologia', 'intermedio', '[{\"id\":0,\"text\":\"El cliente pide cambios urgentes que afectan el plan. Que haces primero?\",\"options\":[{\"text\":\"A) Aceptar todo sin analizar\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-5,\"Liderazgo\":-5,\"Trabajo en Equipo\":-5,\"Toma de Decisiones\":-5}},{\"text\":\"B) Analizar impacto y alinear con el equipo\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":8,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":10}},{\"text\":\"C) Ignorar el cambio\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-6,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":-8}}]},{\"id\":1,\"text\":\"El equipo esta confundido con la nueva prioridad.\",\"options\":[{\"text\":\"A) Hacer una mini reunion y clarificar objetivos\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":10,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":6}},{\"text\":\"B) Dejar que cada quien interprete\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":-2,\"Toma de Decisiones\":-2}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"Una buena gestion del cambio combina analisis, comunicacion y acuerdos claros.\",\"options\":[]}]\n ', 0, 1, NULL, 15, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(2, NULL, 'Feedback Conflictivo', 'Debes dar retroalimentacion a un companero molesto.', 'comercio', 'basico', '[{\"id\":0,\"text\":\"Un companero reacciona mal al feedback. Como inicias?\",\"options\":[{\"text\":\"A) Le dices que se aguante\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-4,\"Trabajo en Equipo\":-8,\"Toma de Decisiones\":-6}},{\"text\":\"B) Preguntas como se siente y contextualizas\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":6}}]},{\"id\":1,\"text\":\"Necesitas ser claro sin herir.\",\"options\":[{\"text\":\"A) Usar ejemplos concretos y proponer mejoras\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":8,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":6}},{\"text\":\"B) Hablar en generalidades\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":0,\"Toma de Decisiones\":0}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"El feedback efectivo es oportuno, especifico y empatico.\",\"options\":[]}]\n ', 0, 1, NULL, 12, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(3, NULL, 'Proveedor Inconfiable', 'Un proveedor incumple y pone en riesgo la entrega.', 'logistica', 'intermedio', '[{\"id\":0,\"text\":\"El proveedor no responde. Que haces?\",\"options\":[{\"text\":\"A) Esperar sin plan B\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-4,\"Liderazgo\":-6,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":-10}},{\"text\":\"B) Escalar y activar alternativas\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":10,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":10}}]},{\"id\":1,\"text\":\"Debes informar a stakeholders.\",\"options\":[{\"text\":\"A) Comunicar impacto y mitigacion\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":8}},{\"text\":\"B) Ocultar el problema\",\"result\":2,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-6,\"Trabajo en Equipo\":-6,\"Toma de Decisiones\":-8}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"La gestion de riesgos exige transparencia y decisiones oportunas.\",\"options\":[]}]\n ', 0, 1, NULL, 18, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(4, NULL, 'Error en Produccion', 'Se detecta un fallo critico en un sistema en uso.', 'tecnologia', 'avanzado', '[{\"id\":0,\"text\":\"Hay un error critico. Tu primer paso?\",\"options\":[{\"text\":\"A) Buscar culpables\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-8,\"Liderazgo\":-8,\"Trabajo en Equipo\":-10,\"Toma de Decisiones\":-4}},{\"text\":\"B) Contener el impacto y abrir incidente\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":10,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":10}}]},{\"id\":1,\"text\":\"El equipo necesita direccion.\",\"options\":[{\"text\":\"A) Asignar roles y tiempos claros\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":10,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}},{\"text\":\"B) Dejar que todo fluya\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":-2,\"Trabajo en Equipo\":-2,\"Toma de Decisiones\":-4}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"En crisis: primero contener, luego comunicar, despues aprender.\",\"options\":[]}]\n ', 0, 1, NULL, 20, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(5, NULL, 'Idea Rechazada', 'Tu propuesta fue rechazada en una reunion.', 'comercio', 'basico', '[{\"id\":0,\"text\":\"Rechazan tu idea. Como respondes?\",\"options\":[{\"text\":\"A) Te ofendes y te cierras\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-8,\"Liderazgo\":-4,\"Trabajo en Equipo\":-8,\"Toma de Decisiones\":-4}},{\"text\":\"B) Pides criterios y aprendes\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":8}}]},{\"id\":1,\"text\":\"Quieres mejorarla.\",\"options\":[{\"text\":\"A) Iterar con feedback y datos\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":6,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}},{\"text\":\"B) Guardarla sin accion\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":0,\"Toma de Decisiones\":-2}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"La resiliencia convierte el rechazo en aprendizaje accionable.\",\"options\":[]}]\n ', 0, 1, NULL, 10, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(6, NULL, 'Companero con Dificultades', 'Un companero esta bajando su rendimiento.', 'servicio', 'intermedio', '[{\"id\":0,\"text\":\"Notas que alguien no esta bien. Que haces?\",\"options\":[{\"text\":\"A) Ignorar para no meterte\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-6,\"Liderazgo\":-4,\"Trabajo en Equipo\":-10,\"Toma de Decisiones\":-4}},{\"text\":\"B) Conversar en privado con respeto\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":6}}]},{\"id\":1,\"text\":\"Hay barreras personales y de trabajo.\",\"options\":[{\"text\":\"A) Ofrecer apoyo y ajustar expectativas\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":8,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":6}},{\"text\":\"B) Reportar sin hablar antes\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":-2,\"Liderazgo\":2,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":0}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"El apoyo oportuno fortalece la cohesion del equipo.\",\"options\":[]}]\n ', 0, 1, NULL, 16, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(7, NULL, 'Prioridades del Proyecto', 'Exceso de tareas y todas parecen urgentes.', 'tecnologia', 'intermedio', '[{\"id\":0,\"text\":\"Todo parece urgente. Como priorizas?\",\"options\":[{\"text\":\"A) Hacer lo primero que llegue\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-2,\"Liderazgo\":-6,\"Trabajo en Equipo\":-2,\"Toma de Decisiones\":-10}},{\"text\":\"B) Usar criterios claros de impacto y esfuerzo\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":6,\"Liderazgo\":10,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":10}}]},{\"id\":1,\"text\":\"Necesitas alinear al equipo.\",\"options\":[{\"text\":\"A) Explicar prioridades y por que\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":8,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}},{\"text\":\"B) Imponer sin contexto\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":-4,\"Liderazgo\":4,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":0}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"Priorizar bien es decidir con criterios y comunicar el por que.\",\"options\":[]}]\n ', 0, 1, NULL, 14, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(8, NULL, 'Presentacion Inesperada', 'Debes presentar avances sin preparacion previa.', 'comunicacion', 'basico', '[{\"id\":0,\"text\":\"Te piden presentar ya. Tu enfoque inicial?\",\"options\":[{\"text\":\"A) Improvisar sin estructura\",\"result\":1,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":0,\"Toma de Decisiones\":0}},{\"text\":\"B) Organizar 3 ideas clave y objetivo\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":4,\"Toma de Decisiones\":8}}]},{\"id\":1,\"text\":\"Durante preguntas, hay tension.\",\"options\":[{\"text\":\"A) Escuchar, confirmar y responder breve\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":6}},{\"text\":\"B) Defenderte atacando\",\"result\":2,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-4,\"Trabajo en Equipo\":-6,\"Toma de Decisiones\":-4}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"Una estructura simple y la escucha activa salvan presentaciones inesperadas.\",\"options\":[]}]\n ', 0, 1, NULL, 12, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `scenario_id` int(11) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `completed_at` timestamp NULL DEFAULT NULL,
  `scores_json` text NOT NULL COMMENT '{"Comunicaci??n": 25, "Liderazgo": 15, ...}',
  `final_score` int(11) DEFAULT 0,
  `decisions_count` int(11) DEFAULT 0 COMMENT 'Total de decisiones tomadas',
  `completion_percentage` decimal(5,2) DEFAULT 0.00,
  `program_id` int(11) DEFAULT NULL COMMENT 'Programa asociado a esta sesi├│n',
  `context_json` text DEFAULT NULL COMMENT 'Contexto completo de la sesi├│n (soft skills, programa, historial)',
  `current_stage` int(11) DEFAULT 0 COMMENT 'Etapa actual del escenario din├ímico (0=no iniciado, 1-3=etapas)',
  `stage1_json` text DEFAULT NULL COMMENT 'Contenido JSON generado en etapa 1',
  `stage2_json` text DEFAULT NULL COMMENT 'Contenido JSON generado en etapa 2 (basado en respuesta etapa 1)',
  `stage3_json` text DEFAULT NULL COMMENT 'Contenido JSON generado en etapa 3 (cierre y evaluaci├│n)',
  `is_dynamic` tinyint(1) DEFAULT 0 COMMENT 'Indica si es una sesi├│n con generaci├│n din├ímica'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('aprendiz','instructor','admin') DEFAULT 'aprendiz',
  `profile_image` varchar(255) DEFAULT NULL,
  `ficha` varchar(50) DEFAULT NULL COMMENT 'N??mero de ficha del aprendiz',
  `programa` varchar(255) DEFAULT NULL COMMENT 'Programa de formaci??n',
  `active` tinyint(1) DEFAULT 1,
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_token` varchar(100) DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `reset_token_expires` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_image`, `ficha`, `programa`, `active`, `email_verified`, `verification_token`, `reset_token`, `reset_token_expires`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@rolplayedu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-27 05:06:30', '2026-01-27 05:06:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_achievements`
--

CREATE TABLE `user_achievements` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `achievement_id` int(11) NOT NULL,
  `unlocked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `user_performance`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `user_performance` (
`user_id` int(11)
,`name` varchar(100)
,`email` varchar(150)
,`ficha` varchar(50)
,`total_sessions` bigint(21)
,`completed_sessions` bigint(21)
,`average_score` decimal(13,2)
,`best_score` int(11)
,`achievements_count` bigint(21)
,`total_points` int(11)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_stats`
--

CREATE TABLE `user_stats` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_sessions` int(11) DEFAULT 0,
  `completed_sessions` int(11) DEFAULT 0,
  `total_points` int(11) DEFAULT 0,
  `average_score` decimal(5,2) DEFAULT 0.00,
  `best_competence` varchar(50) DEFAULT NULL,
  `scenarios_completed_ids` text DEFAULT NULL COMMENT 'JSON array de IDs completados',
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura para la vista `user_performance`
--
DROP TABLE IF EXISTS `user_performance`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_performance`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`ficha` AS `ficha`, count(distinct `s`.`id`) AS `total_sessions`, count(distinct case when `s`.`completed_at` is not null then `s`.`id` end) AS `completed_sessions`, round(avg(`s`.`final_score`),2) AS `average_score`, max(`s`.`final_score`) AS `best_score`, count(distinct `ua`.`achievement_id`) AS `achievements_count`, coalesce(`us`.`total_points`,0) AS `total_points` FROM (((`users` `u` left join `sessions` `s` on(`u`.`id` = `s`.`user_id`)) left join `user_achievements` `ua` on(`u`.`id` = `ua`.`user_id`)) left join `user_stats` `us` on(`u`.`id` = `us`.`user_id`)) WHERE `u`.`role` = 'aprendiz' GROUP BY `u`.`id`, `u`.`name`, `u`.`email`, `u`.`ficha`, `us`.`total_points` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `decisions`
--
ALTER TABLE `decisions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_step` (`step_number`),
  ADD KEY `idx_stage` (`stage`);

--
-- Indices de la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_read` (`is_read`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indices de la tabla `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_instructor` (`instructor_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_sector` (`sector`),
  ADD KEY `idx_soft_skills_generated` (`soft_skills_generated`);

--
-- Indices de la tabla `program_soft_skills`
--
ALTER TABLE `program_soft_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_program` (`program_id`),
  ADD KEY `idx_soft_skill_name` (`soft_skill_name`);

--
-- Indices de la tabla `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_instructor` (`instructor_id`),
  ADD KEY `idx_dates` (`start_date`,`end_date`);

--
-- Indices de la tabla `scenarios`
--
ALTER TABLE `scenarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_area` (`area`),
  ADD KEY `idx_program` (`program_id`),
  ADD KEY `idx_difficulty` (`difficulty`),
  ADD KEY `idx_active` (`active`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_scenario` (`scenario_id`),
  ADD KEY `idx_completed` (`completed_at`),
  ADD KEY `idx_final_score` (`final_score`),
  ADD KEY `idx_program` (`program_id`),
  ADD KEY `idx_current_stage` (`current_stage`),
  ADD KEY `idx_is_dynamic` (`is_dynamic`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_ficha` (`ficha`);

--
-- Indices de la tabla `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_achievement` (`user_id`,`achievement_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_achievement` (`achievement_id`);

--
-- Indices de la tabla `user_stats`
--
ALTER TABLE `user_stats`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_total_points` (`total_points`),
  ADD KEY `idx_last_activity` (`last_activity`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `achievements`
--
ALTER TABLE `achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `decisions`
--
ALTER TABLE `decisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `program_soft_skills`
--
ALTER TABLE `program_soft_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `scenarios`
--
ALTER TABLE `scenarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `user_achievements`
--
ALTER TABLE `user_achievements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `user_stats`
--
ALTER TABLE `user_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `decisions`
--
ALTER TABLE `decisions`
  ADD CONSTRAINT `decisions_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `program_soft_skills`
--
ALTER TABLE `program_soft_skills`
  ADD CONSTRAINT `program_soft_skills_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `routes`
--
ALTER TABLE `routes`
  ADD CONSTRAINT `routes_ibfk_1` FOREIGN KEY (`instructor_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `scenarios`
--
ALTER TABLE `scenarios`
  ADD CONSTRAINT `scenarios_ibfk_1` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `fk_sessions_program` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sessions_ibfk_2` FOREIGN KEY (`scenario_id`) REFERENCES `scenarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_achievements`
--
ALTER TABLE `user_achievements`
  ADD CONSTRAINT `user_achievements_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_achievements_ibfk_2` FOREIGN KEY (`achievement_id`) REFERENCES `achievements` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `user_stats`
--
ALTER TABLE `user_stats`
  ADD CONSTRAINT `user_stats_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
