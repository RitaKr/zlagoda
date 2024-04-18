USE zlagoda;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+03:00";

CREATE TABLE IF NOT EXISTS `Employee` (
  `id_employee` varchar(10) NOT NULL,
  `empl_surname` varchar(50) NOT NULL,
  `empl_name` varchar(50) NOT NULL,
  `empl_patronymic` varchar(50) DEFAULT NULL,
  `empl_role` varchar(10) NOT NULL DEFAULT 'cashier',
  `salary` decimal(13,4) NOT NULL,
  `date_of_birth` date NOT NULL,
  `date_of_start` date NOT NULL,
  `phone_number` varchar(13) NOT NULL,
  `city` varchar(50) NOT NULL,
  `street` varchar(50) NOT NULL,
  `zip_code` varchar(9) NOT NULL
); 

INSERT INTO `Employee` (`id_employee`, `empl_surname`, `empl_name`, `empl_patronymic`, `empl_role`, `salary`, `date_of_birth`, `date_of_start`, `phone_number`, `city`, `street`, `zip_code`) VALUES
('0000000000', 'Петренко', 'Петро', 'Петрович', 'manager', 41200.0000, '1995-03-03', '2023-02-20', '+380123456789', 'Київ', 'вул. Хрещатик, 1', '02000'),
('0000000001', 'Іваненко', 'Іван', NULL, 'cashier', 12000.0000, '1988-05-05', '2024-01-20', '+380661234567', 'Київ', 'вул. Сагайдачного, 2', '02000');

ALTER TABLE `Employee`
  ADD PRIMARY KEY (`id_employee`);
COMMIT;