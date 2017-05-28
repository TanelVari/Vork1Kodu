<?php

/*

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `tvari_kodu_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `password` varchar(40) DEFAULT NULL,
  `role` enum('owner','guest') NOT NULL DEFAULT 'guest',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `tvari_kodu_users` (`id`, `name`, `password`, `role`) VALUES
(1, 'Tanel', '12e4f7acd791422ae34c814d40cb0930dddb1221', 'owner'),
(2, 'Kätlin', NULL, 'guest'),
(3, 'Toomas', NULL, 'guest'),
(4, 'Edmund, vana jobu', NULL, 'guest');

CREATE TABLE IF NOT EXISTS `tvari_kodu_systems` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

INSERT INTO `tvari_kodu_systems` (`id`, `description`) VALUES
(1, 'Autorid tähestikuliselt'),
(2, 'Pilla-palla'),
(3, 'Värvi järgi');

CREATE TABLE IF NOT EXISTS `tvari_kodu_shelves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bookcase` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `shelf_nr` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

INSERT INTO `tvari_kodu_shelves` (`id`, `bookcase`, `category`, `shelf_nr`) VALUES
(7, 1, 5, 1),
(8, 1, 8, 1),
(9, 3, 3, 5),
(10, 2, 7, 3),
(11, 4, 4, 1),
(12, 4, 4, 2);

CREATE TABLE IF NOT EXISTS `tvari_kodu_rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

INSERT INTO `tvari_kodu_rooms` (`id`, `name`) VALUES
(2, 'Arvutituba'),
(1, 'Elutuba'),
(3, 'Lastetuba');

CREATE TABLE IF NOT EXISTS `tvari_kodu_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `system` int(11) NOT NULL,
  `color_id` varchar(7) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

INSERT INTO `tvari_kodu_categories` (`id`, `category`, `system`, `color_id`) VALUES
(1, 'Krimkad', 1, '#dce2ed'),
(3, 'Eestikeelne ilukirjandus', 1, '#ebe7d0'),
(4, 'Keskmisele ja vanemale koolieale', 2, '#bcf2ff'),
(7, 'Sõnastikud', 3, '#c7ffbd'),
(8, 'Tõlkekirjandus', 1, '#f2d6ff');

CREATE TABLE IF NOT EXISTS `tvari_kodu_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `year` int(4) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `isbn` bigint(13) DEFAULT NULL,
  `category` int(11) NOT NULL,
  `rating` tinyint(4) DEFAULT NULL,
  `comment` text,
  `borrower` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

INSERT INTO `tvari_kodu_books` (`id`, `title`, `author`, `year`, `cover`, `isbn`, `category`, `rating`, `comment`, `borrower`, `borrow_date`) VALUES
(9, 'Tõde ja õigus I', 'Anton H. Tammsaare', 2003, 'covers/todejaoigusi-1700.jpg', 9789985208724, 3, 3, 'Eepiline', 2, '2017-05-28'),
(11, 'Tõde ja õigus III', 'Anton H. Tamsaare', 1968, 'covers/todejaoigusiii-1968.jpg', NULL, 3, NULL, NULL, NULL, NULL),
(12, 'Vee peal', 'Olavi Ruitlane', 2015, 'covers/veepeal-2015.jpg', 9789949384693, 3, 5, NULL, NULL, NULL),
(13, 'Rehepapp', 'Andrus Kivirähk', 2000, 'covers/rehepapp-2000.jpg', 2220000008637, 3, 5, NULL, NULL, NULL),
(14, 'Humoorikad jutustused', 'Anton Tšehhov', 2007, 'covers/humoorikadjutustused-2007.jpg', 2220000006931, 8, 4, NULL, NULL, NULL),
(15, 'Tšempionide eine', 'Kurt Vonnegut', 1978, 'covers/tsempionideeine-1978.jpg', 2220000003047, 8, 5, NULL, NULL, NULL),
(16, 'Eesti etümoloogiasõnaraamat', ' Iris Metsmägi, Meeli Sedrik, Sven-Eerik Soosaar', 2012, 'covers/eestietumoloogiasonaraamat-2012.jpg', 9789985794784, 7, NULL, NULL, NULL, NULL),
(17, 'Lõhutud vaas', 'Rex Stout', 2014, 'covers/9789949491476jpg-2014.jpg', 9789949491476, 1, 3, 'Mnjh', 4, '2017-05-28'),
(18, 'Poirot’ viimased juhtumid', 'Agatha Christie', 2014, 'covers/9789985331477jpg-2014.jpg', 9789985331477, 1, NULL, NULL, NULL, NULL),
(19, 'Kaka ja kevad', 'Andrus Kivirähk', 2009, 'covers/kakajakevad-2009.jpg', 9789985319727, 4, 5, NULL, NULL, NULL);

CREATE TABLE IF NOT EXISTS `tvari_kodu_bookcases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `room` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

INSERT INTO `tvari_kodu_bookcases` (`id`, `description`, `room`) VALUES
(1, 'Riiul veranda ukse juures', 1),
(2, 'Arvutitoa raamaturiiul', 2),
(3, 'Riiul teleka kõrval', 1),
(4, 'Riiul uksest paremal', 3);

*/

?>
