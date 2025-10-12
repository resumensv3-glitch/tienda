-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-10-2025 a las 19:36:57
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
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `IDcategorias` int(11) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `estilo` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`IDcategorias`, `categoria`, `estilo`, `descripcion`) VALUES
(1, 'Camisas', 'NOse ', 'Prueba'),
(2, 'Pantalones', 'Aja', 'Sino'),
(3, 'Faldas', 'Juvelin', 'Para algo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `IDcliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `DUI` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`IDcliente`, `nombre`, `apellido`, `email`, `telefono`, `DUI`) VALUES
(1, 'Henrry', 'Teus', 'teushenrry@gmail.com', '77210563', '068178362'),
(2, 'Henrry', 'teus', 'teushdhf@gmail.com', '77210563', '6165165156'),
(3, 'Henrry ', 'Teus', 'grurh@gmail.com', '88613', '13564'),
(4, 'Henrry ', 'Cañas', 'fgjflh@gmail.com', '16165121', '16556651'),
(5, 'Marilyn', 'Merlos', 'rgkfjn@gmail.com', '8738747864', '46894984'),
(6, 'Valentina', 'Capuchina', 'hueguih@gmail.com', '5644656', '665656262'),
(7, 'Marily ', 'Merlos', 'gjgljk@gmail.com', '84858', '495868'),
(8, 'Marilyn ', 'Rosales', 'jehjf@gmail.com', '7726546', '4387578'),
(9, 'Teresa', 'Del Carmen', 'teiugdygD@gmail.com', '84785785', '54784575'),
(10, 'tghy', 'thjgthj', 'ergt@gmai.com', '665651651', '16165165'),
(11, 'hgrig', 'rfhrfg', '', '', ''),
(15, 'Alexis', 'Teus', NULL, NULL, NULL),
(16, 'Yaquelin ', 'Franco', NULL, NULL, NULL),
(17, 'rgirg', 'wrghrgh', NULL, NULL, NULL),
(18, 'Natanael', 'Perez', NULL, '72548255', '545518514');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_ventas`
--

CREATE TABLE `detalle_ventas` (
  `IDdetalle` int(11) NOT NULL,
  `IDventas` int(11) NOT NULL,
  `IDproductos` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_ventas`
--

INSERT INTO `detalle_ventas` (`IDdetalle`, `IDventas`, `IDproductos`, `cantidad`, `precio_unitario`, `subtotal`) VALUES
(1, 1, 4, 1, 10.00, 10.00),
(2, 2, 4, 2, 10.00, 20.00),
(3, 3, 2, 2, 80.00, 160.00),
(4, 3, 4, 1, 10.00, 10.00),
(5, 4, 4, 1, 10.00, 10.00),
(6, 5, 4, 1, 10.00, 10.00),
(7, 6, 4, 1, 10.00, 10.00),
(8, 7, 3, 1, 20.00, 20.00),
(9, 8, 3, 1, 20.00, 20.00),
(10, 9, 3, 1, 20.00, 20.00),
(11, 10, 4, 1, 10.00, 10.00),
(12, 11, 3, 6, 20.00, 120.00),
(13, 12, 4, 1, 10.00, 10.00),
(14, 12, 1, 1, 50.00, 50.00),
(15, 13, 5, 1, 60.00, 60.00),
(16, 13, 4, 1, 10.00, 10.00),
(17, 13, 1, 1, 50.00, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturas`
--

CREATE TABLE `facturas` (
  `IDfactura` int(11) NOT NULL,
  `numero_factura` varchar(50) NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `NIT` varchar(50) DEFAULT NULL,
  `DUI` varchar(50) DEFAULT NULL,
  `IDventas` int(11) DEFAULT NULL,
  `tipo_factura` varchar(50) DEFAULT NULL,
  `total` decimal(12,2) DEFAULT NULL,
  `nombre_cliente` varchar(100) DEFAULT NULL,
  `apellido_cliente` varchar(100) DEFAULT NULL,
  `email_cliente` varchar(150) DEFAULT NULL,
  `telefono_cliente` varchar(20) DEFAULT NULL,
  `DUI_cliente` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturas`
--

INSERT INTO `facturas` (`IDfactura`, `numero_factura`, `fecha`, `NIT`, `DUI`, `IDventas`, `tipo_factura`, `total`, `nombre_cliente`, `apellido_cliente`, `email_cliente`, `telefono_cliente`, `DUI_cliente`) VALUES
(1, 'F-1758835309', '2025-09-25 21:21:49', NULL, '13564', 1, 'Consumidor Final', 11.30, 'Henrry ', 'Teus', 'grurh@gmail.com', '88613', '13564'),
(2, 'F-1758835900', '2025-09-25 21:31:40', '1234-567890-001-0', '16556651', 2, 'Credito Fiscal', 22.60, 'Henrry ', 'Cañas', 'fgjflh@gmail.com', '16165121', '16556651');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `IDproductos` int(11) NOT NULL,
  `producto` varchar(150) NOT NULL,
  `categorias` int(11) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `marca` varchar(100) DEFAULT NULL,
  `talla` varchar(50) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`IDproductos`, `producto`, `categorias`, `imagen`, `stock`, `marca`, `talla`, `precio`) VALUES
(1, 'Camisas rosadas', 1, 'uploads/1758389166_rosadas.jpg', 3, 'Adias', 'M', 50.00),
(2, 'Camisas Rojas', 1, 'uploads/1758389200_rojas.jpg', 3, 'Nike', 'S', 80.00),
(3, 'Pantalones Boddy', 2, 'uploads/1758389238_pantalon.jpg', 43, '0', '34', 20.00),
(4, 'Camisas verdes', 1, 'uploads/1758764674_verde.jpg', 13, '0', 'S', 10.00),
(5, 'Falda Verde', 3, 'uploads/1760203203_descarga.jpg', 36, 'Calvin Clein', '34', 60.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `IDusuarios` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `contraseña` varchar(255) NOT NULL,
  `rol` enum('admin','vendedor','bodega') NOT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`IDusuarios`, `nombre`, `usuario`, `contraseña`, `rol`, `foto`) VALUES
(1, 'Maria Teresa', 'Teresa', '123', 'admin', NULL),
(2, 'Henrry Teus', 'Teus', '123', 'vendedor', 'uploads/68ea90e18d0a2_descarga.jpg'),
(3, 'Valentina Capuchina', 'Valentina', '123', 'bodega', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `IDventas` int(11) NOT NULL,
  `referencia` varchar(100) NOT NULL,
  `IDusuarios` int(11) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `iva` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `IDcliente` int(11) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`IDventas`, `referencia`, `IDusuarios`, `tipo`, `subtotal`, `iva`, `total`, `IDcliente`, `fecha`) VALUES
(1, 'V-1758835309', 3, 'Consumidor Final', 10.00, 1.30, 11.30, 3, '2025-09-25 21:21:49'),
(2, 'V-1758835900', 3, 'Credito Fiscal', 20.00, 2.60, 22.60, 4, '2025-09-25 21:31:40'),
(3, 'V-1758836295', 3, 'Consumidor Final', 170.00, 22.10, 192.10, 5, '2025-09-25 21:38:15'),
(4, 'V-1759082726', 1, 'Consumidor Final', 10.00, 1.30, 11.30, 6, '2025-09-28 18:05:26'),
(5, 'V-1759083035', 1, 'Consumidor Final', 10.00, 1.30, 11.30, 7, '2025-09-28 18:10:35'),
(6, 'V-1759083189', 1, 'Consumidor Final', 10.00, 1.30, 11.30, 8, '2025-09-28 18:13:09'),
(7, 'V-1759083549', 1, 'Credito Fiscal', 20.00, 2.60, 22.60, 9, '2025-09-28 18:19:09'),
(8, 'V-1759084321', 1, 'Consumidor Final', 20.00, 2.60, 22.60, 10, '2025-09-28 18:32:01'),
(9, 'V-1759085417', 2, 'Consumidor Final', 20.00, 2.60, 22.60, 11, '2025-09-28 18:50:17'),
(10, 'V-1759111470', 2, 'Consumidor Final', 10.00, 1.30, 11.30, 15, '2025-09-29 02:04:30'),
(11, 'V-1759111811', 2, 'Credito Fiscal', 120.00, 15.60, 135.60, 16, '2025-09-29 02:10:11'),
(12, 'V-1759111996', 2, 'Credito Fiscal', 60.00, 7.80, 67.80, 17, '2025-09-29 02:13:16'),
(13, 'V-1759246415', 2, 'Consumidor Final', 120.00, 15.60, 135.60, 18, '2025-09-30 15:33:35');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`IDcategorias`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`IDcliente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `DUI` (`DUI`);

--
-- Indices de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD PRIMARY KEY (`IDdetalle`),
  ADD KEY `fk_detalle_ventas_ventas` (`IDventas`),
  ADD KEY `fk_detalle_ventas_productos` (`IDproductos`);

--
-- Indices de la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD PRIMARY KEY (`IDfactura`),
  ADD KEY `fk_factura_venta` (`IDventas`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`IDproductos`),
  ADD KEY `fk_categoria` (`categorias`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`IDusuarios`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`IDventas`),
  ADD KEY `fk_ventas_clientes` (`IDcliente`),
  ADD KEY `fk_ventas_usuarios` (`IDusuarios`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `IDcategorias` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `IDcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  MODIFY `IDdetalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `facturas`
--
ALTER TABLE `facturas`
  MODIFY `IDfactura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `IDproductos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `IDusuarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `IDventas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalle_ventas`
--
ALTER TABLE `detalle_ventas`
  ADD CONSTRAINT `fk_detalle_ventas_productos` FOREIGN KEY (`IDproductos`) REFERENCES `productos` (`IDproductos`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detalle_ventas_ventas` FOREIGN KEY (`IDventas`) REFERENCES `ventas` (`IDventas`) ON DELETE CASCADE;

--
-- Filtros para la tabla `facturas`
--
ALTER TABLE `facturas`
  ADD CONSTRAINT `fk_factura_venta` FOREIGN KEY (`IDventas`) REFERENCES `ventas` (`IDventas`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categorias`) REFERENCES `categorias` (`IDcategorias`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_clientes` FOREIGN KEY (`IDcliente`) REFERENCES `clientes` (`IDcliente`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_ventas_usuarios` FOREIGN KEY (`IDusuarios`) REFERENCES `usuarios` (`IDusuarios`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
