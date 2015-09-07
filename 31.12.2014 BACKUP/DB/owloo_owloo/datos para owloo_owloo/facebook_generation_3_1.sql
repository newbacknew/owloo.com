-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:05 PM
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
-- Table structure for table `facebook_generation_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_generation_3_1` (
  `id_generation` int(11) NOT NULL AUTO_INCREMENT,
  `key_generation` bigint(20) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_generation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `facebook_generation_3_1`
--

INSERT INTO `facebook_generation_3_1` (`id_generation`, `key_generation`, `nombre`, `description`, `active_fb_get_data`, `active`) VALUES
(1, 6002714401172, 'Baby boomers', 'Personas nacidas durante el baby boom', 1, 1),
(2, 6016645577583, 'Generation X', 'People who were born between 1961 and 1981', 1, 1),
(3, 6016645612183, 'Millennials', 'People who were born between 1982 and 2004', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
