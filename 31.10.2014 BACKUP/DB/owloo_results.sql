-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2014 at 12:32 AM
-- Server version: 5.5.37-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `owloo_results`
--

-- --------------------------------------------------------

--
-- Table structure for table `facebook_cities`
--

CREATE TABLE IF NOT EXISTS `facebook_cities` (
  `id_city` int(5) NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_city`),
  KEY `country_code` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_countries`
--

CREATE TABLE IF NOT EXISTS `facebook_countries` (
  `id_country` int(3) NOT NULL,
  `code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name_en` varchar(200) CHARACTER SET utf8 NOT NULL,
  `abbreviation` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `idiom` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `id_continent` int(1) NOT NULL,
  `supports_region` tinyint(1) NOT NULL,
  `supports_city` tinyint(1) NOT NULL,
  `total_user` int(10) NOT NULL,
  `total_female` int(10) NOT NULL,
  `total_male` int(10) NOT NULL,
  `audience_history` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `audience_grow_1` int(10) DEFAULT NULL,
  `audience_grow_7` int(10) DEFAULT NULL,
  `audience_grow_30` int(10) DEFAULT NULL,
  `audience_grow_60` int(10) DEFAULT NULL,
  `audience_grow_90` int(10) DEFAULT NULL,
  `audience_grow_180` int(10) DEFAULT NULL,
  `general_ranking` int(3) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_country`),
  KEY `code` (`code`,`idiom`,`id_continent`,`supports_region`,`supports_city`,`total_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_countries_ages`
--

CREATE TABLE IF NOT EXISTS `facebook_countries_ages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_age` int(3) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `grow_30` int(11) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  KEY `id_age` (`id_age`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1977 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_countries_comportamientos`
--

CREATE TABLE IF NOT EXISTS `facebook_countries_comportamientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comportamiento` int(4) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nivel` int(1) NOT NULL,
  `nivel_superior` int(4) NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) DEFAULT NULL,
  `total_female` int(11) DEFAULT NULL,
  `total_male` int(11) DEFAULT NULL,
  `grow_30` int(11) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_interest` (`id_comportamiento`,`nivel`,`nivel_superior`,`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31617 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_countries_interests`
--

CREATE TABLE IF NOT EXISTS `facebook_countries_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_interest` int(4) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nivel` int(1) NOT NULL,
  `nivel_superior` int(4) NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `grow_30` int(11) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_interest` (`id_interest`,`nivel`,`nivel_superior`,`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81264 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_countries_languages`
--

CREATE TABLE IF NOT EXISTS `facebook_countries_languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_language` int(3) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `grow_30` int(11) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_language` (`id_language`),
  KEY `country_code` (`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13586 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_countries_relationships`
--

CREATE TABLE IF NOT EXISTS `facebook_countries_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_relationship` int(2) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `total_user_grow_30` int(11) DEFAULT NULL,
  `total_female_grow_30` int(11) DEFAULT NULL,
  `total_male_grow_30` int(11) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_relationship` (`id_relationship`,`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2965 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pages`
--

CREATE TABLE IF NOT EXISTS `facebook_pages` (
  `id_page` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `fb_id` bigint(25) NOT NULL,
  `username` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `about` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `link` varchar(500) CHARACTER SET utf8 NOT NULL,
  `picture` varchar(500) CHARACTER SET utf8 NOT NULL,
  `cover` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `likes` int(11) NOT NULL,
  `likes_history_30` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `likes_grow_1` int(11) DEFAULT NULL,
  `likes_grow_7` int(11) DEFAULT NULL,
  `likes_grow_15` int(11) DEFAULT NULL,
  `likes_grow_30` int(11) DEFAULT NULL,
  `likes_grow_60` int(11) DEFAULT NULL,
  `talking_about` int(11) NOT NULL,
  `talking_about_history_30` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `talking_about_grow_1` int(11) DEFAULT NULL,
  `talking_about_grow_7` int(11) DEFAULT NULL,
  `talking_about_grow_15` int(11) DEFAULT NULL,
  `talking_about_grow_30` int(11) DEFAULT NULL,
  `talking_about_grow_60` int(11) DEFAULT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `country_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `country_name_en` varchar(200) DEFAULT NULL,
  `country_ranking` int(11) DEFAULT NULL,
  `first_country_code` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `first_country_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `first_country_name_en` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `idiom` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `category_id` int(5) DEFAULT NULL,
  `category_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `sub_category_id` int(5) DEFAULT NULL,
  `sub_category_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `general_ranking` int(11) DEFAULT NULL,
  `first_local_fans_country_ranking` int(11) DEFAULT NULL,
  `first_local_fans_country_audience` int(10) DEFAULT NULL,
  `in_owloo_from` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `country_code` (`country_code`,`first_country_code`,`idiom`),
  KEY `likes` (`likes`),
  KEY `talking_about` (`talking_about`),
  KEY `idiom` (`idiom`),
  KEY `parent` (`parent`),
  KEY `category_id` (`category_id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pages_local_fans`
--

CREATE TABLE IF NOT EXISTS `facebook_pages_local_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `fb_id` bigint(25) NOT NULL,
  `username` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `likes` int(11) NOT NULL,
  `likes_grow_7` int(11) DEFAULT NULL,
  `talking_about` int(11) NOT NULL,
  `category_id` int(5) DEFAULT NULL,
  `sub_category_id` int(5) DEFAULT NULL,
  `country_code` varchar(2) NOT NULL,
  `country_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `country_name_en` varchar(200) CHARACTER SET utf8 NOT NULL,
  `likes_local_fans` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  KEY `likes` (`likes_local_fans`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=156168 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page_local_fans_country`
--

CREATE TABLE IF NOT EXISTS `facebook_page_local_fans_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `id_country` int(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_page` (`id_page`,`id_country`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5570476 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_regions`
--

CREATE TABLE IF NOT EXISTS `facebook_regions` (
  `id_region` int(5) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_region`),
  KEY `country_code` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
