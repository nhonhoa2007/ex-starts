
CREATE DATABASE IF NOT EXISTS `tintuc` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tintuc`;

CREATE TABLE IF NOT EXISTS `theloai` (
  `idTL` INT(11) NOT NULL AUTO_INCREMENT,
  `TenTL` VARCHAR(255) NOT NULL UNIQUE,
  `ThuTu` INT(11) DEFAULT 0,
  `AnHien` TINYINT(1) DEFAULT 1,
  `icon` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`idTL`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `theloai` (`TenTL`, `ThuTu`, `AnHien`, `icon`) VALUES
('Giải trí', 1, 1, ''),
('Pháp luật', 2, 0, ''),
('Văn Hóa', 3, 1, ''),
('Xã hội', 4, 1, '')
ON DUPLICATE KEY UPDATE `ThuTu` = VALUES(`ThuTu`), `AnHien` = VALUES(`AnHien`);

