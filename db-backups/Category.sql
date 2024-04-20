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
-- Структура таблиці `Category`
--

CREATE TABLE `Category` (
  `category_number` int NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `Category`
--

INSERT INTO `Category` (`category_number`, `category_name`) VALUES
(1, 'Молочні продукти'),
(2, 'Випічка'),
(3, 'Овочі'),
(4, 'Фрукти та ягоди'),
(5, 'Мясо та мясні вироби'),
(6, 'Напої'),
(7, 'Солодощі'),
(8, 'Риба та морепродукти'),
(9, 'Крупи та каші'),
(10, 'Соуси та спеції'),
(11, 'Снеки та закуски'),
(12, 'Дитяче харчування');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Category`
--
ALTER TABLE `Category`
  ADD PRIMARY KEY (`category_number`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `Category`
--
ALTER TABLE `Category`
  MODIFY `category_number` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
