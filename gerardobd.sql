-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         10.4.32-MariaDB - mariadb.org binary distribution
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.7.0.6850
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para gerardo_db
CREATE DATABASE IF NOT EXISTS `gerardo_db` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci */;
USE `gerardo_db`;

-- Volcando estructura para tabla gerardo_db.accesos
CREATE TABLE IF NOT EXISTS `accesos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `materia_id` int(11) DEFAULT NULL,
  `juego_id` int(11) DEFAULT NULL,
  `proyecto_id` int(11) DEFAULT NULL,
  `permiso_materias` set('crear','leer','actualizar','eliminar') DEFAULT 'leer',
  `permiso_juegos` set('crear','leer','actualizar','eliminar') DEFAULT 'leer',
  `permiso_proyectos` set('crear','leer','actualizar','eliminar') DEFAULT 'leer',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `materia_id` (`materia_id`),
  KEY `juego_id` (`juego_id`),
  KEY `proyecto_id` (`proyecto_id`),
  CONSTRAINT `accesos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`),
  CONSTRAINT `accesos_ibfk_2` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`materia_id`),
  CONSTRAINT `accesos_ibfk_3` FOREIGN KEY (`juego_id`) REFERENCES `juegos` (`juego_id`),
  CONSTRAINT `accesos_ibfk_4` FOREIGN KEY (`proyecto_id`) REFERENCES `proyectos` (`proyecto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla gerardo_db.accesos: ~40 rows (aproximadamente)
