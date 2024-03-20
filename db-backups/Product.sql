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
-- Структура таблиці `Product`
--

CREATE TABLE `Product` (
  `id_product` int NOT NULL,
  `category_number` int NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `producer` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `characteristics` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `Product`
--

INSERT INTO `Product` (`id_product`, `category_number`, `product_name`, `producer`, `characteristics`) VALUES
(1, 2, 'Печиво Oreo', 'Mondelez International', 'Шоколадне печиво з молочною начинкою'),
(5, 2, 'Barni шоколадний', 'Mondelez International', 'Бісквітний ведмедик з шоколадною начинкою'),
(6, 6, 'Сік Садочок виноград-яблуко', 'Sandora', 'Виноградно-яблучний сік');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `Product`
--
ALTER TABLE `Product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `category_number` (`category_number`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `Product`
--
ALTER TABLE `Product`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `Product`
--
ALTER TABLE `Product`
  ADD CONSTRAINT `category_number` FOREIGN KEY (`category_number`) REFERENCES `Category` (`category_number`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
