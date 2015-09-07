-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:27 PM
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
-- Table structure for table `instagram_media_tags`
--

CREATE TABLE IF NOT EXISTS `instagram_media_tags` (
  `id` bigint(25) NOT NULL AUTO_INCREMENT,
  `id_profile` int(11) NOT NULL,
  `id_media` bigint(25) NOT NULL,
  `tag` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_profile` (`id_profile`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=263255 ;

--
-- Dumping data for table `instagram_media_tags`
--

INSERT INTO `instagram_media_tags` (`id`, `id_profile`, `id_media`, `tag`) VALUES
(1, 1, 1, 'vox'),
(2, 1, 2, 'llamadasinternacionales'),
(3, 1, 2, 'vox'),
(4, 1, 3, 'findeañovox'),
(5, 1, 4, 'frases'),
(6, 1, 4, 'instaquotes'),
(7, 1, 5, 'redessociales'),
(8, 1, 5, 'súperpaquetes'),
(9, 1, 6, 'súperpaquetes'),
(10, 1, 6, 'tiendavox'),
(11, 1, 7, 'instamoments'),
(12, 1, 7, 'momentosvox'),
(13, 1, 8, 'súperpaquetes'),
(14, 1, 8, 'vox'),
(15, 1, 8, 'redessociales'),
(16, 1, 9, 'instaquotes'),
(17, 1, 10, 'súperpaquetes'),
(18, 1, 10, 'tiendavox'),
(19, 1, 11, 'redessociales'),
(20, 1, 11, 'súperpaquetes'),
(21, 1, 12, 'vox'),
(22, 1, 13, 'lunes'),
(23, 1, 13, 'motivaciones'),
(24, 1, 14, 'súperpaquetes'),
(25, 1, 14, 'vox'),
(26, 1, 15, 'llamadasinternacionales'),
(27, 1, 15, 'vox'),
(28, 1, 16, 'llamadasinternacionales'),
(29, 1, 17, 'súperpaquetes'),
(30, 1, 18, 'vox');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
