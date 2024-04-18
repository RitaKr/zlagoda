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
-- Table structure for table `Bill`
--

CREATE TABLE `Bill` (
  `bill_number` varchar(10) NOT NULL,
  `id_employee_bill` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `card_number` varchar(13) DEFAULT NULL,
  `print_date` datetime NOT NULL,
  `sum_total` decimal(13,4) NOT NULL,
  `vat` decimal(13,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Bill`
--

INSERT INTO `Bill` (`bill_number`, `id_employee_bill`, `card_number`, `print_date`, `sum_total`, `vat`) VALUES
('0000000000', '0000000001', '0000000000000', '2024-03-10 17:06:53', 100.7600, 20.1520),
('0000000001', '0000000003', NULL, '2024-03-27 00:57:29', 195.8500, 39.1700),
('0000000003', '0000000004', NULL, '2024-02-20 09:18:03', 215.4900, 43.0980),
('0000000004', '0000000001', NULL, '2024-03-25 01:24:06', 64.0000, 12.8000),
('0000000005', '0000000001', NULL, '2024-03-28 17:18:03', 32.0000, 6.4000),
('0000000006', '0000000001', NULL, '2024-03-28 23:35:30', 65.4900, 13.1000),
('0000000007', '0000000001', '0000000000002', '2024-03-28 23:53:48', 472.5000, 94.5000),
('0000000008', '0000000001', NULL, '2024-03-28 23:55:36', 210.0000, 42.0000);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Bill`
--
ALTER TABLE `Bill`
  ADD PRIMARY KEY (`bill_number`),
  ADD KEY `card_number` (`card_number`),
  ADD KEY `id_employee` (`id_employee_bill`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Bill`
--
ALTER TABLE `Bill`
  ADD CONSTRAINT `card_number` FOREIGN KEY (`card_number`) REFERENCES `Customer_Card` (`card_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `id_employee_bill` FOREIGN KEY (`id_employee_bill`) REFERENCES `Employee` (`id_employee`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
