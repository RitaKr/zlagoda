USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Product` (
  `id_product` int NOT NULL,
  `category_number` int NOT NULL,
  `product_name` varchar(50) NOT NULL,
  `producer` varchar(50) NOT NULL,
  `characteristics` varchar(100) NOT NULL
); 

ALTER TABLE `Product`
  ADD PRIMARY KEY (`id_product`),
  ADD KEY `category_number` (`category_number`);

ALTER TABLE `Product`
  MODIFY `id_product` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

ALTER TABLE `Product`
  ADD CONSTRAINT `category_number` FOREIGN KEY (`category_number`) REFERENCES `Category` (`category_number`) ON DELETE NO ACTION ON UPDATE CASCADE;
COMMIT;
