-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:28 PM
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
-- Table structure for table `record_city`
--

CREATE TABLE IF NOT EXISTS `record_city` (
  `id_historial_city` int(11) NOT NULL AUTO_INCREMENT,
  `id_city` bigint(11) NOT NULL,
  `date` date NOT NULL,
  `total_user` bigint(11) NOT NULL,
  `total_female` bigint(11) NOT NULL,
  `total_male` bigint(11) NOT NULL,
  PRIMARY KEY (`id_historial_city`),
  KEY `date` (`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1852547 ;

--
-- Dumping data for table `record_city`
--

INSERT INTO `record_city` (`id_historial_city`, `id_city`, `date`, `total_user`, `total_female`, `total_male`) VALUES
(1852539, 8193, '2014-09-29', 80000, 42000, 36000),
(1852540, 8194, '2014-09-29', 144000, 78000, 64000),
(1852541, 8195, '2014-09-29', 680000, 380000, 300000),
(1852542, 8196, '2014-09-29', 124000, 68000, 56000),
(1852543, 8197, '2014-09-29', 660000, 360000, 300000),
(1852544, 8198, '2014-09-29', 660000, 360000, 300000),
(1852545, 8199, '2014-09-29', 680000, 360000, 300000),
(1852546, 8200, '2014-09-29', 112000, 60000, 50000);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
