-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-06-2019 a las 14:24:36
-- Versión del servidor: 10.1.40-MariaDB
-- Versión de PHP: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hoteles`
--

USE hoteles;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(10) NOT NULL,
  `descripcion` varchar(200) COLLATE utf16_bin NOT NULL,
  `img_path` varchar(50) COLLATE utf16_bin NOT NULL,
  `num_habitacion` int(11) NOT NULL,
  `hoteles_id_hotel` char(1) COLLATE utf16_bin NOT NULL,
  `tipo_alojamiento_id_alojamiento` char(1) COLLATE utf16_bin NOT NULL,
  `tipo_pensiones_id_pension` char(1) COLLATE utf16_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `descripcion`, `img_path`, `num_habitacion`, `hoteles_id_hotel`, `tipo_alojamiento_id_alojamiento`, `tipo_pensiones_id_pension`) VALUES
(1, 'habitación doble, primer piso, jacuzzi, minibar', '101Chicago.jpg', 101, '1', '1', '3'),
(2, 'habitación individual, suite, televisión', 'pathdeimagen', 101, '2', '2', '3'),
(3, 'ewtwe', 'pathdeimagen', 102, '1', '1', '4'),
(4, 'dsfsse', 'pathdeimagen', 102, '2', '2', '4'),
(5, 'faf', 'pathdeimagen', 105, '2', '1', '3'),
(6, 'sdsa', 'pathdeimagen', 106, '1', '1', '3'),
(7, 'saiaodi', 'pathdeimagen', 107, '1', '1', '3'),
(14, 'One bed, air-conditioner, jacuzzi, tv, minibar, wifi', 'single-room-1.jpg', 101, '8', '1', '3'),
(15, 'Two beds, air-conditioner, jacuzzi, tv, minibar, wifi', 'double-room-1.jpg', 102, '8', '2', '3'),
(16, 'Three beds, air-conditioner, jacuzzi, tv, minibar,wifi', 'triple-room-1.jpg', 103, '8', '3', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones_reservas`
--

