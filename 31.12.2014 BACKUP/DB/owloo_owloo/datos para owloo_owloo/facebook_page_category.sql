-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:08 PM
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
-- Table structure for table `facebook_page_category`
--

CREATE TABLE IF NOT EXISTS `facebook_page_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `facebook_page_category`
--

INSERT INTO `facebook_page_category` (`id_category`, `category`) VALUES
(1, 'internet'),
(2, 'sport'),
(3, 'media'),
(4, 'public-figure'),
(5, 'entertainment'),
(6, 'brands'),
(7, 'community'),
(8, 'local-business'),
(9, 'companies'),
(10, 'politics'),
(11, 'music'),
(12, 'places');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
