-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-07-2024 a las 07:30:58
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
-- Base de datos: `mysql_tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tienda`
--

CREATE TABLE `tienda` (
  `id` int(11) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `precio` float UNSIGNED NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `marca` varchar(30) NOT NULL,
  `stock` int(10) UNSIGNED NOT NULL,
  `imagen` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tienda`
--

INSERT INTO `tienda` (`id`, `nombre`, `precio`, `tipo`, `marca`, `stock`, `imagen`) VALUES
(2, 'Lumia 3100', 700000, 'Smartphone', 'Nokia', 19488, 'ImagenesDeProductos/2024/Smartphone-Lumia 3100.png'),
(3, 'Lumia 3300', 700000, 'Smartphone', 'Nokia', 9989, 'ImagenesDeProductos/2024/Smartphone-Lumia 3300.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `email` varchar(70) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `idProducto` int(10) UNSIGNED NOT NULL,
  `precio` float UNSIGNED NOT NULL,
  `cantidad` int(10) UNSIGNED NOT NULL,
  `imagen` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `fecha`, `email`, `usuario`, `idProducto`, `precio`, `cantidad`, `imagen`) VALUES
(1, '2024-07-04', 'gaston@gmail.com', 'Gaston', 2, 350000000, 500, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3100-gaston.jpeg'),
(2, '2024-07-05', 'gaston@gmail.com', 'Gaston', 2, 350000000, 500, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3100-gaston.jpeg'),
(3, '2024-07-05', 'gaston@gmail.com', 'Gaston', 2, 350000000, 500, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3100-gaston.jpeg'),
(4, '2024-07-05', 'carlos@gmail.com', 'Gaston', 2, 7000000, 10, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3100-carlos.jpeg'),
(5, '2024-07-05', 'carlos@gmail.com', 'Gaston', 3, 1400000, 2, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3300-carlos.jpeg'),
(6, '2024-07-05', 'carlos@gmail.com', 'Gaston', 3, 2100000, 3, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3300-carlos.jpeg'),
(7, '2024-07-05', 'cesar@gmail.com', 'Gaston', 3, 1400000, 2, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3300-cesar.jpeg'),
(8, '2024-07-05', 'cesar@gmail.com', 'Gaston', 2, 1400000, 2, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3100-cesar.jpeg'),
(9, '2024-07-05', 'cesar@gmail.com', 'Gaston', 3, 2800000, 4, 'ImagenesDeVentas/2024/Smartphone-Nokia-Lumia 3300-cesar.jpeg');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tienda`
--
ALTER TABLE `tienda`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tienda`
--
ALTER TABLE `tienda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
