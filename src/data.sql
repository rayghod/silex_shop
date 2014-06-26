CREATE DATABASE  IF NOT EXISTS `projekt_php` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `projekt_php`;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`login` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
`password` char(255) CHARACTER SET latin1 NOT NULL,
`firstname` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`lastname` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`phone_number` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`email` CHAR(125) CHARACTER SET latin1 NOT NULL,
`street` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`house_number` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`postal-code` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`city` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
`orders_count` INT UNSIGNED NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_bin;

INSERT INTO `users` (`id` , `login` , `password`, `firstname`, `lastname`, `phone_number` , `email` , `street` , `house_number` , `postal-code` , `city`, `orders_count`, `admin` ) 
VALUES (NULL ,  'admin', ENCRYPT(  'admin' ), 'Szymon', 'Witkowski', '693 970 777', 'rayghod@gmail.com', 'Litewska', '39', '30-014', 'Kraków', 0, 1);

INSERT INTO `users` (`id` , `login` , `password`, `firstname`, `lastname`, `phone_number` , `email` , `street` , `house_number` , `postal-code` , `city`, `orders_count`, `admin` ) 
VALUES (NULL ,  'test1', ENCRYPT(  'test1' ), 'Szymon', 'Witkowski', '693 970 777', 'rayghod@gmail.com', 'Litewska', '39', '30-014', 'Kraków', 0, 0);


DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`idCategory` INT UNSIGNED NOT NULL,
`idProducent` INT UNSIGNED,
`name` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
`price_netto` INT UNSIGNED NOT NULL,
`price_brutto` INT UNSIGNED NOT NULL,
`desc` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 

INSERT INTO `products` ( `id`, `idCategory`, `idProducent`, `name`, `price_netto`, `price_brutto`, `desc` ) 
VALUES (1, 1, 1, 'Psycho', 24, 34, 'aa'), (2, 1, 1, 'Psycho', 24, 34, 'aa'), (3, 1, 1, 'Psycho', 24, 34, 'aa'), (4, 1, 1, 'Psycho', 24, 34, 'aa'), (5, 2, 2, 'Nah', 24, 34, 'aa'), (6, 3, 3, 'Bah', 24, 34, 'aa');

DROP TABLE IF EXISTS `producents`;

CREATE TABLE `producents` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 

INSERT INTO `producents` ( `id`, `name` ) VALUES (NULL, 'Universal'), (NULL, 'Zysk i spk'), (NULL, 'Atlantic Records');

DROP TABLE IF EXISTS `categories`;

CREATE TABLE `categories` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 

INSERT INTO `categories` ( `id`, `name` ) VALUES (NULL, 'Filmy'), (NULL, 'Książki'), (NULL, 'Muzyka');

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`idClient` INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 