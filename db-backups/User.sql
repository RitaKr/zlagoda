-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: database
-- Час створення: Квт 20 2024 р., 08:24
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
-- Структура таблиці `User`
--

CREATE TABLE `User` (
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `id_employee` varchar(10) NOT NULL,
  `pass` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп даних таблиці `User`
--

INSERT INTO `User` (`username`, `id_employee`, `pass`) VALUES
('fed', '0000000002', 'ca63a50aa97ef5d173b5cf8dd774e47662172cad122c94d3d3ce101790ce326a'),
('ivanenko', '0000000001', '47ef20207489b775fa4cdcac3c394b517ab22d7460237ae3df1ac0e8963699d6'),
('petrenko', '0000000000', '47ef20207489b775fa4cdcac3c394b517ab22d7460237ae3df1ac0e8963699d6');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `User`
--
ALTER TABLE `User`
  ADD PRIMARY KEY (`username`),
  ADD KEY `id_employee` (`id_employee`);

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `User`
--
ALTER TABLE `User`
  ADD CONSTRAINT `id_employee` FOREIGN KEY (`id_employee`) REFERENCES `Employee` (`id_employee`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
