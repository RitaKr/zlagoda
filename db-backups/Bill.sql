-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: database
-- Час створення: Бер 20 2024 р., 01:43
-- Версія сервера: 8.3.0
-- Версія PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `zlagoda`
--

-- --------------------------------------------------------

--
-- Структура таблиці `Bill`
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
-- Дамп даних таблиці `Bill`
--

INSERT INTO `Bill` (`bill_number`, `id_employee_bill`, `card_number`, `print_date`, `sum_total`, `vat`) VALUES
('0000000000', 'c000000001', '0000000000000', '2024-03-10 17:06:53', 1000.0000, 200.0000);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Bill`
--
ALTER TABLE `Bill`
  ADD PRIMARY KEY (`bill_number`),
  ADD KEY `card_number` (`card_number`),
  ADD KEY `id_employee` (`id_employee_bill`);

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `Bill`
--
ALTER TABLE `Bill`
  ADD CONSTRAINT `card_number` FOREIGN KEY (`card_number`) REFERENCES `Customer_Card` (`card_number`) ON UPDATE CASCADE,
  ADD CONSTRAINT `id_employee_bill` FOREIGN KEY (`id_employee_bill`) REFERENCES `Employee` (`id_employee`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
