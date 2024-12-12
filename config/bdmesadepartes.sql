-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2024 a las 14:48:11
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
-- Base de datos: `bdmesadepartes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areas`
--

CREATE TABLE `areas` (
  `id_area` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areas`
--

INSERT INTO `areas` (`id_area`, `nombre`, `descripcion`) VALUES
(1, 'mesa de pates', 'ds'),
(2, 'mesa de pates', 'dsfas'),
(3, 'adf', 'df'),
(4, 'af', 'af'),
(5, 'asdfasfd', 'asf'),
(6, 'Matematica', 'dsfa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `expedientes`
--

CREATE TABLE `expedientes` (
  `id_expediente` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `remitente` varchar(100) NOT NULL,
  `tipo_tramite` enum('solicitud','reclamo','otro') NOT NULL,
  `asunto` varchar(200) NOT NULL,
  `folio` int(11) DEFAULT NULL,
  `tipo_persona` varchar(20) DEFAULT NULL,
  `dni_ruc` varchar(20) NOT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `estado` enum('pendiente','en proceso','atendido','derivado') NOT NULL DEFAULT 'pendiente',
  `archivo` varchar(255) DEFAULT NULL,
  `apellido_paterno` varchar(50) DEFAULT NULL,
  `apellido_materno` varchar(50) DEFAULT NULL,
  `notas_referencias` text DEFAULT NULL,
  `codigo_seguridad` varchar(10) DEFAULT NULL,
  `tipo_documento` enum('DNI','RUC') NOT NULL,
  `numero_expediente` varchar(10) NOT NULL,
  `id_area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `expedientes`
--

INSERT INTO `expedientes` (`id_expediente`, `fecha_hora`, `remitente`, `tipo_tramite`, `asunto`, `folio`, `tipo_persona`, `dni_ruc`, `correo`, `telefono`, `direccion`, `estado`, `archivo`, `apellido_paterno`, `apellido_materno`, `notas_referencias`, `codigo_seguridad`, `tipo_documento`, `numero_expediente`, `id_area`) VALUES
(1, '2024-12-12 04:46:40', 'Matematica', '', 'asdf', 12, 'Persona Natural', '64565489', 'mullisaca@gmail.com', '4525', 'Jr. Lambayeque N° 731', 'pendiente', '1733978800_carrito-de-compras (4).png', 'asdf', 'asdf', 'dsaf', '2F2321', '', '000000001', 1),
(2, '2024-12-12 05:03:04', 'asdfasdfsa', '', 'adsfsa', 10, 'Persona Natural', '313415648', 'cahuana@gmail.com', '1233645645', 'Jr. Lambayeque N° 731', 'pendiente', '1733979784_PIAD-520_TRABAJOFINAL_INGENIERIA_SOFTWARE.pdf', 'sadfsadf', 'adsfsadfsa', 'sfdafsa', '5348D0', '', '000000002', 1),
(3, '2024-12-12 05:06:12', 'asdfsa', '', 'asdfsadf', 10, 'Persona Natural', '56486416486', 'danymullisaca@gmail.com', '4525', 'Calle Las Dunas 23, Ica, Perú', 'derivado', '1733979972_GESTION DE USUARIOS.pdf', 'asdfsaf', 'dsfsa', 'sadfsa', 'A28E0C', '', '000000003', 5),
(4, '2024-12-12 05:14:53', 'Comunicación', '', 'asfd', 12, 'Persona Natural', '56486546', 'danymullisaca@gmail.com', '4525', 'Calle Las Dunas 23, Ica, Perú', 'derivado', '1733980493_GESTION DE USUARIOS.pdf', 'asdfasd', 'asdf', 'adsfsaf', '32B134', '', '000000004', 2),
(5, '2024-12-12 05:14:53', 'Comunicación', '', 'asfd', 12, 'Persona Natural', '56486546', 'danymullisaca@gmail.com', '4525', 'Calle Las Dunas 23, Ica, Perú', 'derivado', '1733980493_GESTION DE USUARIOS.pdf', 'asdfasd', 'asdf', 'adsfsaf', '50E8D4', '', '000000005', 4),
(6, '2024-12-12 05:15:33', 'asdfsafs', '', 'adsfsa', 12, 'Persona Natural', '464984', 'mullisaca@gmail.com', '4525', 'Jr. Del Sur 876, Trujillo, Perú', 'derivado', '1733980533_GESTION DE USUARIOS.pdf', 'asdfasfsadf', 'fdsafsadf', 'asdfsaf', '92E80F', '', '000000006', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_expedientes`
--

CREATE TABLE `historial_expedientes` (
  `id_historial` int(11) NOT NULL,
  `id_expediente` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `estado_documento` varchar(50) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_expedientes`
--

INSERT INTO `historial_expedientes` (`id_historial`, `id_expediente`, `id_usuario`, `estado_documento`, `descripcion`, `fecha_hora`) VALUES
(1, 6, 1, 'derivado', 'Expediente derivado', '2024-12-12 06:13:15'),
(2, 3, 1, 'derivado', 'Expediente derivado', '2024-12-12 06:17:13'),
(3, 4, 1, 'derivado', 'Expediente derivado a Matematica', '2024-12-12 06:25:43'),
(4, 4, 1, 'derivado', 'Expediente derivado a mesa de pates', '2024-12-12 06:26:17');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `leido` tinyint(1) DEFAULT 0,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `seguimiento`
--

CREATE TABLE `seguimiento` (
  `id_seguimiento` int(11) NOT NULL,
  `id_expediente` int(11) NOT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `descripcion` text DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_area` int(11) NOT NULL,
  `adjunto` varchar(255) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `respuesta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','empleado') NOT NULL,
  `id_area` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `nombre_usuario`, `correo`, `contraseña`, `rol`, `id_area`) VALUES
(1, 'Dany Mullisaca Cahuana', 'Admin', 'mullisaca@gmail.com', '$2y$10$SImrY8Ga75SrJu4CCw/sleQBvx1VhnnH5nfQuTw8BDrFTwjKfnVHS', 'admin', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id_area`);

--
-- Indices de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD PRIMARY KEY (`id_expediente`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `historial_expedientes`
--
ALTER TABLE `historial_expedientes`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_expediente` (`id_expediente`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD PRIMARY KEY (`id_seguimiento`),
  ADD KEY `id_expediente` (`id_expediente`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_area` (`id_area`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  ADD UNIQUE KEY `correo` (`correo`),
  ADD KEY `id_area` (`id_area`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `areas`
--
ALTER TABLE `areas`
  MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `expedientes`
--
ALTER TABLE `expedientes`
  MODIFY `id_expediente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `historial_expedientes`
--
ALTER TABLE `historial_expedientes`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  MODIFY `id_seguimiento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `expedientes`
--
ALTER TABLE `expedientes`
  ADD CONSTRAINT `expedientes_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `historial_expedientes`
--
ALTER TABLE `historial_expedientes`
  ADD CONSTRAINT `historial_expedientes_ibfk_1` FOREIGN KEY (`id_expediente`) REFERENCES `expedientes` (`id_expediente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `historial_expedientes_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `seguimiento`
--
ALTER TABLE `seguimiento`
  ADD CONSTRAINT `seguimiento_ibfk_1` FOREIGN KEY (`id_expediente`) REFERENCES `expedientes` (`id_expediente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seguimiento_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `seguimiento_ibfk_3` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`id_area`) REFERENCES `areas` (`id_area`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
