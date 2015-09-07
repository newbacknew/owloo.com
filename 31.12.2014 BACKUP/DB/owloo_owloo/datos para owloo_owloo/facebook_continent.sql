-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:04 PM
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
-- Table structure for table `facebook_continent`
--

CREATE TABLE IF NOT EXISTS `facebook_continent` (
  `id_continent` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id_continent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `facebook_continent`
--

INSERT INTO `facebook_continent` (`id_continent`, `code`, `nombre`, `name`) VALUES
(1, 'AF', 'África', 'Africa'),
(2, 'AS', 'Asia', 'Asia'),
(3, 'EU', 'Europa', 'Europe'),
(4, 'NA', 'América del Norte', 'North America'),
(5, 'OC', 'Oceanía', 'Oceania'),
(6, 'SA', 'América del Sur', 'South America');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
