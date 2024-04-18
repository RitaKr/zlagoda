-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Mar 29, 2024 at 03:04 AM
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
-- Table structure for table `Product`
--

CREATE TABLE `Product` (
  `id_product` int NOT NULL,
  `category_number` int NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `producer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `characteristics` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Product`
--

INSERT INTO `Product` (`id_product`, `category_number`, `product_name`, `producer`, `characteristics`) VALUES
(1, 2, 'Печиво Oreo', 'Mondelez International', 'Шоколадне печиво з молочною начинкою'),
(6, 6, 'Сік Садочок виноград-яблуко 1л', 'Sandora', 'Виноградно-яблучний сік, 1л'),
(23, 1, 'Сирок глазурований', 'Чудо', 'Сирок глазурований з карамельною начинкою, 100г'),
(24, 6, 'Сік Садочок апельсин 1л', 'Sandora', 'Апельсиновий сік, 1л'),
(25, 7, 'Lion', 'Nestle', 'шоколадний батончик з карамеллю'),
(36, 1, 'Молоко', 'Яготинське', '1л пастеризованого молока, 2% жирності'),
(37, 1, 'Кефір', 'Слов\'яночка', 'Кефір 5% жирності'),
(38, 2, 'Barni шоколадний', 'Nestle', 'Бісквітний батончик з шоколадною начинкою'),
(52, 1, 'Молоко', 'Слов&#39;яночка', '1л пастеризованого молока, 1.5% жирності'),
(53, 6, 'Fanta 0.5л', 'Sandora', 'Газований напій зі смаком апельсину, 0.5л');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `category_number` (`category_number`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Product`
--
ALTER TABLE `Product`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `category_number` FOREIGN KEY (`category_number`) REFERENCES `Category` (`category_number`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