CREATE TABLE `habitaciones_reservas` (
  `habitaciones_id_habitacion` int(10) NOT NULL,
  `reservas_id_reserva` int(10) NOT NULL,
  `precio_final` float(6,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `habitaciones_reservas`
--

INSERT INTO `habitaciones_reservas` (`habitaciones_id_habitacion`, `reservas_id_reserva`, `precio_final`) VALUES
(1, 1, 688.00),
(1, 16, 688.00),
(2, 1, 1005.00),
(2, 12, 1005.00),
(2, 14, 1005.00),
(2, 17, 1005.00),
(3, 1, 825.00),
(3, 16, 825.00),
(4, 1, 1185.00),
(4, 15, 1185.00),
(5, 1, 730.00),
(5, 12, 730.00),
(5, 17, 730.00),
(6, 1, 688.00),
(6, 15, 688.00),
(6, 19, 688.00),
(14, 1, 600.00),
(15, 1, 875.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoteles`
--

CREATE TABLE `hoteles` (
  `id_hotel` int(10) NOT NULL,
  `nombre` varchar(50) COLLATE utf16_bin NOT NULL,
  `ubicacion` varchar(50) COLLATE utf16_bin NOT NULL,
  `ciudad` varchar(50) COLLATE utf16_bin NOT NULL,
  `estrellas` char(1) COLLATE utf16_bin NOT NULL,
  `path_img` varchar(50) COLLATE utf16_bin NOT NULL,
  `precio_multi` float(3,1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `hoteles`
--

INSERT INTO `hoteles` (`id_hotel`, `nombre`, `ubicacion`, `ciudad`, `estrellas`, `path_img`, `precio_multi`) VALUES
(1, 'Imperial Hotel', 'Togichi 321', 'Nikko', '4', 'https://www.slh.com/globalassets/country-pages/her', 5.5),
(2, 'LuckyStrike Hotel', 'Kensington and Chelsea 60', 'London', '5', 'https://s-ec.bstatic.com/images/hotel/max1024x768/', 7.2),
(8, 'Aeroplane', 'Dulce de Vie', 'Atlanta', '5', 'aeroplaneimg.jpg', 2.1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(10) NOT NULL,
  `fecha_reserva_inicio` date NOT NULL,
  `valoraciones_id_valoracion` int(10) DEFAULT NULL,
  `fecha_reserva_fin` date NOT NULL,
  `usuarios_correo_usuario` varchar(100) COLLATE utf16_bin DEFAULT NULL,
  `precio_final_reserva` float(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `fecha_reserva_inicio`, `valoraciones_id_valoracion`, `fecha_reserva_fin`, `usuarios_correo_usuario`, `precio_final_reserva`) VALUES
(1, '2019-05-01', 7, '2019-05-07', 'anseryne@gmail.com', 6188.00),
(12, '2019-06-11', NULL, '2019-06-18', 'administradornipohtels@gmail.com', 2095.00),
(14, '2019-07-10', NULL, '2019-07-31', 'anseryne@gmail.com', 1005.00),
(15, '2019-06-12', NULL, '2019-06-19', 'anseryne@gmail.com', 550.00),
(16, '2019-06-27', NULL, '2019-07-09', 'anseryne@gmail.com', 1513.00),
(17, '2019-06-24', NULL, '2019-07-01', 'anseryne@gmail.com', 1735.00),
(18, '2019-06-12', NULL, '2019-06-19', 'anseryne@gmail.com', 1185.00),
(19, '2019-06-20', NULL, '2019-06-27', 'administradornipohtels@gmail.com', 688.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipopensiones_hoteles`
--

CREATE TABLE `tipopensiones_hoteles` (
  `tipo_pensiones_id_pension` char(1) COLLATE utf16_bin NOT NULL,
  `hoteles_id_hotel` int(10) NOT NULL,
  `precio_final_pension` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `tipopensiones_hoteles`
--

INSERT INTO `tipopensiones_hoteles` (`tipo_pensiones_id_pension`, `hoteles_id_hotel`, `precio_final_pension`) VALUES
('1', 1, 0),
('1', 2, 0),
('1', 8, 0),
('2', 1, 55),
('2', 2, 72),
('2', 8, 20),
('3', 1, 138),
('3', 2, 180),
('3', 8, 50),
('4', 1, 275),
('4', 2, 360),
('4', 8, 100),
('5', 1, 385),
('5', 2, 504),
('5', 8, 150);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_alojamientos`
--

CREATE TABLE `tipos_alojamientos` (
  `id_tipo_alojamiento` char(1) COLLATE utf16_bin NOT NULL,
  `descripcion` varchar(50) COLLATE utf16_bin NOT NULL,
  `precio` double(5,2) NOT NULL,
  `tipo_pensiones_id_pension` char(1) COLLATE utf16_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `tipos_alojamientos`
--

INSERT INTO `tipos_alojamientos` (`id_tipo_alojamiento`, `descripcion`, `precio`, `tipo_pensiones_id_pension`) VALUES
('1', 'doble', 100.00, '3'),
('1', 'sasad', 100.00, '4'),
('2', 'individual', 150.00, '5'),
('3', 'ddads', 200.00, '2'),
('4', 'doand', 250.00, '1'),
('5', 'dsboa', 300.00, '4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pensiones`
--

CREATE TABLE `tipo_pensiones` (
  `id_pension` char(1) COLLATE utf16_bin NOT NULL,
  `descripcion` varchar(50) COLLATE utf16_bin NOT NULL,
  `precio_base` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `tipo_pensiones`
--

INSERT INTO `tipo_pensiones` (`id_pension`, `descripcion`, `precio_base`) VALUES
('1', 'Nothing added', 0),
('2', 'Breakfast', 10),
('3', 'Half board', 25),
('4', 'Full board', 50),
('5', 'All included', 70);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `correo` varchar(100) COLLATE utf16_bin NOT NULL,
  `apellidos` varchar(50) COLLATE utf16_bin NOT NULL,
  `nombre` varchar(50) COLLATE utf16_bin NOT NULL,
  `password` varchar(200) COLLATE utf16_bin NOT NULL,
  `tipo_usuario` char(1) COLLATE utf16_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`correo`, `apellidos`, `nombre`, `password`, `tipo_usuario`) VALUES
('administradornipohtels@gmail.com', 'Nipohtels', 'Administrador', '25e4ee4e9229397b6b17776bfceaf8e7', '1'),
('anseryne@gmail.com', 'Aparicio Rivero', 'Ester', '4c882dcb24bcb1bc225391a602feca7c', '2'),
('lagartitos@gmail.com', 'Gar', 'Krok', 'a4be9a0e6ed093467be4601816289fac', '2'),
('patata@gmail.com', 'Otonashi', 'Saya', '4c882dcb24bcb1bc225391a602feca7c', '2'),
('stevenpearl@gmail.com', 'Star', 'Pearl', '218f6ea8d9863e1a68cb9b710f84c4e8', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `id_valoracion` int(10) NOT NULL,
  `fecha` date NOT NULL,
  `valor` int(11) NOT NULL,
  `usuarios_correo` varchar(100) COLLATE utf16_bin NOT NULL,
  `reservas_fecha_reserva` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_bin;

--
-- Volcado de datos para la tabla `valoraciones`
--

INSERT INTO `valoraciones` (`id_valoracion`, `fecha`, `valor`, `usuarios_correo`, `reservas_fecha_reserva`) VALUES
(7, '2019-05-29', 3, 'anseryne@gmail.com', '2019-05-01'),
(16, '2019-05-29', 2, 'anseryne@gmail.com', '2019-05-27');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD KEY `tipo_alojamiento_id_fk` (`tipo_alojamiento_id_alojamiento`),
  ADD KEY `tipo_pension_id_fk` (`tipo_pensiones_id_pension`),
  ADD KEY `hoteles_id_hotel_fk` (`hoteles_id_hotel`) USING BTREE;

--
-- Indices de la tabla `habitaciones_reservas`
--
ALTER TABLE `habitaciones_reservas`
  ADD PRIMARY KEY (`habitaciones_id_habitacion`,`reservas_id_reserva`),
  ADD KEY `reservas_id_reserva` (`reservas_id_reserva`);

--
-- Indices de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  ADD PRIMARY KEY (`id_hotel`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD UNIQUE KEY `reservas__idx` (`valoraciones_id_valoracion`),
  ADD KEY `usuarios_correo_usuario_fk` (`usuarios_correo_usuario`);

--
-- Indices de la tabla `tipopensiones_hoteles`
--
ALTER TABLE `tipopensiones_hoteles`
  ADD PRIMARY KEY (`tipo_pensiones_id_pension`,`hoteles_id_hotel`),
  ADD KEY `hoteles_id_hotel` (`hoteles_id_hotel`);

--
-- Indices de la tabla `tipos_alojamientos`
--
ALTER TABLE `tipos_alojamientos`
  ADD PRIMARY KEY (`id_tipo_alojamiento`,`tipo_pensiones_id_pension`),
  ADD KEY `tipo_pensiones_id_pension` (`tipo_pensiones_id_pension`);

--
-- Indices de la tabla `tipo_pensiones`
--
ALTER TABLE `tipo_pensiones`
  ADD PRIMARY KEY (`id_pension`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`correo`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`id_valoracion`),
  ADD KEY `usuarios_correo` (`usuarios_correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `habitaciones_reservas`
--
ALTER TABLE `habitaciones_reservas`
  MODIFY `reservas_id_reserva` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `hoteles`
--
ALTER TABLE `hoteles`
  MODIFY `id_hotel` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  MODIFY `id_valoracion` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD CONSTRAINT `tipo_alojamiento_id_fk` FOREIGN KEY (`tipo_alojamiento_id_alojamiento`) REFERENCES `tipos_alojamientos` (`id_tipo_alojamiento`),
  ADD CONSTRAINT `tipo_pension_id_fk` FOREIGN KEY (`tipo_pensiones_id_pension`) REFERENCES `tipo_pensiones` (`id_pension`);

--
-- Filtros para la tabla `habitaciones_reservas`
--
ALTER TABLE `habitaciones_reservas`
  ADD CONSTRAINT `habitaciones_id_habitacion_fk` FOREIGN KEY (`habitaciones_id_habitacion`) REFERENCES `habitaciones` (`id_habitacion`),
  ADD CONSTRAINT `reservas_id_reserva_fk` FOREIGN KEY (`reservas_id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `usuarios_correo_usuario_fk` FOREIGN KEY (`usuarios_correo_usuario`) REFERENCES `usuarios` (`correo`),
  ADD CONSTRAINT `valoraciones_id_valoracion_fk` FOREIGN KEY (`valoraciones_id_valoracion`) REFERENCES `valoraciones` (`id_valoracion`);

--
-- Filtros para la tabla `tipopensiones_hoteles`
--
ALTER TABLE `tipopensiones_hoteles`
  ADD CONSTRAINT `tipo_pensiones_id_pension_fk` FOREIGN KEY (`tipo_pensiones_id_pension`) REFERENCES `tipo_pensiones` (`id_pension`),
  ADD CONSTRAINT `tipopensiones_hoteles_ibfk_1` FOREIGN KEY (`tipo_pensiones_id_pension`) REFERENCES `tipo_pensiones` (`id_pension`),
  ADD CONSTRAINT `tipopensiones_hoteles_id_fk` FOREIGN KEY (`hoteles_id_hotel`) REFERENCES `hoteles` (`id_hotel`);

--
-- Filtros para la tabla `tipos_alojamientos`
--
ALTER TABLE `tipos_alojamientos`
  ADD CONSTRAINT `tipos_alojamientos_ibfk_1` FOREIGN KEY (`tipo_pensiones_id_pension`) REFERENCES `tipo_pensiones` (`id_pension`);

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`usuarios_correo`) REFERENCES `usuarios` (`correo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
