USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Sale` (
  `UPC` varchar(12) NOT NULL,
  `bill_number` varchar(10) NOT NULL,
  `product_number` int NOT NULL,
  `selling_price` decimal(13,4) NOT NULL
);


ALTER TABLE `Sale`
  ADD PRIMARY KEY (`UPC`,`bill_number`),
  ADD KEY `bill_number` (`bill_number`);

ALTER TABLE `Sale`
  ADD CONSTRAINT `bill_number` FOREIGN KEY (`bill_number`) REFERENCES `Bill` (`bill_number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UPC` FOREIGN KEY (`UPC`) REFERENCES `Store_Product` (`UPC`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;

