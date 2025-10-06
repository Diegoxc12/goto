CREATE DATABASE IF NOT EXISTS goto_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE goto_db;

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 06-10-2025 a las 16:42:44
-- Versión del servidor: 10.4.32-MariaDB-1:10.4.32+maria~ubu2004
-- Versión de PHP: 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `goto_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bodegas`
--

CREATE TABLE `bodegas` (
  `id_bodega` int(11) NOT NULL,
  `nombre_bodega` varchar(150) NOT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `egresos`
--

CREATE TABLE `egresos` (
  `id_egreso` int(11) NOT NULL,
  `codigo_producto` varchar(50) NOT NULL,
  `fecha_movimiento` date NOT NULL,
  `egresos` decimal(12,2) NOT NULL,
  `bodega` int(11) NOT NULL,
  `descripcion_producto` text DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE `ingresos` (
  `id_ingreso` int(11) NOT NULL,
  `codigo_producto` varchar(50) NOT NULL,
  `fecha_movimiento` date NOT NULL,
  `ingresos` decimal(12,2) NOT NULL,
  `bodega` int(11) NOT NULL,
  `descripcion_producto` text DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario_saldos`
--

CREATE TABLE `inventario_saldos` (
  `codigo_producto` varchar(64) NOT NULL,
  `codigo_bodega` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `costo_actual` decimal(12,4) DEFAULT NULL,
  `precio_referencia` decimal(12,4) DEFAULT NULL,
  `fecha_compra_ref` date DEFAULT NULL,
  `fecha_venta_ref` date DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `codigo_producto` varchar(64) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `marca` varchar(120) DEFAULT NULL,
  `imagen_webp` varchar(255) DEFAULT NULL,
  `creado_por` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bodegas`
--
ALTER TABLE `bodegas`
  ADD PRIMARY KEY (`id_bodega`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD PRIMARY KEY (`id_egreso`),
  ADD KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `bodega` (`bodega`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `codigo_producto` (`codigo_producto`),
  ADD KEY `bodega` (`bodega`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `inventario_saldos`
--
ALTER TABLE `inventario_saldos`
  ADD PRIMARY KEY (`codigo_producto`,`codigo_bodega`),
  ADD KEY `codigo_bodega` (`codigo_bodega`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo_producto`),
  ADD KEY `creado_por` (`creado_por`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bodegas`
--
ALTER TABLE `bodegas`
  MODIFY `id_bodega` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `egresos`
--
ALTER TABLE `egresos`
  MODIFY `id_egreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `bodegas`
--
ALTER TABLE `bodegas`
  ADD CONSTRAINT `bodegas_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `egresos`
--
ALTER TABLE `egresos`
  ADD CONSTRAINT `egresos_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo_producto`),
  ADD CONSTRAINT `egresos_ibfk_2` FOREIGN KEY (`bodega`) REFERENCES `bodegas` (`id_bodega`),
  ADD CONSTRAINT `egresos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo_producto`),
  ADD CONSTRAINT `ingresos_ibfk_2` FOREIGN KEY (`bodega`) REFERENCES `bodegas` (`id_bodega`),
  ADD CONSTRAINT `ingresos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `inventario_saldos`
--
ALTER TABLE `inventario_saldos`
  ADD CONSTRAINT `inventario_saldos_ibfk_1` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo_producto`),
  ADD CONSTRAINT `inventario_saldos_ibfk_2` FOREIGN KEY (`codigo_bodega`) REFERENCES `bodegas` (`id_bodega`),
  ADD CONSTRAINT `inventario_saldos_ibfk_3` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
