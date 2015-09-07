-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:11 PM
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
-- Table structure for table `facebook_record_city_comportamiento_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_city_comportamiento_3_1` (
  `id_city` int(5) NOT NULL,
  `id_comportamiento` int(4) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_city`,`id_comportamiento`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `facebook_record_city_comportamiento_3_1`
--

INSERT INTO `facebook_record_city_comportamiento_3_1` (`id_city`, `id_comportamiento`, `total_user`, `date`) VALUES
(1, 63, 92000, '2014-09-24'),
(1, 63, 94000, '2014-09-25'),
(1, 63, 94000, '2014-09-26'),
(1, 63, 94000, '2014-09-27'),
(1, 63, 94000, '2014-09-28'),
(1, 63, 94000, '2014-09-29'),
(1, 63, 94000, '2014-09-30'),
(1, 63, 92000, '2014-10-01'),
(1, 63, 92000, '2014-10-09'),
(1, 63, 92000, '2014-10-10'),
(1, 63, 92000, '2014-10-11'),
(1, 63, 92000, '2014-10-12'),
(1, 63, 92000, '2014-10-13'),
(1, 63, 90000, '2014-10-14'),
(1, 63, 90000, '2014-10-15'),
(1, 63, 90000, '2014-10-16'),
(1, 63, 90000, '2014-10-17'),
(1, 63, 92000, '2014-10-18'),
(1, 63, 92000, '2014-10-19'),
(1, 63, 92000, '2014-10-20'),
(1, 63, 92000, '2014-10-21'),
(1, 63, 92000, '2014-10-22'),
(1, 63, 92000, '2014-10-23'),
(1, 63, 88000, '2014-10-24'),
(1, 63, 88000, '2014-10-25'),
(1, 63, 88000, '2014-10-26'),
(1, 63, 88000, '2014-10-27'),
(1, 63, 88000, '2014-10-28'),
(1, 63, 88000, '2014-10-29'),
(1, 63, 60000, '2014-10-30'),
(1, 63, 60000, '2014-10-31'),
(1, 63, 60000, '2014-11-01'),
(1, 63, 60000, '2014-11-02'),
(1, 63, 60000, '2014-11-03'),
(1, 63, 60000, '2014-11-04'),
(1, 63, 60000, '2014-11-05'),
(1, 63, 56000, '2014-11-06'),
(1, 63, 56000, '2014-11-07'),
(1, 63, 56000, '2014-11-08'),
(1, 63, 56000, '2014-11-09'),
(1, 63, 56000, '2014-11-10'),
(1, 63, 56000, '2014-11-11'),
(1, 63, 56000, '2014-11-12'),
(1, 63, 56000, '2014-11-13'),
(1, 63, 56000, '2014-11-14'),
(1, 63, 56000, '2014-11-15'),
(1, 63, 56000, '2014-11-16'),
(1, 63, 56000, '2014-11-17'),
(1, 63, 56000, '2014-11-18'),
(1, 63, 56000, '2014-11-19'),
(1, 63, 54000, '2014-11-20'),
(1, 63, 54000, '2014-11-21'),
(1, 63, 54000, '2014-11-22'),
(1, 63, 54000, '2014-11-23'),
(1, 63, 54000, '2014-11-24'),
(1, 63, 54000, '2014-11-25'),
(1, 63, 54000, '2014-11-26'),
(1, 63, 52000, '2014-11-27'),
(1, 63, 52000, '2014-11-28'),
(1, 63, 52000, '2014-11-29'),
(1, 63, 52000, '2014-11-30'),
(1, 63, 52000, '2014-12-01'),
(1, 63, 52000, '2014-12-02'),
(1, 63, 52000, '2014-12-03'),
(1, 63, 52000, '2014-12-04'),
(1, 63, 52000, '2014-12-05'),
(1, 63, 52000, '2014-12-06'),
(1, 63, 52000, '2014-12-07'),
(1, 63, 52000, '2014-12-08'),
(1, 63, 52000, '2014-12-09'),
(1, 63, 50000, '2014-12-10'),
(1, 63, 50000, '2014-12-11'),
(1, 63, 50000, '2014-12-12'),
(1, 63, 50000, '2014-12-13'),
(1, 63, 50000, '2014-12-14'),
(1, 63, 50000, '2014-12-15'),
(1, 63, 52000, '2014-12-16'),
(1, 63, 52000, '2014-12-17'),
(1, 63, 52000, '2014-12-18'),
(1, 63, 50000, '2014-12-19'),
(1, 63, 50000, '2014-12-20'),
(1, 63, 50000, '2014-12-21'),
(1, 63, 50000, '2014-12-22'),
(1, 63, 50000, '2014-12-23'),
(1, 63, 50000, '2014-12-24'),
(1, 63, 50000, '2014-12-25'),
(1, 63, 50000, '2014-12-26'),
(1, 63, 50000, '2014-12-27'),
(1, 63, 50000, '2014-12-28'),
(1, 63, 50000, '2014-12-29'),
(1, 63, 50000, '2014-12-30'),
(1, 63, 50000, '2014-12-31'),
(1, 92, 2600000, '2014-09-24'),
(1, 92, 2600000, '2014-09-25'),
(1, 92, 2800000, '2014-09-26'),
(1, 92, 2800000, '2014-09-27'),
(1, 92, 2800000, '2014-09-28'),
(1, 92, 2800000, '2014-09-29'),
(1, 92, 2800000, '2014-09-30'),
(1, 92, 2800000, '2014-10-01'),
(1, 92, 2600000, '2014-10-09'),
(1, 92, 2600000, '2014-10-10'),
(1, 92, 2600000, '2014-10-11'),
(1, 92, 2600000, '2014-10-12'),
(1, 92, 2600000, '2014-10-13'),
(1, 92, 2600000, '2014-10-14'),
(1, 92, 2600000, '2014-10-15'),
(1, 92, 2600000, '2014-10-16'),
(1, 92, 2600000, '2014-10-17'),
(1, 92, 2600000, '2014-10-18'),
(1, 92, 2600000, '2014-10-19'),
(1, 92, 2600000, '2014-10-20'),
(1, 92, 2600000, '2014-10-21'),
(1, 92, 2600000, '2014-10-22'),
(1, 92, 2600000, '2014-10-23'),
(1, 92, 2600000, '2014-10-24'),
(1, 92, 2600000, '2014-10-25'),
(1, 92, 2600000, '2014-10-26'),
(1, 92, 2600000, '2014-10-27'),
(1, 92, 2600000, '2014-10-28'),
(1, 92, 2800000, '2014-10-29'),
(1, 92, 1620000, '2014-10-30'),
(1, 92, 1620000, '2014-10-31'),
(1, 92, 1620000, '2014-11-01'),
(1, 92, 1620000, '2014-11-02'),
(1, 92, 1620000, '2014-11-03'),
(1, 92, 1620000, '2014-11-04'),
(1, 92, 1620000, '2014-11-05'),
(1, 92, 1600000, '2014-11-06'),
(1, 92, 1600000, '2014-11-07'),
(1, 92, 1620000, '2014-11-08'),
(1, 92, 1620000, '2014-11-09'),
(1, 92, 1620000, '2014-11-10'),
(1, 92, 1600000, '2014-11-11'),
(1, 92, 1600000, '2014-11-12'),
(1, 92, 1600000, '2014-11-13'),
(1, 92, 1620000, '2014-11-14'),
(1, 92, 1620000, '2014-11-15'),
(1, 92, 1620000, '2014-11-16'),
(1, 92, 1620000, '2014-11-17'),
(1, 92, 1620000, '2014-11-18'),
(1, 92, 1620000, '2014-11-19'),
(1, 92, 1620000, '2014-11-20'),
(1, 92, 1600000, '2014-11-21'),
(1, 92, 1640000, '2014-11-22'),
(1, 92, 1620000, '2014-11-23'),
(1, 92, 1620000, '2014-11-24'),
(1, 92, 1620000, '2014-11-25'),
(1, 92, 1620000, '2014-11-26'),
(1, 92, 1620000, '2014-11-27'),
(1, 92, 1620000, '2014-11-28'),
(1, 92, 1600000, '2014-11-29'),
(1, 92, 1600000, '2014-11-30'),
(1, 92, 1600000, '2014-12-01'),
(1, 92, 1640000, '2014-12-02'),
(1, 92, 1620000, '2014-12-03'),
(1, 92, 1620000, '2014-12-04'),
(1, 92, 1620000, '2014-12-05'),
(1, 92, 1640000, '2014-12-06'),
(1, 92, 1640000, '2014-12-07'),
(1, 92, 1640000, '2014-12-08'),
(1, 92, 1640000, '2014-12-09'),
(1, 92, 1640000, '2014-12-10'),
(1, 92, 1620000, '2014-12-11'),
(1, 92, 1620000, '2014-12-12'),
(1, 92, 1620000, '2014-12-13'),
(1, 92, 1620000, '2014-12-14'),
(1, 92, 1620000, '2014-12-15'),
(1, 92, 1640000, '2014-12-16'),
(1, 92, 1640000, '2014-12-17'),
(1, 92, 1640000, '2014-12-18'),
(1, 92, 1640000, '2014-12-19'),
(1, 92, 1620000, '2014-12-20'),
(1, 92, 1620000, '2014-12-21'),
(1, 92, 1620000, '2014-12-22'),
(1, 92, 1620000, '2014-12-23'),
(1, 92, 1660000, '2014-12-24'),
(1, 92, 1660000, '2014-12-25'),
(1, 92, 1660000, '2014-12-26'),
(1, 92, 1640000, '2014-12-27'),
(1, 92, 1640000, '2014-12-28'),
(1, 92, 1640000, '2014-12-29'),
(1, 92, 1640000, '2014-12-30'),
(1, 92, 1620000, '2014-12-31'),
(1, 93, 92000, '2014-09-24'),
(1, 93, 92000, '2014-09-25'),
(1, 93, 92000, '2014-09-26'),
(1, 93, 92000, '2014-09-27'),
(1, 93, 92000, '2014-09-28'),
(1, 93, 92000, '2014-09-29'),
(1, 93, 92000, '2014-09-30'),
(1, 93, 92000, '2014-10-01'),
(1, 93, 96000, '2014-10-09'),
(1, 93, 96000, '2014-10-10'),
(1, 93, 96000, '2014-10-11'),
(1, 93, 96000, '2014-10-12'),
(1, 93, 96000, '2014-10-13'),
(1, 93, 96000, '2014-10-14'),
(1, 93, 96000, '2014-10-15'),
(1, 93, 96000, '2014-10-16'),
(1, 93, 96000, '2014-10-17'),
(1, 93, 96000, '2014-10-18'),
(1, 93, 94000, '2014-10-19'),
(1, 93, 94000, '2014-10-20'),
(1, 93, 94000, '2014-10-21'),
(1, 93, 94000, '2014-10-22'),
(1, 93, 94000, '2014-10-23'),
(1, 93, 94000, '2014-10-24'),
(1, 93, 94000, '2014-10-25'),
(1, 93, 94000, '2014-10-26'),
(1, 93, 94000, '2014-10-27'),
(1, 93, 94000, '2014-10-28'),
(1, 93, 94000, '2014-10-29'),
(1, 93, 58000, '2014-10-30'),
(1, 93, 58000, '2014-10-31'),
(1, 93, 58000, '2014-11-01'),
(1, 93, 58000, '2014-11-02'),
(1, 93, 58000, '2014-11-03'),
(1, 93, 58000, '2014-11-04'),
(1, 93, 58000, '2014-11-05'),
(1, 93, 60000, '2014-11-06'),
(1, 93, 60000, '2014-11-07'),
(1, 93, 60000, '2014-11-08'),
(1, 93, 60000, '2014-11-09'),
(1, 93, 60000, '2014-11-10'),
(1, 93, 60000, '2014-11-11'),
(1, 93, 58000, '2014-11-12'),
(1, 93, 58000, '2014-11-13'),
(1, 93, 58000, '2014-11-14'),
(1, 93, 58000, '2014-11-15'),
(1, 93, 58000, '2014-11-16'),
(1, 93, 58000, '2014-11-17'),
(1, 93, 58000, '2014-11-18'),
(1, 93, 58000, '2014-11-19'),
(1, 93, 58000, '2014-11-20'),
(1, 93, 56000, '2014-11-21'),
(1, 93, 56000, '2014-11-22'),
(1, 93, 56000, '2014-11-23'),
(1, 93, 56000, '2014-11-24'),
(1, 93, 56000, '2014-11-25'),
(1, 93, 56000, '2014-11-26'),
(1, 93, 56000, '2014-11-27'),
(1, 93, 56000, '2014-11-28'),
(1, 93, 56000, '2014-11-29'),
(1, 93, 56000, '2014-11-30'),
(1, 93, 56000, '2014-12-01'),
(1, 93, 56000, '2014-12-02'),
(1, 93, 56000, '2014-12-03'),
(1, 93, 56000, '2014-12-04'),
(1, 93, 56000, '2014-12-05'),
(1, 93, 56000, '2014-12-06'),
(1, 93, 56000, '2014-12-07'),
(1, 93, 56000, '2014-12-08'),
(1, 93, 56000, '2014-12-09'),
(1, 93, 54000, '2014-12-10'),
(1, 93, 54000, '2014-12-11'),
(1, 93, 54000, '2014-12-12'),
(1, 93, 54000, '2014-12-13'),
(1, 93, 54000, '2014-12-14'),
(1, 93, 54000, '2014-12-15'),
(1, 93, 56000, '2014-12-16'),
(1, 93, 56000, '2014-12-17'),
(1, 93, 56000, '2014-12-18'),
(1, 93, 56000, '2014-12-19'),
(1, 93, 56000, '2014-12-20'),
(1, 93, 56000, '2014-12-21'),
(1, 93, 56000, '2014-12-22'),
(1, 93, 56000, '2014-12-23'),
(1, 93, 56000, '2014-12-24'),
(1, 93, 56000, '2014-12-25'),
(1, 93, 56000, '2014-12-26'),
(1, 93, 56000, '2014-12-27'),
(1, 93, 56000, '2014-12-28');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;