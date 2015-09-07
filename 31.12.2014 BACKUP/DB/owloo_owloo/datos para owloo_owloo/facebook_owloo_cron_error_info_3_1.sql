-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:06 PM
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
-- Table structure for table `facebook_owloo_cron_error_info_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_owloo_cron_error_info_3_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `error` text CHARACTER SET utf8 NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62164 ;

--
-- Dumping data for table `facebook_owloo_cron_error_info_3_1`
--

INSERT INTO `facebook_owloo_cron_error_info_3_1` (`id`, `type`, `error`, `date`) VALUES
(1, 'country_3_1', 'Facebook error code - Pais - ID = 119 - Code: 2 - Mensaje: An unexpected error has occurred. Please retry your request later.', '2014-11-15'),
(2, 'country_3_1', 'Facebook error code - Pais - ID = 119 - Code: 803 - Mensaje: (#803) Some of the aliases you requested do not exist: act_', '2014-11-15'),
(3, 'country_3_1', 'Facebook error code - Pais - ID = 119 - Code: 803 - Mensaje: (#803) Some of the aliases you requested do not exist: act_', '2014-11-15'),
(4, 'country_3_1', 'Facebook error code - Pais - ID = 119 - Code: 803 - Mensaje: (#803) Some of the aliases you requested do not exist: act_', '2014-11-15'),
(5, 'country_3_1', 'Facebook error code - Pais - ID = 230 - Code: 1 - Mensaje: (#1) An unknown error occurred', '2014-11-18'),
(6, 'country_3_1', 'Facebook error code - Pais - ID = 230 - Code: 803 - Mensaje: (#803) Some of the aliases you requested do not exist: act_', '2014-11-18'),
(7, 'country_3_1', 'Facebook error code - City RELATIONSHIP - ID = 3573 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-19'),
(8, 'country_3_1', 'Facebook error code - City INTEREST - ID = 6075 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-21'),
(9, 'country_3_1', 'Facebook error code - City INTEREST - ID = 6086 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-21'),
(10, 'country_3_1', 'Facebook error code - City - ID = 705 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(11, 'country_3_1', 'Facebook error code - City - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(12, 'country_3_1', 'Facebook error code - City - ID = 666 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(13, 'country_3_1', 'Facebook error code - City - ID = 1013 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(14, 'country_3_1', 'Facebook error code - City - ID = 1022 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(15, 'country_3_1', 'Facebook error code - City - ID = 1437 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(16, 'country_3_1', 'Facebook error code - City - ID = 853 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(17, 'country_3_1', 'Facebook error code - City - ID = 1099 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(18, 'country_3_1', 'Facebook error code - City - ID = 1598 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(19, 'country_3_1', 'Facebook error code - City - ID = 2002 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(20, 'country_3_1', 'Facebook error code - City - ID = 2204 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(21, 'country_3_1', 'Facebook error code - City - ID = 2034 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(22, 'country_3_1', 'Facebook error code - City - ID = 2246 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(23, 'country_3_1', 'Facebook error code - City - ID = 1773 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(24, 'country_3_1', 'Facebook error code - City - ID = 2096 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(25, 'country_3_1', 'Facebook error code - City - ID = 2506 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(26, 'country_3_1', 'Facebook error code - City - ID = 2437 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(27, 'country_3_1', 'Facebook error code - City - ID = 3074 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(28, 'country_3_1', 'Facebook error code - City - ID = 2998 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(29, 'country_3_1', 'Facebook error code - City - ID = 3811 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(30, 'country_3_1', 'Facebook error code - City - ID = 3621 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(31, 'country_3_1', 'Facebook error code - City - ID = 3630 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(32, 'country_3_1', 'Facebook error code - City - ID = 3932 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(33, 'country_3_1', 'Facebook error code - City - ID = 3933 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(34, 'country_3_1', 'Facebook error code - City - ID = 3361 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(35, 'country_3_1', 'Facebook error code - City - ID = 3366 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(36, 'country_3_1', 'Facebook error code - City - ID = 3474 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(37, 'country_3_1', 'Facebook error code - City - ID = 3670 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(38, 'country_3_1', 'Facebook error code - City - ID = 4107 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(39, 'country_3_1', 'Facebook error code - City - ID = 4611 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(40, 'country_3_1', 'Facebook error code - City - ID = 4148 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(41, 'country_3_1', 'Facebook error code - City - ID = 4343 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(42, 'country_3_1', 'Facebook error code - City - ID = 4555 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(43, 'country_3_1', 'Facebook error code - City - ID = 5104 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(44, 'country_3_1', 'Facebook error code - City - ID = 5273 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(45, 'country_3_1', 'Facebook error code - City - ID = 5095 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(46, 'country_3_1', 'Facebook error code - City - ID = 4892 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(47, 'country_3_1', 'Facebook error code - City - ID = 5711 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(48, 'country_3_1', 'Facebook error code - City - ID = 5722 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(49, 'country_3_1', 'Facebook error code - City - ID = 6073 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(50, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(51, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(52, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(53, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(54, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(55, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23'),
(56, 'country_3_1', 'Facebook error code - City AGE - ID = 228 - Code: 1487108 - Mensaje: (#1487108) Ciudades no válidas: Ciudades no válidas', '2014-11-23');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
