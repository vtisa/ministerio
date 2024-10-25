-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2023 at 08:07 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ministerio`
--

-- --------------------------------------------------------

--
-- Table structure for table `flujo`
--

CREATE TABLE `flujo` (
  `id` int(11) NOT NULL,
  `tipo` enum('importacion','exportacion') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flujo`
--

INSERT INTO `flujo` (`id`, `tipo`) VALUES
(1, 'importacion'),
(2, 'exportacion');

-- --------------------------------------------------------

--
-- Table structure for table `paises`
--

CREATE TABLE `paises` (
  `id` int(11) NOT NULL,
  `nombre_pais` varchar(50) NOT NULL,
  `tipo_relacion` varchar(250) DEFAULT NULL,
  `fecha_relacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paises`
--

INSERT INTO `paises` (`id`, `nombre_pais`, `tipo_relacion`, `fecha_relacion`) VALUES
(1, 'China', 'Tratado de Libre Comercio', '2009-01-01'),
(2, 'Brasil', 'Cooperación en defensa, energía e infraestructura', '2010-02-01'),
(3, 'Japón', 'Acuerdo de Asociación Económica', '2011-01-01'),
(4, 'España', 'Relaciones culturales y diplomáticas', '2012-01-02');

-- --------------------------------------------------------

--
-- Table structure for table `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `flujo_id` int(11) DEFAULT NULL,
  `pais_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `flujo_id`, `pais_id`) VALUES
(1, 'Mango y palta', 'este paquete va para China', 2, 1),
(2, 'Oro', 'este cargamento va a Brasil', 1, 2),
(3, 'Cobre', 'este cargamento va a Japón', 1, 3),
(4, 'Arina de animales', 'este paquete va a España', 2, 4),
(5, 'Papas', 'este producto es un cargamento de tuberculo', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contraseña` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre_usuario`, `contraseña`) VALUES
(1, 'admin', '$2y$10$JG4vesSPNabyLrH98HUfCOSyy9bwKJ.qpnfZrgMoZoVknUElaBD9W'),
(2, 'usuario', '$2y$10$f5qXIvw2Jr9Znx8UiXdrDuA2ZEmhS/ppph8GoTPDZFaKKAQYz7K4q');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `flujo`
--
ALTER TABLE `flujo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `paises`
--
ALTER TABLE `paises`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_producto_flujo` (`flujo_id`),
  ADD KEY `fk_producto_pais` (`pais_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `flujo`
--
ALTER TABLE `flujo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `paises`
--
ALTER TABLE `paises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_producto_flujo` FOREIGN KEY (`flujo_id`) REFERENCES `flujo` (`id`),
  ADD CONSTRAINT `fk_producto_pais` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`),
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`flujo_id`) REFERENCES `flujo` (`id`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`pais_id`) REFERENCES `paises` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
