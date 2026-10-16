-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-10-2026 a las 20:07:58
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `moscafix`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `rol` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `rol`) VALUES
(0, 'admin'),
(1, 'user');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_usuarios`
--

CREATE TABLE `roles_usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `roles_usuarios`
--

INSERT INTO `roles_usuarios` (`id_usuario`, `id_rol`) VALUES
(1, 0),
(2, 1),
(3, 1),
(27, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tableros`
--

CREATE TABLE `tableros` (
  `id` int(11) NOT NULL,
  `casillas` varchar(30) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `intentos` int(11) NOT NULL,
  `intentos_iniciales` int(11) NOT NULL,
  `estado` varchar(30) NOT NULL,
  `posicion_mosca` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `tableros`
--

INSERT INTO `tableros` (`id`, `casillas`, `fecha_creacion`, `intentos`, `intentos_iniciales`, `estado`, `posicion_mosca`, `id_usuario`) VALUES
(22, '0,0,1,0,0', '2026-10-15 23:13:20', 5, 5, 'rendido', 2, 2),
(23, '0,0,0,1,0', '2026-10-16 03:28:59', 5, 5, 'ganado', 3, 2),
(25, '0,0,0,0,1', '2026-10-16 04:52:39', 3, 5, 'rendido', 4, 2),
(27, '0,0,0,0,1', '2026-10-16 18:36:45', 2, 5, 'ganado', 4, 2),
(28, '0,1,0,0,0,0,0,0,0,0', '2026-10-16 18:36:52', 5, 5, 'rendido', 1, 3),
(29, '0,0,1,0,0', '2026-10-16 18:44:33', 5, 5, 'rendido', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `edad` int(11) NOT NULL,
  `email` varchar(30) NOT NULL,
  `pass` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `edad`, `email`, `pass`) VALUES
(1, 'pepeadmin', 12, 'pepetijeras@gmail.com', 'c893bad68927b457dbed39460e6afd62'),
(2, 'pepeplebe', 12, 'pontucorreo@gmail.com', 'fabdd981ee0353b49d50efd02e633af4'),
(3, 'pepe2', 12, 'pepetijeras23@gmail.com', 'c893bad68927b457dbed39460e6afd62'),
(27, 'pepebutaca', 58, 'butanerosexys@gmail.com', 'c893bad68927b457dbed39460e6afd62');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD PRIMARY KEY (`id_usuario`,`id_rol`),
  ADD KEY `IDROL2` (`id_rol`);

--
-- Indices de la tabla `tableros`
--
ALTER TABLE `tableros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tableros_usuarios` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tableros`
--
ALTER TABLE `tableros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
  ADD CONSTRAINT `IDROL2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `IDUSER2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tableros`
--
ALTER TABLE `tableros`
  ADD CONSTRAINT `fk_tableros_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
