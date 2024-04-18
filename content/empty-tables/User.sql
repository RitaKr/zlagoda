USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `User` (
  `username` varchar(50) NOT NULL,
  `id_employee` varchar(10) NOT NULL,
  `pass` varchar(100) NOT NULL
);

ALTER TABLE `User`
  ADD PRIMARY KEY (`username`),
  ADD KEY `id_employee` (`id_employee`);

ALTER TABLE `User`
  ADD CONSTRAINT `id_employee` FOREIGN KEY (`id_employee`) REFERENCES `Employee` (`id_employee`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

