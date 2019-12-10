-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2019 a las 15:50:41
-- Versión del servidor: 10.3.16-MariaDB
-- Versión de PHP: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `comandita`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `id_empleado` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `nombre_pedido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_cliente` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `estado` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tiempo_preparacion` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_empleado` int(11) NOT NULL,
  `nombre` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `apellido` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_usuario` varchar(55) COLLATE utf8_spanish_ci NOT NULL,
  `clave` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `tipo` varchar(55) COLLATE utf8_spanish_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_empleado`, `nombre`, `apellido`, `nombre_usuario`, `clave`, `tipo`, `foto`, `created_at`, `updated_at`) VALUES
(3, 'nico', 'miguenz', '0', 'cltP7S89pvyUE', 'bartender', '', '2019-12-02 23:28:17', '2019-12-02 23:28:17'),
(4, 'javier', 'santono', '0', 'cltP7S89pvyUE', 'cervecero', '', '2019-12-02 23:29:15', '2019-12-02 23:29:15'),
(5, 'pablo', 'chapuza', '0', 'cltP7S89pvyUE', 'cocinero', '', '2019-12-02 23:29:40', '2019-12-02 23:29:40'),
(6, 'lito', 'Mazzeo', '0', 'cltP7S89pvyUE', 'mozo', '', '2019-12-02 23:30:06', '2019-12-02 23:30:06'),
(9, 'Daniel', 'Ramos', '0', 'cltP7S89pvyUE', 'socio', 'D:\\Programas\\XAMPP\\htdocs\\comandita\\src\\app\\modelORM../../../../img/usuarios/socio-02-12-2019-163703.jpg', '2019-12-02 23:37:03', '2019-12-02 23:37:03'),
(11, 'Daniela', 'Ramoswa', 'lalala', 'cltP7S89pvyUE', 'socio', 'D:\\Programas\\XAMPP\\htdocs\\comandita\\src\\app\\modelORM../../../../img/usuarios/socio-02-12-2019-214544.jpg', '2019-12-03 04:45:45', '2019-12-03 06:13:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_empleado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
