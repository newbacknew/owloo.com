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
-- Table structure for table `facebook_age_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_age_3_1` (
  `id_age` int(11) NOT NULL AUTO_INCREMENT,
  `min` int(3) NOT NULL,
  `max` int(3) NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_age`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `facebook_age_3_1`
--

INSERT INTO `facebook_age_3_1` (`id_age`, `min`, `max`, `active_fb_get_data`, `active`) VALUES
(1, 13, 17, 1, 1),
(2, 18, 24, 1, 1),
(3, 25, 29, 1, 1),
(4, 30, 34, 1, 1),
(5, 35, 44, 1, 1),
(6, 45, 54, 1, 1),
(7, 55, 64, 1, 1),
(8, 65, 65, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
