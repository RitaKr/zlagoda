USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Category` (
  `category_number` int NOT NULL,
  `category_name` varchar(50) NOT NULL
); 

ALTER TABLE `Category`
  ADD PRIMARY KEY (`category_number`);

ALTER TABLE `Category`
  MODIFY `category_number` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
COMMIT;

