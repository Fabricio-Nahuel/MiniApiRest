--Crear base de datos.
CREATE DATABASE [IF NOT EXISTS] miniapirest_DB
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

--Crear tabla Productos.
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(32) NOT NULL,
  `precio` decimal(10,0) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--Insertar registros en la tabla Productos.
INSERT INTO `productos` (`id`, `nombre`, `precio`) VALUES
(1, 'ASUS Zenfone 6', 87000),
(2, 'Google Pixel 4 XL', 105900),
(3, 'Honor 20', 18000),
(4, 'Honor View 20', 34999),
(5, 'Huawei Mate 20 Pro', 84690),
(6, 'Huawei Mate 30 Pro', 119999),
(7, 'Huawei P30 Pro', 108889),
(8, 'Huawei P Smart+ 2019', 25900),
(9, 'iPhone 11', 74999),
(10, 'LG G8 Thing', 60000),
(11, 'LG G8s', 68000),
(12, 'LG V50 Things', 55000),
(13, 'Motorola Moto G7 Plus', 26999),
(14, 'Motorola Moto G8 Plus', 31500),
(15, 'Motorola Moto Z4', 47000),
(16, 'Motorola One Action', 25999),
(17, 'Motorola One Macro', 23800),
(18, 'Motorola One Vision', 25999),
(19, 'Motorola One Zoom', 48999),
(20, 'Nokia 9 PureView', 66000),
(21, 'Nubia Z20', 71200),
(22, 'OnePlus 7T Pro', 78999),
(23, 'Samsung Galaxy A50', 24799),
(24, 'Samsung Galaxy Note 10 Plus', 129999),
(25, 'Samsung Galaxy S10 Plus', 74999),
(26, 'Sony Xperia XZ3', 76390),
(27, 'Sony Xperia Z5', 21600),
(28, 'OnePlus 7 Pro', 76895),
(29, 'OnePlus 7T Pro', 78999),
(30, 'Xiaomi Mi 9T Pro', 43200),
(31, 'Xiaomi Mi A3', 17500),
(32, 'Xiaomi Mi Mix 3', 43599),
(33, 'Xiaomi Redmi 8A', 15199),
(34, 'Xiaomi Redmi Note 8 Pro', 38699);
