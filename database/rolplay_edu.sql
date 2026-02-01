-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 01-02-2026 a las 17:50:35
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `achievements`
--

CREATE TABLE `achievements` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(50) DEFAULT NULL COMMENT 'Clase de Font Awesome',
  `criteria_json` text NOT NULL COMMENT 'Condiciones para desbloquear',
  `badge_icon` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT 'general',
  `requirement_type` varchar(50) DEFAULT NULL COMMENT 'Tipo de requisito: sessions_completed, avg_score, best_score, total_points, achievements_count',
  `requirement_value` int(11) DEFAULT NULL COMMENT 'Valor num├®rico del requisito',
  `points` int(11) DEFAULT 0 COMMENT 'Puntos que otorga el logro',
  `is_active` tinyint(1) DEFAULT 1 COMMENT 'Si el logro est├í activo',
  `rarity` enum('common','rare','epic','legendary') DEFAULT 'common',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `achievements`
--

INSERT INTO `achievements` (`id`, `name`, `description`, `icon`, `criteria_json`, `badge_icon`, `category`, `requirement_type`, `requirement_value`, `points`, `is_active`, `rarity`, `created_at`) VALUES
(1, 'Primer Paso', 'Completa tu primera simulación de escenario laboral.', 'fa-shoe-prints', '', NULL, 'progreso', 'sessions_completed', 1, 10, 1, 'common', '2026-01-30 02:32:34'),
(2, 'Aprendiz Dedicado', 'Completa 5 simulaciones de escenarios laborales.', 'fa-graduation-cap', '', NULL, 'progreso', 'sessions_completed', 5, 25, 1, 'common', '2026-01-30 02:32:34'),
(3, 'Practicante Avanzado', 'Completa 10 simulaciones de escenarios laborales.', 'fa-user-graduate', '', NULL, 'progreso', 'sessions_completed', 10, 50, 1, 'common', '2026-01-30 02:32:34'),
(4, 'Veterano SENA', 'Completa 25 simulaciones de escenarios laborales.', 'fa-medal', '', NULL, 'progreso', 'sessions_completed', 25, 100, 1, 'common', '2026-01-30 02:32:34'),
(5, 'Maestro de Escenarios', 'Completa 50 simulaciones de escenarios laborales.', 'fa-crown', '', NULL, 'progreso', 'sessions_completed', 50, 200, 1, 'common', '2026-01-30 02:32:34'),
(6, 'Desempeño Sólido', 'Alcanza un promedio de 60% en tus competencias.', 'fa-star', '', NULL, 'excelencia', 'avg_score', 60, 20, 1, 'common', '2026-01-30 02:32:34'),
(7, 'Competente', 'Alcanza un promedio de 70% en tus competencias.', 'fa-star-half-alt', '', NULL, 'excelencia', 'avg_score', 70, 40, 1, 'common', '2026-01-30 02:32:34'),
(8, 'Destacado', 'Alcanza un promedio de 80% en tus competencias.', 'fa-certificate', '', NULL, 'excelencia', 'avg_score', 80, 75, 1, 'common', '2026-01-30 02:32:34'),
(9, 'Excelente', 'Alcanza un promedio de 90% en tus competencias.', 'fa-trophy', '', NULL, 'excelencia', 'avg_score', 90, 150, 1, 'common', '2026-01-30 02:32:34'),
(10, 'Perfección', 'Alcanza un promedio de 95% o más en tus competencias.', 'fa-gem', '', NULL, 'excelencia', 'avg_score', 95, 250, 1, 'common', '2026-01-30 02:32:34'),
(11, 'Comunicador Efectivo', 'Alcanza 80 puntos o más en Comunicación.', 'fa-comments', '', NULL, 'excelencia', 'competence_comunicacion', 80, 50, 1, 'common', '2026-01-30 02:32:34'),
(12, 'Líder Natural', 'Alcanza 80 puntos o más en Liderazgo.', 'fa-user-tie', '', NULL, 'excelencia', 'competence_liderazgo', 80, 50, 1, 'common', '2026-01-30 02:32:34'),
(13, 'Espíritu de Equipo', 'Alcanza 80 puntos o más en Trabajo en Equipo.', 'fa-users', '', NULL, 'excelencia', 'competence_trabajo_equipo', 80, 50, 1, 'common', '2026-01-30 02:32:34'),
(14, 'Decisiones Acertadas', 'Alcanza 80 puntos o más en Toma de Decisiones.', 'fa-lightbulb', '', NULL, 'excelencia', 'competence_toma_decisiones', 80, 50, 1, 'common', '2026-01-30 02:32:34'),
(15, 'Maestro de Competencias', 'Alcanza 90 puntos o más en las 4 competencias transversales.', 'fa-award', '', NULL, 'excelencia', 'all_competences', 90, 200, 1, 'common', '2026-01-30 02:32:34'),
(16, 'Primeros Contactos', 'Interactúa con 5 personas diferentes en simulaciones.', 'fa-handshake', '', NULL, 'social', 'interactions_count', 5, 15, 1, 'common', '2026-01-30 02:32:34'),
(17, 'Red de Colaboración', 'Interactúa con 10 personas diferentes en simulaciones.', 'fa-project-diagram', '', NULL, 'social', 'interactions_count', 10, 30, 1, 'common', '2026-01-30 02:32:34'),
(18, 'Conector Social', 'Interactúa con 25 personas diferentes en simulaciones.', 'fa-network-wired', '', NULL, 'social', 'interactions_count', 25, 75, 1, 'common', '2026-01-30 02:32:34'),
(19, 'Racha de 3', 'Completa 3 simulaciones consecutivas con 80% o más.', 'fa-fire', '', NULL, 'especial', 'streak', 3, 40, 1, 'common', '2026-01-30 02:32:34'),
(20, 'Racha de 5', 'Completa 5 simulaciones consecutivas con 80% o más.', 'fa-fire-alt', '', NULL, 'especial', 'streak', 5, 80, 1, 'common', '2026-01-30 02:32:34'),
(21, 'Imparable', 'Completa 10 simulaciones consecutivas con 80% o más.', 'fa-infinity', '', NULL, 'especial', 'streak', 10, 150, 1, 'common', '2026-01-30 02:32:34'),
(22, 'Explorador', 'Completa escenarios de 3 áreas diferentes.', 'fa-compass', '', NULL, 'especial', 'areas_explored', 3, 35, 1, 'common', '2026-01-30 02:32:34'),
(23, 'Versátil', 'Completa escenarios de 5 áreas diferentes.', 'fa-globe', '', NULL, 'especial', 'areas_explored', 5, 70, 1, 'common', '2026-01-30 02:32:34'),
(24, 'Velocista', 'Completa una simulación en menos de 10 minutos.', 'fa-stopwatch', '', NULL, 'especial', 'completion_time', 10, 30, 0, 'common', '2026-01-30 02:32:34'),
(25, 'Madrugador', 'Completa una simulación antes de las 8:00 AM.', 'fa-sun', '', NULL, 'especial', 'early_bird', 1, 20, 0, 'common', '2026-01-30 02:32:34'),
(26, 'Noctámbulo', 'Completa una simulación después de las 10:00 PM.', 'fa-moon', '', NULL, 'especial', 'night_owl', 1, 20, 0, 'common', '2026-01-30 02:32:34'),
(27, 'Bienvenido a RolPlay EDU', 'Inicia sesión en la plataforma por primera vez.', 'fa-hand-peace', '', NULL, 'general', 'first_login', 1, 5, 1, 'common', '2026-01-30 02:32:34'),
(28, 'Perfil Completo', 'Completa toda la información de tu perfil de usuario.', 'fa-id-card', '', NULL, 'general', 'profile_complete', 1, 10, 1, 'common', '2026-01-30 02:32:34'),
(29, 'Tecnología en Acción', 'Completa un escenario del área de Tecnología.', 'fa-laptop-code', '', NULL, 'general', 'area_tecnologia', 1, 15, 1, 'common', '2026-01-30 02:32:34'),
(30, 'Comercio Exitoso', 'Completa un escenario del área de Comercio.', 'fa-store', '', NULL, 'general', 'area_comercio', 1, 15, 1, 'common', '2026-01-30 02:32:34'),
(31, 'Salud es Vida', 'Completa un escenario del área de Salud.', 'fa-heartbeat', '', NULL, 'general', 'area_salud', 1, 15, 1, 'common', '2026-01-30 02:32:34'),
(32, 'Ingenio Industrial', 'Completa un escenario del área Industrial.', 'fa-industry', '', NULL, 'general', 'area_industrial', 1, 15, 1, 'common', '2026-01-30 02:32:34'),
(33, 'Agro Sostenible', 'Completa un escenario del área Agropecuario.', 'fa-leaf', '', NULL, 'general', 'area_agropecuario', 1, 15, 1, 'common', '2026-01-30 02:32:34'),
(34, 'Básico Dominado', 'Completa un escenario de nivel Básico.', 'fa-check', '', NULL, 'general', 'difficulty_basico', 1, 10, 1, 'common', '2026-01-30 02:32:34'),
(35, 'Intermedio Superado', 'Completa un escenario de nivel Intermedio.', 'fa-check-double', '', NULL, 'general', 'difficulty_intermedio', 1, 20, 1, 'common', '2026-01-30 02:32:34'),
(36, 'Avanzado Conquistado', 'Completa un escenario de nivel Avanzado.', 'fa-crown', '', NULL, 'general', 'difficulty_avanzado', 1, 40, 1, 'common', '2026-01-30 02:32:34'),
(37, 'Coleccionista', 'Desbloquea 10 logros diferentes.', 'fa-boxes', '', NULL, 'general', 'achievements_unlocked', 10, 50, 1, 'common', '2026-01-30 02:32:34'),
(38, 'Acumulador de Puntos', 'Alcanza 500 puntos de logros totales.', 'fa-coins', '', NULL, 'general', 'total_achievement_points', 500, 100, 1, 'common', '2026-01-30 02:32:34'),
(39, 'Leyenda de RolPlay', 'Alcanza 1000 puntos de logros totales.', 'fa-trophy', '', NULL, 'general', 'total_achievement_points', 1000, 250, 1, 'common', '2026-01-30 02:32:34');

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

