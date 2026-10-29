CREATE DATABASE IF NOT EXISTS `mail_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mail_db`;

CREATE TABLE IF NOT EXISTS `recipients` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) NOT NULL UNIQUE,
    `status` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `recipients` (`name`, `email`, `status`) VALUES
('Ngô Lê Quân', 'ngolequanit@gmail.com', 1),
('Nguyễn Văn A', 'nguyenvana@gmail.com', 1),
('Trần Thị B', 'tranthib@gmail.com', 1),
('Lê Văn C', 'levanc@gmail.com', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`);
