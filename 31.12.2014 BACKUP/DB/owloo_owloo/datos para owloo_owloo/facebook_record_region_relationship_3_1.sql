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
-- Table structure for table `facebook_record_region_relationship_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_region_relationship_3_1` (
  `id_region` int(5) NOT NULL,
  `id_relationship` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_region`,`id_relationship`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `facebook_record_region_relationship_3_1`
--

INSERT INTO `facebook_record_region_relationship_3_1` (`id_region`, `id_relationship`, `total_user`, `date`) VALUES
(265, 12, 3200, '2014-12-19'),
(265, 12, 3200, '2014-12-20'),
(265, 12, 3200, '2014-12-21'),
(265, 12, 3200, '2014-12-22'),
(265, 12, 3200, '2014-12-23'),
(265, 12, 3200, '2014-12-24'),
(265, 12, 3200, '2014-12-25'),
(265, 12, 3200, '2014-12-26'),
(265, 12, 3200, '2014-12-27'),
(265, 12, 3200, '2014-12-28'),
(265, 12, 3200, '2014-12-29'),
(265, 12, 3000, '2014-12-30'),
(265, 12, 3000, '2014-12-31');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