--
-- Volcado de datos para la tabla `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `link`, `created_at`) VALUES
(1, 2, 'system', 'Analisis de programa completado', 'El analisis del programa \"ADSO\" ya finalizo.', 1, '/instructor/programs/4', '2026-01-30 06:52:01');

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
  `soft_skills_generated` tinyint(1) DEFAULT 0 COMMENT 'Indica si ya se identificaron las soft skills del programa',
  `estado_analisis` enum('PENDIENTE','PROCESANDO','COMPLETADO','ERROR') DEFAULT 'PENDIENTE',
  `resultado_gemini` longtext DEFAULT NULL,
  `competencias_text` longtext DEFAULT NULL,
  `perfil_egreso_text` longtext DEFAULT NULL,
  `pdf_original_name` varchar(255) DEFAULT NULL COMMENT 'Nombre original del PDF cargado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `programs`
--

INSERT INTO `programs` (`id`, `instructor_id`, `title`, `codigo_programa`, `pdf_path`, `analysis_json`, `status`, `created_at`, `updated_at`, `sector`, `soft_skills_generated`, `estado_analisis`, `resultado_gemini`, `competencias_text`, `perfil_egreso_text`, `pdf_original_name`) VALUES
(10, 2, 'Analisis y desarrollo de software', '228118', '/uploads/programs/program_20260130_083829_db8cc82f27d3.pdf', '{\"nombre\":\"Analisis y desarrollo de software\",\"codigo_programa\":\"228118\",\"nivel\":\"Tecnólogo\",\"perfil_egresado\":\"El egresado será un tecnólogo en análisis y desarrollo de software, capaz de diseñar, programar e implementar soluciones para el sector TI, ya sea en empresas o de forma independiente. Contribuirá a la innovación y mejora, manteniéndose actualizado con las tendencias tecnológicas del mercado.\",\"competencias\":[\"Aplicación de conocimientos de las ciencias naturales de acuerdo con situaciones del contexto productivo y social\",\"Aplicar prácticas de protección ambiental, seguridad y salud en el trabajo de acuerdo con las políticas organizacionales y la normatividad vigente\",\"Controlar la calidad del servicio de software de acuerdo con los estándares técnicos\",\"Desarrollar la solución de software de acuerdo con el diseño y metodologías de desarrollo\",\"Desarrollar procesos de comunicación eficaces y efectivos, teniendo en cuenta situaciones de orden social, personal y\",\"Diseñar la solución de software de acuerdo con procedimientos y requisitos técnicos\",\"Ejercer derechos fundamentales del trabajo en el marco de la constitución política y los convenios internacionales\",\"Enrique Low Murtra-Interactuar en el contexto productivo y social de acuerdo con principios éticos para la construcción de una cultura de paz\",\"Establecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico\",\"Estructurar propuesta técnica de servicio de tecnología de la información según requisitos técnicos y normativa\",\"Evaluar requisitos de la solución de software de acuerdo con metodologías de análisis y estándares\",\"Generar hábitos saludables de vida mediante la aplicación de programas de actividad física en los contextos productivos y sociales\",\"Gestionar procesos propios de la cultura emprendedora y empresarial de acuerdo con el perfil personal y los requerimientos de los contextos productivo y social\",\"Implementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia\",\"Interactuar en lengua inglesa de forma oral y escrita dentro de contextos sociales y laborales según los criterios establecidos por el marco común europeo de referencia para las lenguas\",\"Orientar investigación formativa según referentes técnicos\",\"Razonar cuantitativamente frente a situaciones susceptibles de ser abordadas de manera matemática en contextos laborales, sociales y personales\",\"Utilizar herramientas informáticas de acuerdo con las necesidades de manejo de información\"],\"resultados_aprendizaje\":[\"Controlar la calidad del servicio de software de acuerdo con los estándares técnicos\",\"DESARROLLAR LA SOLUCIÓN DE SOFTWARE de acuerdo con el diseño y metodologías de desarrollo\",\"Diseñar la solución de software de acuerdo con procedimientos y requisitos técnicos\",\"Establecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico\",\"Implementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia\"],\"contextos_laborales\":[\"Empresas públicas o privadas en el sector TI\",\"Analista de software en proyectos específicos\",\"Diseñador de software en proyectos específicos\",\"Programador de software en proyectos específicos\",\"Prestador de servicios personales como analista, diseñador y programador de software independiente\",\"Parte de unidades productivas en procesos de desarrollo de software\",\"Técnicos en Tecnologías de la Información\"],\"sector\":\"Tecnología\",\"soft_skills\":[{\"nombre\":\"Comunicación Asertiva\",\"peso\":20,\"criterios\":[\"Expresa ideas de forma clara y respetuosa\",\"Escucha activamente a equipos y clientes\",\"Participa en interacciones constructivas\"],\"descripcion\":\"Habilidad para expresar pensamientos, sentimientos y necesidades de manera clara, directa y honesta, respetando los derechos de los demás.\"},{\"nombre\":\"Trabajo en Equipo\",\"peso\":20,\"criterios\":[\"Colabora en proyectos de software\",\"Comparte responsabilidades y apoya a compañeros\",\"Contribuye al logro de metas comunes\"],\"descripcion\":\"Capacidad para colaborar eficazmente con otros, compartiendo responsabilidades y trabajando hacia un objetivo común, valorando la diversidad de ideas y aportes.\"},{\"nombre\":\"Orientación al Logro de Objetivos\",\"peso\":20,\"criterios\":[\"Cumple plazos y busca la mejora continua\",\"Finaliza tareas con calidad y eficiencia\",\"Supera obstáculos para alcanzar resultados\"],\"descripcion\":\"Impulso para alcanzar metas y resultados específicos, mostrando persistencia, proactividad y enfoque en la calidad y eficiencia del trabajo.\"},{\"nombre\":\"Capacidad de Adaptación al Cambio\",\"peso\":20,\"criterios\":[\"Se ajusta a nuevas tecnologías y metodologías\",\"Maneja la incertidumbre y aprende de nuevas situaciones\",\"Mantiene productividad ante cambios\"],\"descripcion\":\"Habilidad para ajustarse y responder positivamente a nuevas situaciones, tecnologías o metodologías de trabajo, manteniendo la productividad y la actitud proactiva.\"},{\"nombre\":\"Pensamiento Crítico\",\"peso\":20,\"criterios\":[\"Analiza problemas de software de forma objetiva\",\"Evalúa soluciones y toma decisiones fundamentadas\",\"Identifica causas raíz y propone mejoras\"],\"descripcion\":\"Capacidad para analizar información de manera objetiva, identificar problemas, evaluar argumentos y tomar decisiones fundamentadas y lógicas.\"}],\"_meta\":{\"source\":\"puter\",\"model\":\"gemini-2.5-flash\",\"generated_at\":\"2026-01-30T13:38:27.189Z\"},\"soft_skills_generated\":true}', 'completed', '2026-01-30 13:38:29', '2026-01-30 13:38:29', 'Tecnología', 1, 'COMPLETADO', '{\"nombre\":\"Analisis y desarrollo de software\",\"codigo_programa\":\"228118\",\"nivel\":\"Tecnólogo\",\"perfil_egresado\":\"El egresado será un tecnólogo en análisis y desarrollo de software, capaz de diseñar, programar e implementar soluciones para el sector TI, ya sea en empresas o de forma independiente. Contribuirá a la innovación y mejora, manteniéndose actualizado con las tendencias tecnológicas del mercado.\",\"competencias\":[\"Aplicación de conocimientos de las ciencias naturales de acuerdo con situaciones del contexto productivo y social\",\"Aplicar prácticas de protección ambiental, seguridad y salud en el trabajo de acuerdo con las políticas organizacionales y la normatividad vigente\",\"Controlar la calidad del servicio de software de acuerdo con los estándares técnicos\",\"Desarrollar la solución de software de acuerdo con el diseño y metodologías de desarrollo\",\"Desarrollar procesos de comunicación eficaces y efectivos, teniendo en cuenta situaciones de orden social, personal y\",\"Diseñar la solución de software de acuerdo con procedimientos y requisitos técnicos\",\"Ejercer derechos fundamentales del trabajo en el marco de la constitución política y los convenios internacionales\",\"Enrique Low Murtra-Interactuar en el contexto productivo y social de acuerdo con principios éticos para la construcción de una cultura de paz\",\"Establecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico\",\"Estructurar propuesta técnica de servicio de tecnología de la información según requisitos técnicos y normativa\",\"Evaluar requisitos de la solución de software de acuerdo con metodologías de análisis y estándares\",\"Generar hábitos saludables de vida mediante la aplicación de programas de actividad física en los contextos productivos y sociales\",\"Gestionar procesos propios de la cultura emprendedora y empresarial de acuerdo con el perfil personal y los requerimientos de los contextos productivo y social\",\"Implementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia\",\"Interactuar en lengua inglesa de forma oral y escrita dentro de contextos sociales y laborales según los criterios establecidos por el marco común europeo de referencia para las lenguas\",\"Orientar investigación formativa según referentes técnicos\",\"Razonar cuantitativamente frente a situaciones susceptibles de ser abordadas de manera matemática en contextos laborales, sociales y personales\",\"Utilizar herramientas informáticas de acuerdo con las necesidades de manejo de información\"],\"resultados_aprendizaje\":[\"Controlar la calidad del servicio de software de acuerdo con los estándares técnicos\",\"DESARROLLAR LA SOLUCIÓN DE SOFTWARE de acuerdo con el diseño y metodologías de desarrollo\",\"Diseñar la solución de software de acuerdo con procedimientos y requisitos técnicos\",\"Establecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico\",\"Implementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia\"],\"contextos_laborales\":[\"Empresas públicas o privadas en el sector TI\",\"Analista de software en proyectos específicos\",\"Diseñador de software en proyectos específicos\",\"Programador de software en proyectos específicos\",\"Prestador de servicios personales como analista, diseñador y programador de software independiente\",\"Parte de unidades productivas en procesos de desarrollo de software\",\"Técnicos en Tecnologías de la Información\"],\"sector\":\"Tecnología\",\"soft_skills\":[{\"nombre\":\"Comunicación Asertiva\",\"peso\":20,\"criterios\":[\"Expresa ideas de forma clara y respetuosa\",\"Escucha activamente a equipos y clientes\",\"Participa en interacciones constructivas\"],\"descripcion\":\"Habilidad para expresar pensamientos, sentimientos y necesidades de manera clara, directa y honesta, respetando los derechos de los demás.\"},{\"nombre\":\"Trabajo en Equipo\",\"peso\":20,\"criterios\":[\"Colabora en proyectos de software\",\"Comparte responsabilidades y apoya a compañeros\",\"Contribuye al logro de metas comunes\"],\"descripcion\":\"Capacidad para colaborar eficazmente con otros, compartiendo responsabilidades y trabajando hacia un objetivo común, valorando la diversidad de ideas y aportes.\"},{\"nombre\":\"Orientación al Logro de Objetivos\",\"peso\":20,\"criterios\":[\"Cumple plazos y busca la mejora continua\",\"Finaliza tareas con calidad y eficiencia\",\"Supera obstáculos para alcanzar resultados\"],\"descripcion\":\"Impulso para alcanzar metas y resultados específicos, mostrando persistencia, proactividad y enfoque en la calidad y eficiencia del trabajo.\"},{\"nombre\":\"Capacidad de Adaptación al Cambio\",\"peso\":20,\"criterios\":[\"Se ajusta a nuevas tecnologías y metodologías\",\"Maneja la incertidumbre y aprende de nuevas situaciones\",\"Mantiene productividad ante cambios\"],\"descripcion\":\"Habilidad para ajustarse y responder positivamente a nuevas situaciones, tecnologías o metodologías de trabajo, manteniendo la productividad y la actitud proactiva.\"},{\"nombre\":\"Pensamiento Crítico\",\"peso\":20,\"criterios\":[\"Analiza problemas de software de forma objetiva\",\"Evalúa soluciones y toma decisiones fundamentadas\",\"Identifica causas raíz y propone mejoras\"],\"descripcion\":\"Capacidad para analizar información de manera objetiva, identificar problemas, evaluar argumentos y tomar decisiones fundamentadas y lógicas.\"}],\"_meta\":{\"source\":\"puter\",\"model\":\"gemini-2.5-flash\",\"generated_at\":\"2026-01-30T13:38:27.189Z\"},\"soft_skills_generated\":true}', 'Aplicación de conocimientos de las ciencias naturales de acuerdo con situaciones del contexto productivo y social\r\nAplicar prácticas de protección ambiental, seguridad y salud en el trabajo de acuerdo con las políticas organizacionales y la normatividad vigente\r\nControlar la calidad del servicio de software de acuerdo con los estándares técnicos\r\nDesarrollar la solución de software de acuerdo con el diseño y metodologías de desarrollo\r\nDesarrollar procesos de comunicación eficaces y efectivos, teniendo en cuenta situaciones de orden social, personal y\r\nDiseñar la solución de software de acuerdo con procedimientos y requisitos técnicos\r\nEjercer derechos fundamentales del trabajo en el marco de la constitución política y los convenios internacionales\r\nEnrique Low Murtra-Interactuar en el contexto productivo y social de acuerdo con principios éticos para la construcción de una cultura de paz\r\nEstablecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico\r\nEstructurar propuesta técnica de servicio de tecnología de la información según requisitos técnicos y normativa\r\nEvaluar requisitos de la solución de software de acuerdo con metodologías de análisis y estándares\r\nGenerar hábitos saludables de vida mediante la aplicación de programas de actividad física en los contextos productivos y sociales\r\nGestionar procesos propios de la cultura emprendedora y empresarial de acuerdo con el perfil personal y los requerimientos de los contextos productivo y social\r\nImplementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia\r\nInteractuar en lengua inglesa de forma oral y escrita dentro de contextos sociales y laborales según los criterios establecidos por el marco común europeo de referencia para las lenguas\r\nOrientar investigación formativa según referentes técnicos\r\nRazonar cuantitativamente frente a situaciones susceptibles de ser abordadas de manera matemática en contextos laborales, sociales y personales\r\nUtilizar herramientas informáticas de acuerdo con las necesidades de manejo de información', 'El egresado será un tecnólogo en análisis y desarrollo de software, capaz de diseñar, programar e implementar soluciones para el sector TI, ya sea en empresas o de forma independiente. Contribuirá a la innovación y mejora, manteniéndose actualizado con las tendencias tecnológicas del mercado.', 'Programa ADSO.pdf');

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

--
-- Volcado de datos para la tabla `program_soft_skills`
--

INSERT INTO `program_soft_skills` (`id`, `program_id`, `soft_skill_name`, `weight`, `criteria_json`, `description`, `created_at`, `updated_at`) VALUES
(31, 10, 'Comunicación Asertiva', 20.00, '[\"Expresa ideas de forma clara y respetuosa\",\"Escucha activamente a equipos y clientes\",\"Participa en interacciones constructivas\"]', 'Habilidad para expresar pensamientos, sentimientos y necesidades de manera clara, directa y honesta, respetando los derechos de los demás.', '2026-01-30 13:38:29', '2026-01-30 13:38:29'),
(32, 10, 'Trabajo en Equipo', 20.00, '[\"Colabora en proyectos de software\",\"Comparte responsabilidades y apoya a compa\\u00f1eros\",\"Contribuye al logro de metas comunes\"]', 'Capacidad para colaborar eficazmente con otros, compartiendo responsabilidades y trabajando hacia un objetivo común, valorando la diversidad de ideas y aportes.', '2026-01-30 13:38:29', '2026-01-30 13:38:29'),
(33, 10, 'Orientación al Logro de Objetivos', 20.00, '[\"Cumple plazos y busca la mejora continua\",\"Finaliza tareas con calidad y eficiencia\",\"Supera obst\\u00e1culos para alcanzar resultados\"]', 'Impulso para alcanzar metas y resultados específicos, mostrando persistencia, proactividad y enfoque en la calidad y eficiencia del trabajo.', '2026-01-30 13:38:29', '2026-01-30 13:38:29'),
(34, 10, 'Capacidad de Adaptación al Cambio', 20.00, '[\"Se ajusta a nuevas tecnolog\\u00edas y metodolog\\u00edas\",\"Maneja la incertidumbre y aprende de nuevas situaciones\",\"Mantiene productividad ante cambios\"]', 'Habilidad para ajustarse y responder positivamente a nuevas situaciones, tecnologías o metodologías de trabajo, manteniendo la productividad y la actitud proactiva.', '2026-01-30 13:38:29', '2026-01-30 13:38:29'),
(35, 10, 'Pensamiento Crítico', 20.00, '[\"Analiza problemas de software de forma objetiva\",\"Eval\\u00faa soluciones y toma decisiones fundamentadas\",\"Identifica causas ra\\u00edz y propone mejoras\"]', 'Capacidad para analizar información de manera objetiva, identificar problemas, evaluar argumentos y tomar decisiones fundamentadas y lógicas.', '2026-01-30 13:38:29', '2026-01-30 13:38:29');

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
(1, NULL, 'Cambio de Requisitos', 'El cliente cambia prioridades a mitad del sprint.', 'tecnologia', 'intermedio', '[{\"id\":0,\"text\":\"El cliente pide cambios urgentes que afectan el plan. Que haces primero?\",\"options\":[{\"text\":\"A) Aceptar todo sin analizar\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-5,\"Liderazgo\":-5,\"Trabajo en Equipo\":-5,\"Toma de Decisiones\":-5}},{\"text\":\"B) Analizar impacto y alinear con el equipo\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":8,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":10}},{\"text\":\"C) Ignorar el cambio\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-6,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":-8}}]},{\"id\":1,\"text\":\"El equipo esta confundido con la nueva prioridad.\",\"options\":[{\"text\":\"A) Hacer una mini reunion y clarificar objetivos\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":10,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":6}},{\"text\":\"B) Dejar que cada quien interprete\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":-2,\"Toma de Decisiones\":-2}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"Una buena gestion del cambio combina analisis, comunicacion y acuerdos claros.\",\"options\":[]}]\n ', 0, 0, NULL, 15, 1, '2026-01-27 05:13:19', '2026-01-30 05:37:07'),
(2, NULL, 'Feedback Conflictivo', 'Debes dar retroalimentacion a un companero molesto.', 'comercio', 'basico', '[{\"id\":0,\"text\":\"Un companero reacciona mal al feedback. Como inicias?\",\"options\":[{\"text\":\"A) Le dices que se aguante\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-4,\"Trabajo en Equipo\":-8,\"Toma de Decisiones\":-6}},{\"text\":\"B) Preguntas como se siente y contextualizas\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":6}}]},{\"id\":1,\"text\":\"Necesitas ser claro sin herir.\",\"options\":[{\"text\":\"A) Usar ejemplos concretos y proponer mejoras\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":8,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":6}},{\"text\":\"B) Hablar en generalidades\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":0,\"Toma de Decisiones\":0}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"El feedback efectivo es oportuno, especifico y empatico.\",\"options\":[]}]\n ', 0, 1, NULL, 12, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(3, NULL, 'Proveedor Inconfiable', 'Un proveedor incumple y pone en riesgo la entrega.', 'logistica', 'intermedio', '[{\"id\":0,\"text\":\"El proveedor no responde. Que haces?\",\"options\":[{\"text\":\"A) Esperar sin plan B\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-4,\"Liderazgo\":-6,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":-10}},{\"text\":\"B) Escalar y activar alternativas\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":10,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":10}}]},{\"id\":1,\"text\":\"Debes informar a stakeholders.\",\"options\":[{\"text\":\"A) Comunicar impacto y mitigacion\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":8}},{\"text\":\"B) Ocultar el problema\",\"result\":2,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-6,\"Trabajo en Equipo\":-6,\"Toma de Decisiones\":-8}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"La gestion de riesgos exige transparencia y decisiones oportunas.\",\"options\":[]}]\n ', 0, 1, NULL, 18, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(4, NULL, 'Error en Produccion', 'Se detecta un fallo critico en un sistema en uso.', 'tecnologia', 'avanzado', '[{\"id\":0,\"text\":\"Hay un error critico. Tu primer paso?\",\"options\":[{\"text\":\"A) Buscar culpables\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-8,\"Liderazgo\":-8,\"Trabajo en Equipo\":-10,\"Toma de Decisiones\":-4}},{\"text\":\"B) Contener el impacto y abrir incidente\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":10,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":10}}]},{\"id\":1,\"text\":\"El equipo necesita direccion.\",\"options\":[{\"text\":\"A) Asignar roles y tiempos claros\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":10,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}},{\"text\":\"B) Dejar que todo fluya\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":-2,\"Trabajo en Equipo\":-2,\"Toma de Decisiones\":-4}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"En crisis: primero contener, luego comunicar, despues aprender.\",\"options\":[]}]\n ', 0, 1, NULL, 20, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(5, NULL, 'Idea Rechazada', 'Tu propuesta fue rechazada en una reunion.', 'comercio', 'basico', '[{\"id\":0,\"text\":\"Rechazan tu idea. Como respondes?\",\"options\":[{\"text\":\"A) Te ofendes y te cierras\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-8,\"Liderazgo\":-4,\"Trabajo en Equipo\":-8,\"Toma de Decisiones\":-4}},{\"text\":\"B) Pides criterios y aprendes\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":8}}]},{\"id\":1,\"text\":\"Quieres mejorarla.\",\"options\":[{\"text\":\"A) Iterar con feedback y datos\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":6,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}},{\"text\":\"B) Guardarla sin accion\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":0,\"Toma de Decisiones\":-2}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"La resiliencia convierte el rechazo en aprendizaje accionable.\",\"options\":[]}]\n ', 0, 1, NULL, 10, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(6, NULL, 'Companero con Dificultades', 'Un companero esta bajando su rendimiento.', 'servicio', 'intermedio', '[{\"id\":0,\"text\":\"Notas que alguien no esta bien. Que haces?\",\"options\":[{\"text\":\"A) Ignorar para no meterte\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-6,\"Liderazgo\":-4,\"Trabajo en Equipo\":-10,\"Toma de Decisiones\":-4}},{\"text\":\"B) Conversar en privado con respeto\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":6}}]},{\"id\":1,\"text\":\"Hay barreras personales y de trabajo.\",\"options\":[{\"text\":\"A) Ofrecer apoyo y ajustar expectativas\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":8,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":6}},{\"text\":\"B) Reportar sin hablar antes\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":-2,\"Liderazgo\":2,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":0}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"El apoyo oportuno fortalece la cohesion del equipo.\",\"options\":[]}]\n ', 0, 1, NULL, 16, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(7, NULL, 'Prioridades del Proyecto', 'Exceso de tareas y todas parecen urgentes.', 'tecnologia', 'intermedio', '[{\"id\":0,\"text\":\"Todo parece urgente. Como priorizas?\",\"options\":[{\"text\":\"A) Hacer lo primero que llegue\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-2,\"Liderazgo\":-6,\"Trabajo en Equipo\":-2,\"Toma de Decisiones\":-10}},{\"text\":\"B) Usar criterios claros de impacto y esfuerzo\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":6,\"Liderazgo\":10,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":10}}]},{\"id\":1,\"text\":\"Necesitas alinear al equipo.\",\"options\":[{\"text\":\"A) Explicar prioridades y por que\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":8,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}},{\"text\":\"B) Imponer sin contexto\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":-4,\"Liderazgo\":4,\"Trabajo en Equipo\":-4,\"Toma de Decisiones\":0}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"Priorizar bien es decidir con criterios y comunicar el por que.\",\"options\":[]}]\n ', 0, 1, NULL, 14, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(8, NULL, 'Presentacion Inesperada', 'Debes presentar avances sin preparacion previa.', 'comunicacion', 'basico', '[{\"id\":0,\"text\":\"Te piden presentar ya. Tu enfoque inicial?\",\"options\":[{\"text\":\"A) Improvisar sin estructura\",\"result\":1,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":0,\"Liderazgo\":0,\"Trabajo en Equipo\":0,\"Toma de Decisiones\":0}},{\"text\":\"B) Organizar 3 ideas clave y objetivo\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":4,\"Toma de Decisiones\":8}}]},{\"id\":1,\"text\":\"Durante preguntas, hay tension.\",\"options\":[{\"text\":\"A) Escuchar, confirmar y responder breve\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":6,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":6}},{\"text\":\"B) Defenderte atacando\",\"result\":2,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":-10,\"Liderazgo\":-4,\"Trabajo en Equipo\":-6,\"Toma de Decisiones\":-4}}]},{\"id\":2,\"text\":\"Cierre del caso.\",\"feedbackText\":\"Una estructura simple y la escucha activa salvan presentaciones inesperadas.\",\"options\":[]}]\n ', 0, 1, NULL, 12, 1, '2026-01-27 05:13:19', '2026-01-27 05:13:19'),
(10, 10, 'El Dilema del Despliegue Crítico: Gestión de Crisis en Software', 'Eres un tecnólogo en análisis y desarrollo de software en \'Innovatech Solutions\'. Tu equipo está a punto de desplegar la fase inicial de un sistema de gestión para un cliente importante. A pocos días de la entrega final, un tester reporta un error crítico: una funcionalidad clave implementada no cumple con la necesidad real del cliente, aunque sí con la especificación inicial del documento de requisitos. El cliente es conocido por su inflexibilidad con los plazos.', 'Desarrollo de Software y Gestión de Proyectos TI', 'avanzado', '[{\"id\":0,\"text\":\"Tu tester, Ana, te llama alarmada: \'¡Tenemos un problema serio! La funcionalidad de cálculo de inventario que desarrollamos no contempla la lógica de descuentos por volumen que el cliente utiliza. Si la desplegamos así, tendrán pérdidas significativas. La especificación decía solo \'cálculo estándar\'.\' Tienes solo 3 días para el despliegue.\",\"options\":[{\"text\":\"Contactar al cliente para explicar la situación, las consecuencias y proponer una solución, solicitando una extensión o priorización.\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":8,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":9}},{\"text\":\"Pedir a Ana que documente el incidente y liberar la versión actual, planificando una corrección post-lanzamiento como \'mejora\'.\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":3,\"Liderazgo\":2,\"Trabajo en Equipo\":4,\"Toma de Decisiones\":3}},{\"text\":\"Reunir al equipo de desarrollo para ver si se puede implementar un \'parche\' rápido sin notificar al cliente.\",\"result\":1,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":5,\"Liderazgo\":7,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":6}}]},{\"id\":1,\"text\":\"Has contactado al gerente de proyecto del cliente, el Sr. López. Está visiblemente molesto: \'¡No puedo creerlo! ¿Cómo es posible que esto suceda a estas alturas? Necesitamos esta funcionalidad sí o sí para el lanzamiento. Mi director está presionando por el cumplimiento de los plazos. ¿Qué proponen?\'\",\"options\":[{\"text\":\"Asumir la responsabilidad y ofrecer un plan de contingencia detallado: \'Reconocemos el error y asumimos la responsabilidad. Proponemos un equipo dedicado a esta corrección con entrega prioritaria en 5 días adicionales, mientras el resto del sistema puede ser pre-desplegado. Adjunto el plan de trabajo detallado.\'\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":9,\"Trabajo en Equipo\":7,\"Toma de Decisiones\":9}},{\"text\":\"Culpar a la ambigüedad de los requisitos iniciales: \'La especificación original era ambigua. Estamos trabajando para adaptarla, pero esto requiere tiempo. ¿Podrían revisitar los requisitos para una futura fase?\'\",\"result\":2,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":4,\"Liderazgo\":3,\"Trabajo en Equipo\":5,\"Toma de Decisiones\":4}},{\"text\":\"Ofrecer trabajar 24\\/7 sin extensión de plazo: \'Entendemos la urgencia. Vamos a trabajar intensivamente, incluso noches y fines de semana, para intentar tenerlo listo en el plazo original, aunque el riesgo de errores aumentará.\'\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":6,\"Liderazgo\":6,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":5}}]},{\"id\":2,\"text\":\"El Sr. López ha aceptado tu propuesta con la condición de que el plan de 5 días sea estricto y sin fallos. Ahora, debes comunicar esto a tu equipo, que ya está agotado, y organizar la solución técnica. Sabes que la corrección no es trivial y requiere integración con otros módulos.\",\"options\":[{\"text\":\"Liderar con empatía y delegación clara: \'Equipo, hemos tenido un contratiempo, pero lo hemos gestionado con el cliente. Necesitamos un esfuerzo adicional y estratégico. He reasignado prioridades y recursos. ¿Quién se encargaría de [Tarea A], [Tarea B]...?\'\",\"result\":3,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":9,\"Trabajo en Equipo\":9,\"Toma de Decisiones\":9}},{\"text\":\"Exigir un esfuerzo extra sin mucha explicación: \'¡Equipo, el cliente nos dio 5 días más, pero debemos ser impecables! Necesito que todos redoblen esfuerzos. El que no esté comprometido, que lo diga ahora.\'\",\"result\":3,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":4,\"Liderazgo\":5,\"Trabajo en Equipo\":5,\"Toma de Decisiones\":7}},{\"text\":\"Buscar ayuda externa o recursos adicionales: \'Este esfuerzo es grande. Voy a hablar con gerencia para ver si podemos traer un desarrollador externo temporalmente o reasignar a alguien de otro proyecto para aligerar la carga.\'\",\"result\":3,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":7,\"Liderazgo\":8,\"Trabajo en Equipo\":8,\"Toma de Decisiones\":8}}]},{\"id\":3,\"text\":\"Tu equipo, motivado por tu liderazgo y plan claro, logra implementar la corrección a tiempo. Sin embargo, en la fase final de pruebas intensivas, el tester Ana reporta una pequeña inconsistencia en el formato de un reporte generado por la nueva lógica. No es un error crítico, pero es una desviación del estándar acordado para todos los reportes.\",\"options\":[{\"text\":\"Priorizar el plazo y documentar la inconsistencia: \'Gracias Ana por detectarlo. Es un detalle menor. Para no comprometer la entrega, documentémoslo como un \'issue\' a resolver en la próxima iteración. Asegúrate de que no afecte la funcionalidad principal.\'\",\"result\":4,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":7,\"Liderazgo\":7,\"Trabajo en Equipo\":7,\"Toma de Decisiones\":8}},{\"text\":\"Corregirlo inmediatamente, aunque implique un retraso mínimo: \'¡Excelente captura, Ana! Aunque sea menor, queremos la máxima calidad. Calculemos cuánto tiempo tomaría corregirlo y si podemos absorberlo sin afectar la entrega, o si el retraso sería mínimo y justificable.\'\",\"result\":4,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":8,\"Liderazgo\":8,\"Trabajo en Equipo\":9,\"Toma de Decisiones\":9}},{\"text\":\"Preguntar directamente al cliente sobre la inconsistencia: \'Sr. López, hemos encontrado una pequeña inconsistencia en un reporte. Es mínima y no afecta la funcionalidad, pero preferimos ser transparentes. ¿Desean que lo corrijamos ahora o en una futura actualización?\'\",\"result\":4,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":7,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":7}}]},{\"id\":4,\"text\":\"Gracias a la decisión de corregir el pequeño detalle, el equipo hizo un esfuerzo final coordinado. El sistema se desplegó en la nueva fecha acordada con una calidad impecable, superando las expectativas del cliente. El Sr. López expresó su satisfacción por la profesionalidad y el compromiso demostrado.\",\"feedbackText\":\"Has demostrado una excelente combinación de habilidades técnicas, liderazgo, comunicación y toma de decisiones bajo presión. Tu capacidad para asumir la responsabilidad, proponer soluciones concretas, motivar a tu equipo y mantener un alto estándar de calidad fue clave para transformar una crisis en una oportunidad de fortalecer la relación con el cliente. La gestión efectiva de los requisitos, la calidad del software y la comunicación asertiva son pilares fundamentales para un tecnólogo exitoso en el sector TI.\",\"options\":[]}]', 1, 1, NULL, 15, 1, '2026-01-30 14:02:36', '2026-01-30 14:02:36'),
(11, 10, 'El Desafío del Lanzamiento Crítico: Un Error Inesperado', 'Tu equipo de desarrollo se enfrenta a un error crítico a pocos días del lanzamiento de un sistema de gestión para un cliente exigente. Deberás liderar o contribuir a la resolución del problema bajo alta presión, poniendo a prueba tu capacidad de trabajo en equipo y toma de decisiones.', 'Desarrollo de Software, Gestión de Proyectos', 'intermedio', '[{\"id\":0,\"text\":\"Son las 3:00 PM y faltan 3 días para el lanzamiento de la nueva versión de un sistema de gestión. Un tester acaba de reportar un bug crítico que impide la emisión de facturas. El cliente es exigente y ya está preocupado por los plazos. ¿Cuál es tu primera acción?\",\"options\":[{\"text\":\"Avisar al líder del proyecto de inmediato y reunir al equipo para una revisión urgente del problema.\",\"result\":1,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":8,\"Trabajo en Equipo\":9,\"Toma de Decisiones\":9}},{\"text\":\"Intentar replicar y solucionar el bug por tu cuenta durante las próximas horas para no generar alarma.\",\"result\":1,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":3,\"Liderazgo\":4,\"Trabajo en Equipo\":2,\"Toma de Decisiones\":5}},{\"text\":\"Enviar un correo electrónico a todo el equipo y al cliente informando del problema y pidiendo soluciones.\",\"result\":1,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":6,\"Liderazgo\":5,\"Trabajo en Equipo\":4,\"Toma de Decisiones\":5}}]},{\"id\":1,\"text\":\"Has informado al líder y se ha convocado al equipo (desarrolladores, QA). Después de una rápida demostración, queda claro que el bug es complejo. El líder pregunta: \'¿Cuál es el plan inmediato para abordar esto sin comprometer el lanzamiento?\'\",\"options\":[{\"text\":\"Proponer una sesión de \'brainstorming\' para identificar la causa raíz y posibles soluciones, asignando roles para investigar áreas específicas.\",\"result\":2,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":9,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":9}},{\"text\":\"Dividir el equipo en dos: uno busca la solución y el otro prepara un \'rollback plan\' por si acaso, sin mucha coordinación inicial.\",\"result\":2,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":6,\"Liderazgo\":7,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":7}},{\"text\":\"Pedir a cada desarrollador que revise su código más reciente de forma individual, con la esperanza de encontrar el error rápidamente.\",\"result\":2,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":4,\"Liderazgo\":3,\"Trabajo en Equipo\":3,\"Toma de Decisiones\":4}}]},{\"id\":2,\"text\":\"Durante la sesión de \'brainstorming\', se identifican dos posibles causas raíz: un cambio reciente en la base de datos o una actualización de una librería de terceros. El tiempo es crucial. ¿Cómo enfocas la búsqueda de la solución?\",\"options\":[{\"text\":\"Asignar a los desarrolladores más experimentados a investigar ambas causas en paralelo, mientras los demás preparan pruebas unitarias y de integración.\",\"result\":3,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":9,\"Liderazgo\":10,\"Trabajo en Equipo\":10,\"Toma de Decisiones\":9}},{\"text\":\"Concentrar a todo el equipo en la causa más probable (cambio en la DB) para encontrar una solución rápida.\",\"result\":3,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":7,\"Liderazgo\":7,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":6}},{\"text\":\"Pedir que cada uno trabaje individualmente en la causa que considere más fácil de resolver, sin coordinación específica.\",\"result\":3,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":3,\"Liderazgo\":2,\"Trabajo en Equipo\":1,\"Toma de Decisiones\":2}}]},{\"id\":3,\"text\":\"El equipo ha trabajado en paralelo y, después de varias horas, han identificado la causa exacta y desarrollado un parche. Sin embargo, aplicar el parche requiere una ventana de mantenimiento breve en el servidor de pre-producción. El lanzamiento es mañana. ¿Cómo gestionas los próximos pasos?\",\"options\":[{\"text\":\"Comunicar al líder del proyecto que la solución está lista, proponer la ventana de mantenimiento urgente y coordinar las pruebas de regresión inmediatamente después de la aplicación.\",\"result\":4,\"feedback\":\"good\",\"scores\":{\"Comunicacion\":10,\"Liderazgo\":9,\"Trabajo en Equipo\":9,\"Toma de Decisiones\":10}},{\"text\":\"Aplicar el parche en pre-producción sin esperar aprobación formal para ganar tiempo, e informar solo después de que esté implementado y probado.\",\"result\":4,\"feedback\":\"bad\",\"scores\":{\"Comunicacion\":3,\"Liderazgo\":4,\"Trabajo en Equipo\":5,\"Toma de Decisiones\":3}},{\"text\":\"Pedir al equipo de QA que realice pruebas exhaustivas del parche antes de aplicarlo, lo cual podría retrasar el despliegue final.\",\"result\":4,\"feedback\":\"neutral\",\"scores\":{\"Comunicacion\":6,\"Liderazgo\":5,\"Trabajo en Equipo\":6,\"Toma de Decisiones\":7}}]},{\"id\":4,\"text\":\"Cierre\",\"feedbackText\":\"¡Felicitaciones! Tu gestión de la crisis ha sido ejemplar. Al priorizar la comunicación efectiva con el líder y el equipo, fomentar el trabajo colaborativo en la identificación y solución del problema, demostrar liderazgo al asignar tareas estratégicamente y tomar decisiones ágiles y fundamentadas, lograron resolver el bug crítico a tiempo. Este enfoque no solo salvó el lanzamiento, sino que también reforzó la confianza del cliente y la cohesión del equipo. ¡Un verdadero ejemplo de Tecnólogo en Análisis y Desarrollo de Software!\",\"options\":[]}]', 1, 1, NULL, 15, 1, '2026-01-30 19:21:13', '2026-01-30 19:21:13');

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
  `is_dynamic` tinyint(1) DEFAULT 0 COMMENT 'Indica si es una sesi├│n con generaci├│n din├ímica',
  `status` enum('pending','in_progress','completed','abandoned') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `scenario_id`, `started_at`, `completed_at`, `scores_json`, `final_score`, `decisions_count`, `completion_percentage`, `program_id`, `context_json`, `current_stage`, `stage1_json`, `stage2_json`, `stage3_json`, `is_dynamic`, `status`) VALUES
(3, 2, 10, '2026-01-30 14:02:52', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(4, 2, 10, '2026-01-30 14:02:58', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(5, 2, 10, '2026-01-30 14:03:08', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(6, 2, 10, '2026-01-30 14:03:42', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(7, 2, 10, '2026-01-30 14:03:51', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(8, 2, 10, '2026-01-30 14:04:33', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(9, 2, 10, '2026-01-30 14:12:59', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending'),
(10, 2, 10, '2026-01-30 14:15:01', NULL, '{}', 0, 0, 0.00, NULL, NULL, 0, NULL, NULL, NULL, 0, 'pending');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `system_settings`
--

INSERT INTO `system_settings` (`setting_key`, `setting_value`, `updated_at`) VALUES
('app_name', 'RolPlay EDU', '2026-01-30 19:16:01'),
('gamification_achievements_enabled', '1', '2026-01-30 19:16:01'),
('gamification_notifications_enabled', '1', '2026-01-30 19:16:01'),
('gamification_ranking_enabled', '1', '2026-01-30 19:16:01'),
('maintenance_mode', '0', '2026-01-30 19:16:01'),
('puter_auto_login', '1', '2026-01-30 19:16:01'),
('puter_enabled', '1', '2026-01-30 19:16:01'),
('puter_login_hint', 'migdonio@gmail.com', '2026-01-30 19:16:01'),
('puter_password', 'Milo1410*', '2026-01-30 19:16:01'),
('puter_prompt_mode', 'login', '2026-01-30 19:16:01');

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
(2, 'Carlos Rodriguez', 'admin@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(3, 'Maria Gonzalez', 'admin2@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'admin', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(4, 'Juan Perez', 'instructor@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(5, 'Ana Martinez', 'instructor2@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(6, 'Luis Sanchez', 'instructor3@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'instructor', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(7, 'Pedro Garcia', 'aprendiz1@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(8, 'Laura Torres', 'aprendiz2@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(9, 'Diego Ramirez', 'aprendiz3@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(10, 'Camila Lopez', 'aprendiz4@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(11, 'Andres Herrera', 'aprendiz5@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(12, 'Valentina Diaz', 'aprendiz6@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(13, 'Sebastian Morales', 'aprendiz7@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(14, 'Isabella Castro', 'aprendiz8@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(15, 'Miguel Angel Vargas', 'aprendiz9@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11'),
(16, 'Sofia Jimenez', 'aprendiz10@sena.edu.co', '$2y$10$KCePmyGbwSUJgrZGnrawAOqaEY8DnsZU/aIVAKEcWq37yuC26wT6.', 'aprendiz', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, '2026-01-29 12:52:11', '2026-01-29 12:52:11');

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
  `achievements_unlocked` int(11) DEFAULT 0 COMMENT 'Total de logros desbloqueados',
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

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `user_performance`  AS SELECT `u`.`id` AS `user_id`, `u`.`name` AS `name`, `u`.`email` AS `email`, `u`.`ficha` AS `ficha`, count(distinct `s`.`id`) AS `total_sessions`, count(distinct case when `s`.`completed_at` is not null then `s`.`id` end) AS `completed_sessions`, round(avg(`s`.`final_score`),2) AS `average_score`, max(`s`.`final_score`) AS `best_score`, count(distinct `ua`.`achievement_id`) AS `achievements_count`, coalesce(`us`.`total_points`,0) AS `total_points` FROM (((`users` `u` left join `sessions` `s` on(`u`.`id` = `s`.`user_id`)) left join `user_achievements` `ua` on(`u`.`id` = `ua`.`user_id`)) left join `user_stats` `us` on(`u`.`id` = `us`.`user_id`)) WHERE `u`.`role` = 'aprendiz' GROUP BY `u`.`id`, `u`.`name`, `u`.`email`, `u`.`ficha`, `us`.`total_points` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_achievements_active` (`is_active`);

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
-- Indices de la tabla `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `decisions`
--
ALTER TABLE `decisions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `programs`
--
ALTER TABLE `programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `program_soft_skills`
--
ALTER TABLE `program_soft_skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de la tabla `routes`
--
ALTER TABLE `routes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `scenarios`
--
ALTER TABLE `scenarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
