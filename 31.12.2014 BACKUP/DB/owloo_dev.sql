-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2014 at 12:31 AM
-- Server version: 5.5.37-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `owloo_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id_category` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL,
  PRIMARY KEY (`id_category`),
  UNIQUE KEY `id_category` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE IF NOT EXISTS `country` (
  `id_country` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `abbreviation` varchar(200) DEFAULT NULL,
  `id_continent` int(11) NOT NULL,
  `habla_hispana` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=213 ;

-- --------------------------------------------------------

--
-- Table structure for table `record_country`
--

CREATE TABLE IF NOT EXISTS `record_country` (
  `id_historial_pais` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `date` date NOT NULL,
  `total_user` bigint(20) DEFAULT NULL,
  `total_female` bigint(20) DEFAULT NULL,
  `total_male` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_historial_pais`),
  KEY `id_country` (`id_country`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=152148 ;

-- --------------------------------------------------------

--
-- Table structure for table `record_country_for_age_language`
--

CREATE TABLE IF NOT EXISTS `record_country_for_age_language` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `rango_13_15` bigint(12) DEFAULT NULL,
  `rango_16_17` bigint(12) DEFAULT NULL,
  `rango_18_28` bigint(12) DEFAULT NULL,
  `rango_29_34` bigint(12) DEFAULT NULL,
  `rango_35_44` bigint(12) DEFAULT NULL,
  `rango_45_54` bigint(12) DEFAULT NULL,
  `rango_55_64` bigint(12) DEFAULT NULL,
  `rango_65_65` bigint(12) DEFAULT NULL,
  `language_spanish` bigint(12) DEFAULT NULL,
  `language_english` bigint(12) DEFAULT NULL,
  `language_chinese` bigint(12) DEFAULT NULL,
  `language_portuguese` bigint(12) DEFAULT NULL,
  `language_hindi` bigint(12) DEFAULT NULL,
  `relationship_single` bigint(12) DEFAULT NULL,
  `relationship_has_a_relationship` bigint(12) DEFAULT NULL,
  `relationship_married` bigint(12) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17809 ;

-- --------------------------------------------------------

--
-- Table structure for table `record_country_for_user_preference`
--

CREATE TABLE IF NOT EXISTS `record_country_for_user_preference` (
  `id_user_preference` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `date` date NOT NULL,
  `category_1` bigint(10) DEFAULT NULL,
  `category_2` bigint(10) DEFAULT NULL,
  `category_3` bigint(10) DEFAULT NULL,
  `category_4` bigint(10) DEFAULT NULL,
  `category_5` bigint(10) DEFAULT NULL,
  `category_6` bigint(10) DEFAULT NULL,
  `category_7` bigint(10) DEFAULT NULL,
  `category_8` bigint(10) DEFAULT NULL,
  `category_9` bigint(10) DEFAULT NULL,
  `category_10` bigint(10) DEFAULT NULL,
  `category_11` bigint(10) DEFAULT NULL,
  `category_12` bigint(10) DEFAULT NULL,
  `category_13` bigint(10) DEFAULT NULL,
  `category_14` bigint(10) DEFAULT NULL,
  `category_15` bigint(10) DEFAULT NULL,
  `category_16` bigint(10) DEFAULT NULL,
  `category_17` bigint(10) DEFAULT NULL,
  `category_18` bigint(10) DEFAULT NULL,
  `category_19` bigint(10) DEFAULT NULL,
  `category_20` bigint(10) DEFAULT NULL,
  `category_21` bigint(10) DEFAULT NULL,
  `category_22` bigint(10) DEFAULT NULL,
  `category_23` bigint(10) DEFAULT NULL,
  `category_24` bigint(10) DEFAULT NULL,
  `category_25` bigint(10) DEFAULT NULL,
  `category_26` bigint(10) DEFAULT NULL,
  `category_27` bigint(10) DEFAULT NULL,
  `category_28` bigint(10) DEFAULT NULL,
  `category_29` bigint(10) DEFAULT NULL,
  `category_30` bigint(10) DEFAULT NULL,
  `category_31` bigint(10) DEFAULT NULL,
  `category_32` bigint(10) DEFAULT NULL,
  `category_33` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id_user_preference`),
  UNIQUE KEY `id_user_preference` (`id_user_preference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17684 ;

-- --------------------------------------------------------

--
-- Table structure for table `record_country_test_2_index`
--

CREATE TABLE IF NOT EXISTS `record_country_test_2_index` (
  `id_country` int(4) NOT NULL,
  `date` date NOT NULL,
  `total_user` bigint(10) NOT NULL,
  `total_female` bigint(10) NOT NULL,
  `total_male` bigint(10) NOT NULL,
  PRIMARY KEY (`id_country`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
