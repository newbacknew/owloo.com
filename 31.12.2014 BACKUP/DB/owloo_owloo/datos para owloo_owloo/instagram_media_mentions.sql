-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:25 PM
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
-- Table structure for table `instagram_media_mentions`
--

CREATE TABLE IF NOT EXISTS `instagram_media_mentions` (
  `id` bigint(25) NOT NULL AUTO_INCREMENT,
  `id_profile` int(11) NOT NULL,
  `id_media` bigint(25) NOT NULL,
  `mention` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_profile` (`id_profile`),
  KEY `mention` (`mention`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=100863 ;

--
-- Dumping data for table `instagram_media_mentions`
--

INSERT INTO `instagram_media_mentions` (`id`, `id_profile`, `id_media`, `mention`) VALUES
(100831, 122, 146945, 'thejuicespot'),
(100832, 122, 146945, 'dzandertraining'),
(100833, 122, 146945, 'mrs_savannahrj'),
(100834, 122, 146946, 'dzandertraining'),
(100835, 122, 146948, 'faraleff'),
(100836, 122, 146950, 'jmanziel2'),
(100837, 122, 146954, 'mrs_savannahrj'),
(100838, 122, 146959, 'realtristan13'),
(100839, 122, 146960, 'iam_objxiii'),
(100840, 122, 146960, 'iam_objxiii'),
(100841, 122, 146960, 'kingjames'),
(100842, 122, 146961, 'k1irving'),
(100843, 122, 146961, 'nikebasketball'),
(100844, 53, 146968, 'tbhits'),
(100845, 53, 146968, 'victoriamonet'),
(100846, 53, 146981, 'isthatjessiej'),
(100847, 53, 146981, 'forloveandlemons'),
(100848, 53, 146984, 'tywrent'),
(100849, 53, 146984, 'bangbangnyc'),
(100850, 53, 146985, 'mitchgrassi'),
(100851, 53, 146993, 'jarvisderrell'),
(100852, 53, 146993, 'shehashadit'),
(100853, 53, 146994, 'lexie1225'),
(100854, 53, 147001, 'taylorswift'),
(100855, 53, 147001, 'karliekloss'),
(100856, 53, 147002, 'taylorswift'),
(100857, 112, 147008, 'adamtots'),
(100858, 112, 147010, 'iamlilbub'),
(100859, 112, 147018, 'wordswithbffs'),
(100860, 112, 147028, 'cooperhewitt');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
