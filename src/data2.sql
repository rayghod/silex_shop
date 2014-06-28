drop database projekt_php;

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
`postal_code` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`city` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`role` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_bin;

DROP TABLE IF EXISTS `users_roles`;

CREATE TABLE `users_roles` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`user_id` INT UNSIGNED NOT NULL,
`role_id` INT UNSIGNED NOT NULL
) ENGINE = INNODB CHARACTER SET utf8 COLLATE utf8_bin;

ALTER TABLE users_roles ADD CONSTRAINT FK_users_roles_1 FOREIGN KEY ( user_id ) REFERENCES users( id );
ALTER TABLE users_roles ADD CONSTRAINT FK_users_roles_2 FOREIGN KEY ( role_id ) REFERENCES roles( id );

INSERT INTO `roles` (`id`, `role`) VALUES ('1', 'ROLE_ADMIN');

INSERT INTO `roles` (`id`, `role`) VALUES ('2', 'ROLE_USER');

INSERT INTO `users` (`id`, `firstname`, `lastname`, `login`, `password`) VALUES
(1, 'Szymon', 'Witkowski', 'admin', 'Lev4gotN3ZSFGNsv6Us2InQ09NjH8sVba2WR76YAsRrn2iL9mLfdXCdOWNOtcX7cFzSQRZ9deafIwyjdd76P9A==');

INSERT INTO `users_roles` (`id`, `user_id`, `role_id`) VALUES (NULL, '1', '1');

INSERT INTO `users` (`id`, `firstname`, `lastname`, `login`, `password`) VALUES
(NULL, 'Michał', 'Bitka', 'rayghod','Lev4gotN3ZSFGNsv6Us2InQ09NjH8sVba2WR76YAsRrn2iL9mLfdXCdOWNOtcX7cFzSQRZ9deafIwyjdd76P9A==');

INSERT INTO `users_roles` (`id`, `user_id`, `role_id`) VALUES (NULL, '2', '2');

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`idCategory` INT UNSIGNED NOT NULL,
`idProducent` INT UNSIGNED,
`name` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
`price_netto` FLOAT NOT NULL,
`price_brutto` FLOAT UNSIGNED NOT NULL,
`desc` TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 

INSERT INTO `products` ( `id`, `idCategory`, `idProducent`, `name`, `price_netto`, `price_brutto`, `desc` ) 
VALUES (1, 1, 1, 'Psycho1', 24, 34, 'aa'), (2, 1, 1, 'Psycho2', 24, 34, 'aa'), (3, 1, 1, 'Psycho3', 24, 34, 'aa'), (4, 1, 1, 'Psycho4', 24, 34, 'aa'), (5, 2, 2, 'Nah', 24, 34, 'aa'), (6, 3, 3, 'Bah', 24, 34, 'aa');

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
`idUser` INT UNSIGNED NOT NULL,
`street` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`house_number` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`postal_code` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
`city` CHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
`closed` INT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 

INSERT INTO `orders` (`id`, `idUser`,`street`,`house_number`,`postal_code`,`city`, `closed`) VALUES (1, 1, 'Litewska', '39', '30-014', 'Kraków', 0);

INSERT INTO `orders` (`id`, `idUser`,`street`,`house_number`,`postal_code`,`city`, `closed`) VALUES (2, 2, 'Litewska', '39', '30-014', 'Kraków', 0);


CREATE TABLE `orders_products` (
`idOrder` INT UNSIGNED,
`idProduct` INT UNSIGNED
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin; 

INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,1);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,2);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,2);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,3);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,2);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,4);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (1,3);

INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (2,3);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (2,2);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (2,4);
INSERT INTO `orders_products` (`idOrder`, `idProduct`) VALUES (2,3);