-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Mar 29, 2024 at 03:04 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zlagoda`
--

-- --------------------------------------------------------

--
-- Table structure for table `Sale`
--

CREATE TABLE `Sale` (
  `UPC` varchar(12) NOT NULL,
  `bill_number` varchar(10) NOT NULL,
  `product_number` int NOT NULL,
  `selling_price` decimal(13,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Sale`
--

INSERT INTO `Sale` (`UPC`, `bill_number`, `product_number`, `selling_price`) VALUES
('012345678900', '0000000000', 2, 17.4900),
('012345678900', '0000000001', 4, 17.4900),
('012345678900', '0000000006', 1, 17.4900),
('012345678901', '0000000000', 3, 14.9900),
('012345678901', '0000000003', 1, 14.9900),
('012345678902', '0000000000', 1, 32.0000),
('012345678902', '0000000004', 2, 32.0000),
('012345678902', '0000000005', 1, 32.0000),
('012345678908', '0000000001', 1, 49.3900),
('012345678909', '0000000001', 1, 40.5000),
('012345678909', '0000000003', 1, 40.5000),
('12345678910', '0000000003', 1, 40.0000),
('12345678911', '0000000003', 2, 42.0000),
('12345678911', '0000000007', 15, 42.0000),
('12345678911', '0000000008', 5, 42.0000),
('12345678912', '0000000006', 3, 16.0000),
('12345678913', '0000000001', 1, 36.0000),
('12345678913', '0000000003', 1, 36.0000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Sale`
--
ALTER TABLE `Sale`
  ADD PRIMARY KEY (`UPC`,`bill_number`),
  ADD KEY `bill_number` (`bill_number`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Sale`
--
ALTER TABLE `Sale`
  ADD CONSTRAINT `bill_number` FOREIGN KEY (`bill_number`) REFERENCES `Bill` (`bill_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UPC` FOREIGN KEY (`UPC`) REFERENCES `Store_Product` (`UPC`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
