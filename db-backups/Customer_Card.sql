-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: database
-- Час створення: Бер 20 2024 р., 01:42
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
-- Структура таблиці `Customer_Card`
--

CREATE TABLE `Customer_Card` (
  `card_number` varchar(13) NOT NULL,
  `cust_surname` varchar(50) NOT NULL,
  `cust_name` varchar(50) NOT NULL,
  `cust_patronymic` varchar(50) DEFAULT NULL,
  `phone_number` varchar(13) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `street` varchar(50) DEFAULT NULL,
  `zip_code` varchar(9) DEFAULT NULL,
  `percent` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `Customer_Card`
--

INSERT INTO `Customer_Card` (`card_number`, `cust_surname`, `cust_name`, `cust_patronymic`, `phone_number`, `city`, `street`, `zip_code`, `percent`) VALUES
('0000000000000', 'Марчук', 'Марія', NULL, '+380123456789', 'Київ', 'вул. Хрещатик, 5', '02000', 10),
('0000000000001', 'Бойко', 'Степан', NULL, '+380661234567', 'Київ', 'вул. Хрещатик, 6', '02000', 8);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Customer_Card`
--
ALTER TABLE `Customer_Card`
  ADD PRIMARY KEY (`card_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
