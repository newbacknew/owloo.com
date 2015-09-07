-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:23 PM
-- Server version: 5.5.40-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `owloo_owloo`
--

-- --------------------------------------------------------

--
-- Table structure for table `facebook_relationship_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_relationship_3_1` (
  `id_relationship` int(11) NOT NULL AUTO_INCREMENT,
  `key_relationship` int(3) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_relationship`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `facebook_relationship_3_1`
--

INSERT INTO `facebook_relationship_3_1` (`id_relationship`, `key_relationship`, `nombre`, `active_fb_get_data`, `active`) VALUES
(1, 1, 'Soltero', 1, 1),
(2, 2, 'En una relación', 1, 1),
(3, 3, 'Casado', 1, 1),
(4, 4, 'Comprometido', 1, 1),
(5, 6, 'Sin especificar', 1, 1),
(6, 7, 'Unión civil', 1, 1),
(7, 8, 'Pareja de hecho', 1, 1),
(8, 9, 'Relación abierta', 1, 1),
(9, 10, 'Relación complicada', 1, 1),
(10, 11, 'Separado', 1, 1),
(11, 12, 'Divorciado', 1, 1),
(12, 13, 'Viudo', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
