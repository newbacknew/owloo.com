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
-- Table structure for table `facebook_access_token_account_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_access_token_account_3_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pass` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `accountId` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pageId` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `pageName` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `facebook_access_token_account_3_1`
--

INSERT INTO `facebook_access_token_account_3_1` (`id`, `email`, `pass`, `accountId`, `pageId`, `pageName`) VALUES
(1, 'jcanesse78@gmail.com', 'canessej86', '105368146303815', '532655710080087', 'See'),
(2, 'jamendez567@gmail.com', 'ret789lkj741', '109036285959199', '583148568369879', 'Sop'),
(3, 'jjgizmodo@gmail.com', 'jjgizmodo1234', '1383765858523831', '232427073579443', 'Hers');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
