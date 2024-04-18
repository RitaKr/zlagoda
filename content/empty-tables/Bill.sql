USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Bill` (
  `bill_number` varchar(10) NOT NULL,
  `id_employee_bill` varchar(10)  NOT NULL,
  `card_number` varchar(13) DEFAULT NULL,
  `print_date` datetime NOT NULL,
  `sum_total` decimal(13,4) NOT NULL,
  `vat` decimal(13,4) NOT NULL
);

ALTER TABLE `Bill`
  ADD PRIMARY KEY (`bill_number`),
  ADD KEY `card_number` (`card_number`),
  ADD KEY `id_employee` (`id_employee_bill`);

ALTER TABLE `Bill`
  ADD CONSTRAINT `card_number` FOREIGN KEY (`card_number`) REFERENCES `Customer_Card` (`card_number`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `id_employee_bill` FOREIGN KEY (`id_employee_bill`) REFERENCES `Employee` (`id_employee`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;
