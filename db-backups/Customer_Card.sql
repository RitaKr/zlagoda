-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: database
-- Час створення: Квт 20 2024 р., 08:23
-- Версія сервера: 8.3.0
-- Версія PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";


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
('0000000000001', 'Бойко', 'Степан', 'Андрійович', '+380661234567', 'Київ', 'вул. Хрещатик, 6', '02000', 8),
('0000000000002', 'Пєчкурова', 'Олена', 'Миколаївна', '+380123456788', 'Київ', '', '02000', 25),
('0000000000003', 'Кирієнко', 'Оксана', NULL, '+380123456789', 'Київ', '', '02000', 25),
('0000000000004', 'Зважій', 'Дмитро', 'Володимирович', '+380123456780', 'Вінниця', '', '', 40),
('0000000000005', 'Квятковський', 'Андрій', 'Вікторович', '+380672281337', 'Київ', 'вул. Феодори Пушиної, 4', '05731', 33),
('0000000000006', 'Богун', 'Єлизавета', 'Сергіївна', '+380666666666', 'Київ', 'вул. Банкова, 11', '06666', 33),
('0000000000007', 'Крижанівська', 'Маргарита', 'Сергіївна', '+380964040101', 'Київ', 'вул. Іллінська, 9', '01010', 33),
('0000000000008', 'Сергєнко', 'Леся', '', '+380967773311', 'Київ', '', '06666', 1),
('0000000000009', 'Андрішко', 'Олег', 'Віталійович', '+380663852490', 'Одеса', 'вул. Деребасівська, 1', '03725', 35);

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
