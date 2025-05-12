-- phpMyAdmin SQL Dump
-- version 4.9.11
-- https://www.phpmyadmin.net/
--
-- Servidor: db5016800281.hosting-data.io
-- Tiempo de generación: 21-03-2025 a las 14:40:39
-- Versión del servidor: 10.11.7-MariaDB-log
-- Versión de PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dbs13578445`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `descripcion` text NOT NULL,
  `horas_programadas` decimal(5,2) DEFAULT NULL,
  `fecha` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornadas`
--

CREATE TABLE `jornadas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp(),
  `pausa_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pausas`
--

CREATE TABLE `pausas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `pausas`
--

INSERT INTO `pausas` (`id`, `nombre`) VALUES
(1, 'Desayuno'),
(2, 'Comida'),
(3, 'Merienda'),
(4, 'Café'),
(5, 'Descanso'),
(6, 'Sin Pausas'),
(7, 'Break Técnico'),
(8, 'Break Técnico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productividad`
--

CREATE TABLE `productividad` (
  `id` int(11) NOT NULL,
  `jornada_id` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `horas_trabajadas` decimal(5,2) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idPrimaria` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `avatar` varchar(255) DEFAULT NULL,
  `nota` text DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','jefe','usuario') DEFAULT 'usuario',
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idPrimaria`, `nombre`, `apellido`, `dni`, `direccion`, `telefono`, `estado`, `avatar`, `nota`, `email`, `password`, `rol`, `fecha_creacion`) VALUES
(46, 'dario', 'pruebas', '', NULL, NULL, 'activo', NULL, NULL, 'x@gmail.com', '$2y$10$aWFJ.R1LpKW0xhJRRJC.EuWvNLLt3U.hBET0s2PZD/QQm..XcxSgC', 'admin', '2024-12-16 09:44:34'),
(50, 'Maria Luisa', 'Moreno Velasco', '44669340m', 'Calle Clara CampoAmor 3 1 B', '619952178', 'activo', '', '', 'xxxxxx@gmail.com', '$2y$10$/3pmFpkWY9X2vwRIUVi9wexTP86u4L/.5YPwflBWw/qLaXJQcos4W', 'usuario', '2024-12-16 10:06:34'),
(51, 'Dario', 'Roma', 'x3620204g', 'colibri\r\n3', '657197344', 'activo', '', '', 'hdr@gmail.com', '$2y$10$DulWEBCWGTghSqu9khbv0O69X26absFXKN14UuvXs6uotGNcm6AXu', 'admin', '2024-12-16 10:38:50');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `jornadas`
--
ALTER TABLE `jornadas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_pausa` (`pausa_id`);

--
-- Indices de la tabla `pausas`
--
ALTER TABLE `pausas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productividad`
--
ALTER TABLE `productividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jornada_id` (`jornada_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idPrimaria`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `jornadas`
--
ALTER TABLE `jornadas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `pausas`
--
ALTER TABLE `pausas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productividad`
--
ALTER TABLE `productividad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idPrimaria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `actividades_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idPrimaria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `jornadas`
--
ALTER TABLE `jornadas`
  ADD CONSTRAINT `fk_pausa` FOREIGN KEY (`pausa_id`) REFERENCES `pausas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jornadas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`idPrimaria`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productividad`
--
ALTER TABLE `productividad`
  ADD CONSTRAINT `productividad_ibfk_1` FOREIGN KEY (`jornada_id`) REFERENCES `jornadas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
