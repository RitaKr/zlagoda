-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Mar 29, 2024 at 03:03 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.8
USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zlagoda`
--

-- --------------------------------------------------------

--
-- Table structure for table `Store_Product`
--

CREATE TABLE `Store_Product` (
  `UPC` varchar(12) NOT NULL,
  `UPC_prom` varchar(12) DEFAULT NULL,
  `id_product` int NOT NULL,
  `selling_price` decimal(13,4) NOT NULL,
  `products_number` int NOT NULL,
  `promotional_product` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Store_Product`
--

INSERT INTO `Store_Product` (`UPC`, `UPC_prom`, `id_product`, `selling_price`, `products_number`, `promotional_product`) VALUES
('012345678900', NULL, 38, 17.4900, 29, 0),
('012345678901', NULL, 25, 14.9900, 42, 0),
('012345678902', '12345678910', 37, 32.0000, 8, 1),
('012345678908', NULL, 24, 49.3900, 25, 0),
('012345678909', NULL, 6, 40.5000, 27, 0),
('012345678914', NULL, 23, 18.0000, 9, 0),
('12345678910', NULL, 37, 40.0000, 9, 0),
('12345678911', NULL, 1, 42.0000, 10, 0),
('12345678912', '012345678914', 23, 12.8000, 17, 1),
('12345678913', NULL, 36, 36.0000, 10, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Store_Product`
--
ALTER TABLE `Store_Product`
  ADD PRIMARY KEY (`UPC`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `UPC_prom` (`UPC_prom`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Store_Product`
--
ALTER TABLE `Store_Product`
  ADD CONSTRAINT `id_product` FOREIGN KEY (`id_product`) REFERENCES `Product` (`id_product`) ON UPDATE CASCADE,
  ADD CONSTRAINT `UPC_prom` FOREIGN KEY (`UPC_prom`) REFERENCES `Store_Product` (`UPC`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
