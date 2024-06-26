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
-- Структура таблиці `Employee`
--

CREATE TABLE `Employee` (
  `id_employee` varchar(10) NOT NULL,
  `empl_surname` varchar(50) NOT NULL,
  `empl_name` varchar(50) NOT NULL,
  `empl_patronymic` varchar(50) DEFAULT NULL,
  `empl_role` enum('manager','cashier') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `salary` decimal(13,4) NOT NULL,
  `date_of_birth` date NOT NULL,
  `date_of_start` date NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `city` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `zip_code` varchar(9) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `Employee`
--

INSERT INTO `Employee` (`id_employee`, `empl_surname`, `empl_name`, `empl_patronymic`, `empl_role`, `salary`, `date_of_birth`, `date_of_start`, `phone_number`, `city`, `street`, `zip_code`) VALUES
('0000000000', 'Петренко', 'Петро', 'Петрович', 'manager', 41200.0000, '1995-03-03', '2023-02-20', '+380123456789', 'Київ', 'вул. Хрещатик, 1', '02000'),
('0000000001', 'Іваненко', 'Іван', NULL, 'cashier', 12000.0000, '1988-05-05', '2024-01-20', '+380661234567', 'Київ', 'вул. Сагайдачного, 2', '02000'),
('0000000002', 'Федоренко', 'Федір', 'Федорович', 'manager', 40200.0000, '1985-07-08', '2022-02-02', '+380951234567', 'Київ', 'вул. Хрещатик, 3', '02000'),
('0000000003', 'Василенко', 'Василь', 'Васильович', 'cashier', 13000.0000, '2000-10-14', '2023-02-20', '+380671234567', 'Київ', 'вул. Хмельницького, 2', '02000'),
('0000000004', 'Клименко', 'Клим', NULL, 'manager', 38200.0000, '1999-01-24', '2023-12-17', '+380991234567', 'Київ', 'вул. сковороди, 1', '02000'),
('0000000005', 'Антонович', 'Антон', 'Антонович', 'cashier', 15000.0000, '2000-04-04', '2023-12-06', '+380673981354', 'Київ', 'просп. Степана Бандери, 5', '02451'),
('0000000006', 'Олексієнко', 'Олексій', 'Олексійович', 'cashier', 12500.0000, '2004-10-18', '2024-04-05', '+380678094514', 'Харків', 'вул. Тараса Шевченка, 28', '02984'),
('0000000007', 'Мартиненко', 'Марта', 'Маратівна', 'manager', 30000.0000, '2001-07-18', '2023-09-22', '+38096355133', 'Львів', 'вул. Степана Бандери, 10', '06583');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Employee`
--
ALTER TABLE `Employee`
  ADD PRIMARY KEY (`id_employee`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
