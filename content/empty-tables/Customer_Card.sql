USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Customer_Card` (
  `card_number` varchar(13) NOT NULL,
  `cust_surname` varchar(50) NOT NULL,
  `cust_name` varchar(50) NOT NULL,
  `cust_patronymic` varchar(50) DEFAULT NULL,
  `phone_number` varchar(13) NOT NULL,
  `city` varchar(50) DEFAULT NULL,
  `street` varchar(50) DEFAULT NULL,
  `zip_code` varchar(9) DEFAULT NULL,
  `percent` int NOT NULL
);

ALTER TABLE `Customer_Card`
  ADD PRIMARY KEY (`card_number`);
COMMIT;