INSERT INTO `accesos` (`id`, `usuario_id`, `materia_id`, `juego_id`, `proyecto_id`, `permiso_materias`, `permiso_juegos`, `permiso_proyectos`) VALUES
	(1, 1, 1, 2, 1, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(2, 2, 2, 3, 2, '', '', ''),
	(3, 3, 3, 1, 3, 'leer', 'leer', 'leer'),
	(4, 4, 4, 4, 4, 'leer', 'leer', 'leer'),
	(6, 1, 21, 21, 21, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(7, 1, 22, 22, 22, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(8, 1, 23, 23, 23, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(9, 1, 24, 24, 24, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(10, 1, 25, 25, 25, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(11, 1, 26, 26, 26, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(12, 1, 27, 27, 27, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(13, 1, 28, 28, 28, 'crear,leer,actualizar,eliminar', 'leer,actualizar', 'crear,leer'),
	(15, 2, 30, 30, 30, '', '', ''),
	(16, 2, 31, 31, 31, '', '', ''),
	(17, 2, 32, 32, 32, '', '', ''),
	(18, 2, 33, 33, 33, '', '', ''),
	(19, 2, 34, 34, 34, '', '', ''),
	(20, 2, 35, 35, 35, '', '', ''),
	(21, 2, 36, 36, 36, '', '', ''),
	(22, 2, 37, 37, 37, '', '', ''),
	(23, 2, 38, 38, 38, '', '', ''),
	(24, 2, 39, 39, 39, '', '', ''),
	(26, 3, 21, 23, 25, 'leer', 'leer', 'leer'),
	(27, 3, 22, 24, 26, 'leer', 'leer', 'leer'),
	(28, 3, 23, 25, 27, 'leer', 'leer', 'leer'),
	(29, 3, 24, 26, 28, 'leer', 'leer', 'leer'),
	(30, 3, 25, 27, 29, 'leer', 'leer', 'leer'),
	(31, 3, 26, 28, 30, 'leer', 'leer', 'leer'),
	(32, 3, 27, 29, 31, 'leer', 'leer', 'leer'),
	(33, 3, 28, 30, 32, 'leer', 'leer', 'leer'),
	(35, 4, 30, 32, 34, 'leer', 'leer', 'leer'),
	(36, 4, 31, 33, 35, 'leer', 'leer', 'leer'),
	(37, 4, 32, 34, 36, 'leer', 'leer', 'leer'),
	(38, 4, 33, 35, 37, 'leer', 'leer', 'leer'),
	(39, 4, 34, 36, 38, 'leer', 'leer', 'leer'),
	(40, 4, 35, 37, 39, 'leer', 'leer', 'leer'),
	(41, 4, 36, 38, 20, 'leer', 'leer', 'leer'),
	(42, 4, 37, 39, 21, 'leer', 'leer', 'leer'),
	(43, 4, 38, 20, 22, 'leer', 'leer', 'leer'),
	(44, 4, 39, 21, 23, 'leer', 'leer', 'leer');

-- Volcando estructura para tabla gerardo_db.juegos
CREATE TABLE IF NOT EXISTS `juegos` (
  `juego_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  PRIMARY KEY (`juego_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gerardo_db.juegos: ~24 rows (aproximadamente)
INSERT INTO `juegos` (`juego_id`, `nombre`, `descripcion`) VALUES
	(1, 'Juego de Adivinanza', 'Un juego divertido donde tendrás que adivinar palabras a partir de pistas y acertijos.'),
	(2, 'Simulador de Experimentos', 'Este juego te permite crear experimentos científicos de manera virtual en todo el mundo.'),
	(3, 'Trivia de Historias', 'Pon a prueba tus conocimientos sobre la historia mundial con preguntas y respuestas.'),
	(4, 'Juego de Trivia Científica', 'Un juego desafiante donde tendrás que responder preguntas sobre temas científicos.'),
	(20, 'Escape Room Virtual', 'Juego de lógica para resolver acertijos en un tiempo límite.'),
	(21, 'Simulador de Negocios', 'Manejo de empresas en un entorno virtual.'),
	(22, 'Trivia de Ciencia', 'Preguntas y respuestas sobre temas científicos.'),
	(23, 'Aventura Matemática', 'Explora un mundo basado en problemas matemáticos.'),
	(24, 'Rompecabezas de Historia', 'Completa puzzles con eventos históricos famosos.'),
	(25, 'Juego de Estrategia', 'Planifica y ejecuta tácticas para ganar.'),
	(26, 'Simulador de Carrera Espacial', 'Gestiona una agencia espacial y lanza cohetes.'),
	(27, 'Juego de Memoria', 'Desarrolla habilidades de retención con desafíos visuales.'),
	(28, 'Trivia de Arte', 'Pon a prueba tus conocimientos sobre pintura y escultura.'),
	(29, 'Simulador de Cirugía', 'Realiza operaciones médicas en un ambiente virtual.'),
	(30, 'Mundo de Programación', 'Aprende lógica de programación jugando.'),
	(31, 'Ajedrez Virtual', 'Compite contra la IA en partidas de ajedrez.'),
	(32, 'Construcción de Ciudades', 'Diseña y administra tu propia metrópolis.'),
	(33, 'Carrera de Coches Físicos', 'Simulación de velocidad con principios físicos reales.'),
	(34, 'Juego de Química', 'Resuelve ecuaciones químicas en un laboratorio virtual.'),
	(35, 'Exploración Espacial', 'Viaja a planetas y descubre nuevos mundos.'),
	(36, 'Juego de Negociación', 'Prueba tus habilidades de persuasión en un mercado.'),
	(37, 'Resuelve el Misterio', 'Encuentra pistas y resuelve crímenes históricos.'),
	(38, 'Retos de Física', 'Supera pruebas basadas en principios físicos.'),
	(39, 'Juego de Biología', 'Explora el cuerpo humano y sus funciones.');

-- Volcando estructura para tabla gerardo_db.materias
CREATE TABLE IF NOT EXISTS `materias` (
  `materia_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` mediumtext DEFAULT NULL,
  PRIMARY KEY (`materia_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gerardo_db.materias: ~33 rows (aproximadamente)
INSERT INTO `materias` (`materia_id`, `nombre`, `descripcion`) VALUES
	(1, 'Matemáticas', 'Las matemáticas son el estudio de los números, estructuras, espacio y cambio.'),
	(2, 'Ciencias', 'Las ciencias se centran en entender el mundo natural a través de la observación y experimentación.'),
	(3, 'Historia de mexico', 'La historia es el estudio de eventos pasados, sus causas y consecuencias.'),
	(4, 'Física', 'La física estudia las leyes fundamentales del universo.'),
	(21, 'Geometría Euclidiana', 'Estudio de figuras geométricas en dos y tres dimensiones.'),
	(22, 'Cálculo Diferencial', 'Análisis de cambios en funciones matemáticas.'),
	(23, 'Física Cuántica', 'Exploración de los principios de la mecánica cuántica.'),
	(24, 'Biología Molecular', 'Estudio de la estructura y función de las moléculas biológicas.'),
	(25, 'Química Orgánica', 'Análisis de compuestos que contienen carbono.'),
	(26, 'Historia del Arte', 'Evolución del arte a través de los siglos.'),
	(27, 'Astronomía Observacional', 'Métodos y técnicas para estudiar el universo.'),
	(28, 'Psicología Cognitiva', 'Procesos mentales como la memoria y el aprendizaje.'),
	(30, 'Probabilidad y Estadística', 'Cálculo de probabilidades y análisis de datos.'),
	(31, 'Filosofía Moderna', 'Estudio del pensamiento filosófico contemporáneo.'),
	(32, 'Ingeniería de Software', 'Desarrollo de sistemas informáticos eficientes.'),
	(33, 'Inteligencia Artificial', 'Creación y aplicación de modelos de IA.'),
	(34, 'Sociología Urbana', 'Análisis de la vida en ciudades y sociedades modernas.'),
	(35, 'Ética Profesional', 'Normas y principios en la práctica profesional.'),
	(36, 'Geopolítica Contemporánea', 'Estrategias y conflictos a nivel mundial.'),
	(37, 'Nanotecnología Aplicada', 'Uso de materiales y dispositivos a escala nanométrica.'),
	(38, 'Neurociencia', 'Estudio del sistema nervioso y el cerebro.'),
	(39, 'Metodología de la Investigación', 'Técnicas y herramientas para la investigación científica.'),
	(40, 'Gerardo', NULL),
	(41, 'Tabo', NULL),
	(42, 'Gerardo', NULL),
	(43, 'Tabo', NULL),
	(44, 'Gerardo', NULL),
	(45, 'Tabo', 'xdddddddddd'),
	(46, 'Tabo', 'b'),
	(47, 'Tabo', NULL),
	(48, 'Tabo', 'xdddd'),
	(49, 'Wilber', 'xddddddddddddddddddddddddddddddddddddddddddddddddddddddddd'),
	(50, 'Wilber', 'ok');

-- Volcando estructura para tabla gerardo_db.preguntas_secreta
CREATE TABLE IF NOT EXISTS `preguntas_secreta` (
  `pregunta_id` int(11) NOT NULL AUTO_INCREMENT,
  `pregunta` varchar(255) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`pregunta_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla gerardo_db.preguntas_secreta: ~8 rows (aproximadamente)
INSERT INTO `preguntas_secreta` (`pregunta_id`, `pregunta`, `fecha_creacion`) VALUES
	(1, '¿Cuál es el nombre de tu primera mascota?', '2025-02-20 09:40:22'),
	(2, '¿En qué ciudad naciste?', '2025-02-20 09:40:22'),
	(3, '¿Cuál es el nombre de tu escuela primaria?', '2025-02-20 09:40:22'),
	(4, '¿Cuál es el segundo nombre de tu madre?', '2025-02-20 09:40:22'),
	(5, '¿Cómo se llamaba tu primer mejor amigo?', '2025-02-20 09:40:22'),
	(6, '¿Cuál fue tu primer trabajo?', '2025-02-20 09:40:22'),
	(7, '¿Cuál es tu comida favorita?', '2025-02-20 09:40:22'),
	(8, '¿Cuál es el nombre de tu canción favorita?', '2025-02-20 09:40:22');

-- Volcando estructura para tabla gerardo_db.proyectos
CREATE TABLE IF NOT EXISTS `proyectos` (
  `proyecto_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` mediumtext DEFAULT NULL,
  PRIMARY KEY (`proyecto_id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla gerardo_db.proyectos: ~26 rows (aproximadamente)
INSERT INTO `proyectos` (`proyecto_id`, `nombre`, `descripcion`) VALUES
	(1, 'Proyecto de Investigación', 'Desarrolla habilidades de investigación a través de proyectos educativos.'),
	(2, 'Proyecto de Biología', 'Aprende sobre la vida y los seres vivos a través de proyectos interactivos.'),
	(3, 'Proyecto de Línea de Tiempo hoy', 'Crea y visualiza eventos históricos en una línea de tiempo interactiva en todo el mundo.'),
	(4, 'Proyecto de Astronomía', 'Explora el universo y aprende sobre las estrellas y galaxias.'),
	(20, 'Desarrollo de Aplicaciones', 'Crea tu primera aplicación web con tecnologías modernas.'),
	(21, 'Investigación en Cambio Climático', 'Analiza datos sobre el impacto ambiental.'),
	(22, 'Proyecto de Robótica', 'Diseña y programa un robot desde cero.'),
	(23, 'Crecimiento de Plantas', 'Estudio de factores que afectan el crecimiento vegetal.'),
	(24, 'Análisis de Datos Deportivos', 'Explora estadísticas en el mundo del deporte.'),
	(25, 'Reconocimiento Facial', 'Desarrollo de software basado en inteligencia artificial.'),
	(26, 'Realidad Aumentada', 'Crea experiencias interactivas con RA.'),
	(27, 'Economía Circular', 'Proyectos sostenibles y de reciclaje.'),
	(28, 'Historia Interactiva', 'Crea una narración digital sobre eventos históricos.'),
	(29, 'Desarrollo de Videojuegos', 'Programación y diseño de juegos.'),
	(30, 'Proyecto de Biotecnología', 'Aplicaciones innovadoras en genética.'),
	(31, 'Astronomía Digital', 'Software de simulación astronómica.'),
	(32, 'Diseño de Materiales', 'Innovaciones en nuevos materiales industriales.'),
	(33, 'Sistema de Energía Solar', 'Desarrollo de paneles solares eficientes.'),
	(34, 'Simulación de Redes', 'Estudio del comportamiento de redes informáticas.'),
	(35, 'Proyecto de Marketing Digital', 'Estrategias para la promoción de productos en línea.'),
	(36, 'Educación Virtual', 'Plataformas y herramientas de aprendizaje digital.'),
	(37, 'Avances en Medicina', 'Investigaciones sobre tratamientos innovadores.'),
	(38, 'Control de Contaminación', 'Estrategias para reducir la huella ambiental.'),
	(39, 'Automatización Industrial', 'Sistemas de control y robótica en fábricas.'),
	(40, 'Tabo', NULL),
	(41, 'Tabo', NULL);

-- Volcando estructura para tabla gerardo_db.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `usuario_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `celular` varchar(15) NOT NULL,
  `pregunta1` int(11) DEFAULT NULL,
  `respuesta1` varchar(255) DEFAULT NULL,
  `pregunta2` int(11) DEFAULT NULL,
  `respuesta2` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('usuario','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `token` varchar(255) DEFAULT NULL,
  `token_expira` datetime DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `secret` varchar(255) DEFAULT NULL,
  `token_mfa` varchar(100) DEFAULT NULL,
  `token_mfa_expira` datetime DEFAULT NULL,
  `session_token` varchar(255) DEFAULT NULL,
  `sesion_id` varchar(255) DEFAULT NULL,
  `sesion_token` varchar(255) DEFAULT NULL,
  `mfa_activada` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`usuario_id`),
  KEY `pregunta1` (`pregunta1`),
  KEY `pregunta2` (`pregunta2`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`pregunta1`) REFERENCES `preguntas_secreta` (`pregunta_id`),
  CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`pregunta2`) REFERENCES `preguntas_secreta` (`pregunta_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Volcando datos para la tabla gerardo_db.usuarios: ~5 rows (aproximadamente)
INSERT INTO `usuarios` (`usuario_id`, `nombre`, `apellidos`, `email`, `celular`, `pregunta1`, `respuesta1`, `pregunta2`, `respuesta2`, `password`, `rol`, `created_at`, `token`, `token_expira`, `fecha_registro`, `secret`, `token_mfa`, `token_mfa_expira`, `session_token`, `sesion_id`, `sesion_token`, `mfa_activada`) VALUES
	(1, 'Gerardo', 'Jiménez velasco', 'gerardojimenezvelasco74@gmail.com', '9192393081', 1, 'chispo', 5, 'chayo', '$2y$10$ckMPvJaIE3hMdfFXwd40ZOOTlMuH7lVTlh5TQJ23iInOteYVmxA4.', 'usuario', '2025-03-25 22:27:47', 'c18d856aa7922d615e9001337ddef6d8a77b944934c1a99b41ce52b942e9784415ba15c52240d66b64b71e74c4a1a1d71761', '2025-03-26 00:51:42', '2025-03-25 22:27:47', NULL, NULL, NULL, NULL, 'r3qu3nkbel7em4auvqhbarv7d2', NULL, 1),
	(2, 'Noemi', 'Lopez Garcia', 'zavalanoemi100@gmail.com', '9666661663', 1, 'chispo', 5, 'chayo', '$2y$10$WQ7ms.hUOUVR7dydruuDbuTxeMdqU067jJ.BQaHMkXWktG/Z4YnNG', 'usuario', '2025-03-25 23:10:28', '21926bfb84526a00f5693adff06f92dffa7bf1baef13b09e8acbfeabaa9f57f71ffc199dd7b8ad8c2889171fbd9a6a2c41b9', '2025-03-25 23:26:49', '2025-03-25 23:10:28', NULL, NULL, NULL, NULL, 'r3qu3nkbel7em4auvqhbarv7d2', NULL, 0),
	(3, 'Cristian', 'Lopez', 'cristiantrujillogomez12@gmail.com', '9192712766', 1, 'chispo', 5, 'chayo', '$2y$10$fqCGNi3.23FqTfEHdvStv.BtVoLWJsE4EMcEmC3YQge4o887b3osG', 'usuario', '2025-03-25 23:18:55', NULL, NULL, '2025-03-25 23:18:55', NULL, NULL, NULL, NULL, NULL, NULL, 0),
	(4, 'Ana Karen', 'Perez Santiz', 'gerardojimenezvelaco20@gmail.com', '9192393081', 1, 'chispo', 5, 'chayo', '$2y$10$44wdeWXkGGJ091qYjVwkOucxiMAf4syFGTO.AbAYnruP5f.K64ByS', 'usuario', '2025-03-25 23:25:16', NULL, NULL, '2025-03-25 23:25:16', NULL, NULL, NULL, NULL, NULL, NULL, 0),
	(5, 'Wilber', 'Peñaloza Lopez', 'gerardojimenezvelaco49@gmail.com', '9191311350', 1, 'chispo', 5, 'chayo', '$2y$10$XAUZRUqNLAB1smU5escWCODFIwlyHtfpO4tneug70nj.vkgwG2WU.', 'admin', '2025-03-25 22:52:37', '6153b0feeda6476e45708ba3e9ba52248522cdc519424c15125261202219ad10d4e5f74a5c361940cd4ae6515a637cdf2e54', '2025-03-25 23:53:14', '2025-03-25 22:52:37', NULL, NULL, NULL, NULL, 'r3qu3nkbel7em4auvqhbarv7d2', NULL, 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
