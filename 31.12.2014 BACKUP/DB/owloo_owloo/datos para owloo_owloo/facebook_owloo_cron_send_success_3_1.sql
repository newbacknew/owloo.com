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
-- Table structure for table `facebook_owloo_cron_send_success_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_owloo_cron_send_success_3_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=137 ;

--
-- Dumping data for table `facebook_owloo_cron_send_success_3_1`
--

INSERT INTO `facebook_owloo_cron_send_success_3_1` (`id`, `type`, `date`) VALUES
(1, 'country_3_1', '2014-09-23 08:58:58'),
(3, 'country_language_3_1', '2014-09-23 11:34:31'),
(4, 'country_relationship_3_1', '2014-09-23 15:31:28'),
(6, 'country_generation_3_1', '2014-09-23 15:57:35'),
(8, 'country_interest_3_1', '2014-09-23 16:55:36'),
(10, 'country_comportamiento_3_1', '2014-09-23 18:06:14'),
(11, 'country_3_1', '2014-09-24 09:00:14'),
(12, 'country_age_3_1', '2014-09-24 09:31:17'),
(13, 'country_language_3_1', '2014-09-24 09:45:36'),
(14, 'country_relationship_3_1', '2014-09-24 10:06:41'),
(15, 'country_generation_3_1', '2014-09-24 10:09:15'),
(16, 'country_interest_3_1', '2014-09-24 10:36:22'),
(17, 'country_comportamiento_3_1', '2014-09-24 10:53:22'),
(20, 'city_3_1', '2014-09-24 11:45:14'),
(22, 'city_age_3_1', '2014-09-24 12:44:21'),
(25, 'city_relationship_3_1', '2014-09-24 15:29:29'),
(28, 'city_interest_3_1', '2014-09-24 16:42:16'),
(31, 'city_comportamiento_3_1', '2014-09-24 17:43:05'),
(33, 'country_3_1', '2014-09-25 10:30:48'),
(35, 'country_age_3_1', '2014-09-25 10:33:56'),
(37, 'country_language_3_1', '2014-09-25 10:39:49'),
(39, 'country_relationship_3_1', '2014-09-25 10:46:17'),
(41, 'country_generation_3_1', '2014-09-25 10:48:15'),
(43, 'country_interest_3_1', '2014-09-25 11:17:42'),
(46, 'country_comportamiento_3_1', '2014-09-25 12:12:22'),
(48, 'city_3_1', '2014-09-25 12:17:15'),
(50, 'city_age_3_1', '2014-09-25 12:54:43'),
(52, 'city_relationship_3_1', '2014-09-25 13:19:15'),
(54, 'city_interest_3_1', '2014-09-25 14:24:47'),
(55, 'city_comportamiento_3_1', '2014-09-25 14:43:28'),
(57, 'region_3_1', '2014-09-25 15:33:14'),
(59, 'region_age_3_1', '2014-09-25 15:38:04'),
(60, 'region_relationship_3_1', '2014-09-25 16:52:55'),
(61, 'region_interest_3_1', '2014-09-25 17:28:24'),
(62, 'region_comportamiento_3_1', '2014-09-25 17:30:07'),
(63, 'country_3_1', '2014-09-26 08:38:41'),
(64, 'country_age_3_1', '2014-09-26 08:41:08'),
(65, 'country_language_3_1', '2014-09-26 08:48:47'),
(66, 'country_relationship_3_1', '2014-09-26 08:57:16'),
(67, 'country_generation_3_1', '2014-09-26 09:07:45'),
(68, 'country_comportamiento_3_1', '2014-09-26 12:07:58'),
(69, 'country_interest_3_1', '2014-09-26 12:42:01'),
(70, 'city_3_1', '2014-09-26 12:52:12'),
(71, 'city_age_3_1', '2014-09-26 16:03:16'),
(72, 'city_relationship_3_1', '2014-09-26 17:05:06'),
(73, 'city_interest_3_1', '2014-09-26 17:17:05'),
(74, 'city_comportamiento_3_1', '2014-09-26 17:30:05'),
(75, 'region_3_1', '2014-09-26 17:32:23'),
(76, 'region_age_3_1', '2014-09-26 17:34:58'),
(77, 'region_relationship_3_1', '2014-09-26 17:42:15'),
(78, 'region_interest_3_1', '2014-09-26 17:43:23'),
(79, 'region_comportamiento_3_1', '2014-09-26 17:44:07'),
(80, 'country_3_1', '2014-09-27 12:40:23'),
(81, 'country_age_3_1', '2014-09-27 12:45:03'),
(82, 'country_language_3_1', '2014-09-27 13:35:45'),
(83, 'country_relationship_3_1', '2014-09-27 13:42:30'),
(84, 'country_generation_3_1', '2014-09-27 13:46:20'),
(85, 'country_interest_3_1', '2014-09-27 15:09:32'),
(86, 'country_comportamiento_3_1', '2014-09-27 15:31:22'),
(87, 'city_3_1', '2014-09-27 15:52:10'),
(88, 'region_3_1', '2014-09-27 16:23:19'),
(89, 'region_age_3_1', '2014-09-27 16:24:24'),
(90, 'region_relationship_3_1', '2014-09-27 16:27:37'),
(91, 'region_interest_3_1', '2014-09-27 16:28:21'),
(92, 'region_comportamiento_3_1', '2014-09-27 16:29:05'),
(93, 'city_interest_3_1', '2014-09-27 18:10:50'),
(94, 'city_comportamiento_3_1', '2014-09-27 18:34:37'),
(95, 'country_3_1', '2014-09-28 10:15:19'),
(96, 'country_age_3_1', '2014-09-28 10:16:46'),
(97, 'country_relationship_3_1', '2014-09-28 10:26:22'),
(98, 'country_generation_3_1', '2014-09-28 10:27:04'),
(99, 'region_3_1', '2014-09-28 10:48:10'),
(100, 'region_age_3_1', '2014-09-28 10:49:15'),
(101, 'region_relationship_3_1', '2014-09-28 10:50:47'),
(102, 'region_interest_3_1', '2014-09-28 10:51:29'),
(103, 'region_comportamiento_3_1', '2014-09-28 10:52:14'),
(104, 'city_3_1', '2014-09-28 13:06:11'),
(105, 'city_interest_3_1', '2014-09-28 14:49:21'),
(106, 'city_comportamiento_3_1', '2014-09-28 15:20:15'),
(107, 'country_language_3_1', '2014-09-28 23:09:15'),
(108, 'country_interest_3_1', '2014-09-28 23:28:03'),
(109, 'country_comportamiento_3_1', '2014-09-28 23:37:43'),
(110, 'region_3_1', '2014-09-29 18:37:11'),
(111, 'region_age_3_1', '2014-09-29 18:38:15'),
(112, 'region_relationship_3_1', '2014-09-29 18:39:45'),
(113, 'region_interest_3_1', '2014-09-29 18:40:28'),
(114, 'region_comportamiento_3_1', '2014-09-29 18:41:12'),
(115, 'country_3_1', '2014-09-29 18:50:43'),
(116, 'country_age_3_1', '2014-09-29 18:52:10'),
(117, 'country_language_3_1', '2014-09-29 19:04:54'),
(118, 'country_relationship_3_1', '2014-09-29 19:07:44'),
(119, 'country_generation_3_1', '2014-09-29 19:08:26'),
(120, 'country_interest_3_1', '2014-09-29 19:59:10'),
(121, 'country_comportamiento_3_1', '2014-09-29 20:25:56'),
(122, 'city_3_1', '2014-09-29 20:47:26'),
(123, 'city_age_3_1', '2014-09-29 21:59:59'),
(124, 'city_interest_3_1', '2014-09-29 23:14:59'),
(125, 'city_comportamiento_3_1', '2014-09-29 23:39:02'),
(127, 'country_3_1', '2014-09-30 09:35:35'),
(129, 'country_age_3_1', '2014-09-30 10:28:49'),
(131, 'country_language_3_1', '2014-09-30 10:49:10'),
(133, 'country_relationship_3_1', '2014-09-30 11:27:59'),
(135, 'country_generation_3_1', '2014-09-30 11:38:55'),
(136, 'country_interest_3_1', '2014-09-30 13:20:02');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
