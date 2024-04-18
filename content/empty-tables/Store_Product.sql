USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Store_Product` (
  `UPC` varchar(12) NOT NULL,
  `UPC_prom` varchar(12) DEFAULT NULL,
  `id_product` int NOT NULL,
  `selling_price` decimal(13,4) NOT NULL,
  `products_number` int NOT NULL,
  `promotional_product` tinyint(1) NOT NULL DEFAULT '0'
); 


ALTER TABLE `Store_Product`
  ADD PRIMARY KEY (`UPC`),
  ADD KEY `id_product` (`id_product`),
  ADD KEY `UPC_prom` (`UPC_prom`);

ALTER TABLE `Store_Product`
  ADD CONSTRAINT `id_product` FOREIGN KEY (`id_product`) REFERENCES `Product` (`id_product`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `UPC_prom` FOREIGN KEY (`UPC_prom`) REFERENCES `Store_Product` (`UPC`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

