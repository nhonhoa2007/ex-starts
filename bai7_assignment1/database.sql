-- Script tạo cơ sở dữ liệu và bảng cho Bài 7: Quản lý Thể loại Tin tức
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
('Giải trí', 11, 1, 'penguins.jpg'),
('Pháp luật', 99, 0, 'koala.jpg'),
('Văn Hóa', 44, 1, 'desert.jpg'),
('Xã hội', 4, 1, 'tulips.jpg')
ON DUPLICATE KEY UPDATE `ThuTu` = VALUES(`ThuTu`), `AnHien` = VALUES(`AnHien`), `icon` = VALUES(`icon`);
