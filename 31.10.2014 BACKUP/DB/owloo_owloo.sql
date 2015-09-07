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
-- Database: `owloo_owloo`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Table structure for table `continent`
--

CREATE TABLE IF NOT EXISTS `continent` (
  `id_continent` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `nombre` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id_continent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

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
-- Table structure for table `country_population`
--

CREATE TABLE IF NOT EXISTS `country_population` (
  `id_population` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `population` bigint(12) NOT NULL,
  `year` int(4) NOT NULL,
  PRIMARY KEY (`id_population`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=213 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_access_token_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_access_token_3_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `accountId` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date_in` bigint(20) NOT NULL,
  `date_out` bigint(20) NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7146 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `facebook_age_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_age_3_1` (
  `id_age` int(11) NOT NULL AUTO_INCREMENT,
  `min` int(3) NOT NULL,
  `max` int(3) NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_age`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_city`
--

CREATE TABLE IF NOT EXISTS `facebook_city` (
  `id_city` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `key_city` bigint(11) NOT NULL,
  `subtext` varchar(300) CHARACTER SET utf8 NOT NULL,
  `id_country` bigint(11) NOT NULL,
  PRIMARY KEY (`id_city`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19235 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_city_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_city_3_1` (
  `id_city` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `key_city` int(11) NOT NULL,
  `subtext` varchar(300) CHARACTER SET utf8 NOT NULL,
  `id_country` int(11) NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_city`),
  KEY `id_country` (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6301 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_comportamiento_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_comportamiento_3_1` (
  `id_comportamiento` int(11) NOT NULL AUTO_INCREMENT,
  `key_comportamiento` bigint(25) DEFAULT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nivel` int(1) NOT NULL,
  `nivel_superior` int(11) DEFAULT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_comportamiento`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=129 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_country_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_country_3_1` (
  `id_country` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `abbreviation` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `id_continent` int(11) NOT NULL,
  `habla_hispana` tinyint(1) NOT NULL,
  `supports_region` tinyint(1) NOT NULL,
  `supports_city` tinyint(1) NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_country`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=248 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_generation_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_generation_3_1` (
  `id_generation` int(11) NOT NULL AUTO_INCREMENT,
  `key_generation` bigint(20) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_generation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_interest_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_interest_3_1` (
  `id_interest` int(11) NOT NULL AUTO_INCREMENT,
  `key_interest` bigint(25) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nivel` int(1) NOT NULL,
  `nivel_superior` int(11) DEFAULT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_interest`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=331 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_interest_city_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_interest_city_3_1` (
  `id_country` int(5) NOT NULL,
  `id_interest` int(4) NOT NULL,
  PRIMARY KEY (`id_country`,`id_interest`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_language_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_language_3_1` (
  `id_language` int(11) NOT NULL AUTO_INCREMENT,
  `key_language` int(11) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_language`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_mobile_os_city_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_mobile_os_city_3_1` (
  `id_comportamiento` int(4) NOT NULL,
  PRIMARY KEY (`id_comportamiento`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page`
--

CREATE TABLE IF NOT EXISTS `facebook_page` (
  `id_page` int(11) NOT NULL AUTO_INCREMENT,
  `fb_id` bigint(25) NOT NULL,
  `username` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `about` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `description` text CHARACTER SET utf8,
  `link` varchar(500) CHARACTER SET utf8 NOT NULL,
  `picture` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `cover` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `location` int(3) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `likes` int(11) DEFAULT NULL,
  `talking_about` int(11) DEFAULT NULL,
  `first_local_fans_country` int(3) DEFAULT NULL,
  `hispanic` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `date_add` date NOT NULL,
  `date_update` date NOT NULL,
  PRIMARY KEY (`id_page`),
  UNIQUE KEY `fb_id` (`fb_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4349 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pages`
--

CREATE TABLE IF NOT EXISTS `facebook_pages` (
  `id_page` int(11) NOT NULL AUTO_INCREMENT,
  `idfb_page` bigint(25) NOT NULL,
  `name` varchar(500) CHARACTER SET utf8 NOT NULL,
  `username` varchar(500) CHARACTER SET utf8 NOT NULL,
  `link` varchar(200) CHARACTER SET utf8 NOT NULL,
  `type` varchar(200) CHARACTER SET utf8 NOT NULL,
  `location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `id_country` bigint(11) NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `idfb_page` (`idfb_page`,`type`,`id_country`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=66816 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pages_categories_sub_categories`
--

CREATE TABLE IF NOT EXISTS `facebook_pages_categories_sub_categories` (
  `id_category` int(3) NOT NULL,
  `id_sub_category` int(3) NOT NULL,
  PRIMARY KEY (`id_category`,`id_sub_category`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pages_likes_talking_about`
--

CREATE TABLE IF NOT EXISTS `facebook_pages_likes_talking_about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` bigint(25) NOT NULL,
  `likes` bigint(11) NOT NULL,
  `talking_about` bigint(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idfb_page` (`id_page`),
  KEY `id_page` (`id_page`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=290363 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_pages_sub_categories`
--

CREATE TABLE IF NOT EXISTS `facebook_pages_sub_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `id_sub_category` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4319 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page_access_token`
--

CREATE TABLE IF NOT EXISTS `facebook_page_access_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token` varchar(500) CHARACTER SET utf8 NOT NULL,
  `date_add` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=289 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page_category`
--

CREATE TABLE IF NOT EXISTS `facebook_page_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page_change_id`
--

CREATE TABLE IF NOT EXISTS `facebook_page_change_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `form` bigint(25) NOT NULL,
  `to` bigint(20) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
  KEY `id_page` (`id_page`),
  KEY `id_country` (`id_country`),
  KEY `date` (`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26382066 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_page_sub_category`
--

CREATE TABLE IF NOT EXISTS `facebook_page_sub_category` (
  `id_sub_category` int(11) NOT NULL AUTO_INCREMENT,
  `sub_category` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_sub_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=223 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_city_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_city_3_1` (
  `id_city` int(5) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_city`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_city_age_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_city_age_3_1` (
  `id_city` int(5) NOT NULL,
  `id_age` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_city`,`id_age`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_city_interest_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_city_interest_3_1` (
  `id_city` int(5) NOT NULL,
  `id_interest` int(4) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_city`,`id_interest`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_city_relationship_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_city_relationship_3_1` (
  `id_city` int(5) NOT NULL,
  `id_relationship` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_city`,`id_relationship`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_3_1` (
  `id_country` int(3) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_age_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_age_3_1` (
  `id_country` int(3) NOT NULL,
  `id_age` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`id_age`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_comportamiento_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_comportamiento_3_1` (
  `id_country` int(3) NOT NULL,
  `id_comportamiento` int(4) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`id_comportamiento`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_generation_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_generation_3_1` (
  `id_country` int(3) NOT NULL,
  `id_generation` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`id_generation`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_interest_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_interest_3_1` (
  `id_country` int(3) NOT NULL,
  `id_interest` int(4) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`id_interest`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_language_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_language_3_1` (
  `id_country` int(3) NOT NULL,
  `id_language` int(3) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`id_language`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_country_relationship_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_country_relationship_3_1` (
  `id_country` int(3) NOT NULL,
  `id_relationship` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_country`,`id_relationship`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_region_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_region_3_1` (
  `id_region` int(5) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `total_female` int(10) DEFAULT NULL,
  `total_male` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_region`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_region_age_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_region_age_3_1` (
  `id_region` int(5) NOT NULL,
  `id_age` int(2) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_region`,`id_age`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_region_comportamiento_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_region_comportamiento_3_1` (
  `id_region` int(5) NOT NULL,
  `id_comportamiento` int(4) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_region`,`id_comportamiento`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_record_region_interest_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_record_region_interest_3_1` (
  `id_region` int(5) NOT NULL,
  `id_interest` int(4) NOT NULL,
  `total_user` int(10) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_region`,`id_interest`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

-- --------------------------------------------------------

--
-- Table structure for table `facebook_region_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_region_3_1` (
  `id_region` int(11) NOT NULL AUTO_INCREMENT,
  `region_key` int(11) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_country` int(11) NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_region`),
  UNIQUE KEY `region_key` (`region_key`),
  KEY `id_country` (`id_country`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=266 ;

-- --------------------------------------------------------

--
-- Table structure for table `facebook_relationship_3_1`
--

CREATE TABLE IF NOT EXISTS `facebook_relationship_3_1` (
  `id_relationship` int(11) NOT NULL AUTO_INCREMENT,
  `key_relationship` int(3) NOT NULL,
  `nombre` varchar(200) CHARACTER SET utf8 NOT NULL,
  `active_fb_get_data` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_relationship`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_category`
--

CREATE TABLE IF NOT EXISTS `instagram_category` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_profiles`
--

CREATE TABLE IF NOT EXISTS `instagram_profiles` (
  `id_profile` int(11) NOT NULL AUTO_INCREMENT,
  `instagram_id` bigint(25) NOT NULL,
  `username` varchar(200) CHARACTER SET utf8 NOT NULL,
  `bio` text CHARACTER SET utf8,
  `website` varchar(500) CHARACTER SET utf8 DEFAULT NULL,
  `profile_picture` varchar(500) CHARACTER SET utf8 NOT NULL,
  `full_name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `active` tinyint(1) NOT NULL,
  `date_add` date NOT NULL,
  `date_update` date NOT NULL,
  PRIMARY KEY (`id_profile`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `instagram_record`
--

CREATE TABLE IF NOT EXISTS `instagram_record` (
  `id_profile` int(11) NOT NULL,
  `media` int(10) NOT NULL,
  `followed_by` int(10) NOT NULL,
  `follows` int(10) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id_profile`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_cron_error_info_3_1`
--

CREATE TABLE IF NOT EXISTS `owloo_cron_error_info_3_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `error` text CHARACTER SET utf8 NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=227676 ;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_cron_send_success_3_1`
--

CREATE TABLE IF NOT EXISTS `owloo_cron_send_success_3_1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=137 ;

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

-- --------------------------------------------------------

--
-- Table structure for table `record_city_grow_temp`
--

CREATE TABLE IF NOT EXISTS `record_city_grow_temp` (
  `id_city` bigint(11) NOT NULL,
  `period` int(11) NOT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `total_user` bigint(11) NOT NULL,
  `cambio` bigint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=142963 ;

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
  `language_frances` bigint(12) DEFAULT NULL,
  `language_aleman` bigint(12) DEFAULT NULL,
  `language_italiano` bigint(12) DEFAULT NULL,
  `language_ruso` bigint(12) DEFAULT NULL,
  `language_japones` bigint(12) DEFAULT NULL,
  `language_coreano` bigint(12) DEFAULT NULL,
  `language_holandes` bigint(12) DEFAULT NULL,
  `language_arabe` bigint(12) DEFAULT NULL,
  `language_bengali` bigint(12) DEFAULT NULL,
  `language_turco` bigint(12) DEFAULT NULL,
  `language_malayo` bigint(12) DEFAULT NULL,
  `language_polaco` bigint(12) DEFAULT NULL,
  `language_indonesio` bigint(12) DEFAULT NULL,
  `language_filipino` bigint(12) DEFAULT NULL,
  `language_tailandes` bigint(12) DEFAULT NULL,
  `language_vietnamita` bigint(12) DEFAULT NULL,
  `relationship_single` bigint(12) DEFAULT NULL,
  `relationship_has_a_relationship` bigint(12) DEFAULT NULL,
  `relationship_married` bigint(12) DEFAULT NULL,
  `relationship_comprometido` bigint(12) DEFAULT NULL,
  `education_en_la_escuela_secundaria` bigint(12) DEFAULT NULL,
  `education_en_la_universidad` bigint(12) DEFAULT NULL,
  `education_con_estudios_universitarios` bigint(12) DEFAULT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=134304 ;

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
  `category_34` bigint(10) DEFAULT NULL,
  `category_35` bigint(10) DEFAULT NULL,
  `category_36` bigint(10) DEFAULT NULL,
  `category_37` bigint(10) DEFAULT NULL,
  `category_38` bigint(10) DEFAULT NULL,
  `category_39` bigint(10) DEFAULT NULL,
  `category_40` bigint(10) DEFAULT NULL,
  `category_41` bigint(10) DEFAULT NULL,
  `category_42` bigint(10) DEFAULT NULL,
  `category_43` bigint(10) DEFAULT NULL,
  `category_44` bigint(10) DEFAULT NULL,
  `category_45` bigint(10) DEFAULT NULL,
  `category_46` bigint(10) DEFAULT NULL,
  `category_47` bigint(10) DEFAULT NULL,
  `category_48` bigint(10) DEFAULT NULL,
  `category_49` bigint(10) DEFAULT NULL,
  `category_50` bigint(10) DEFAULT NULL,
  `category_51` bigint(10) DEFAULT NULL,
  `category_52` bigint(10) DEFAULT NULL,
  `category_53` bigint(10) DEFAULT NULL,
  `category_54` bigint(10) DEFAULT NULL,
  `category_55` bigint(10) DEFAULT NULL,
  `category_56` bigint(10) DEFAULT NULL,
  `category_57` bigint(10) DEFAULT NULL,
  `category_58` bigint(10) DEFAULT NULL,
  `category_59` bigint(10) DEFAULT NULL,
  `category_60` bigint(10) DEFAULT NULL,
  `category_61` bigint(10) DEFAULT NULL,
  `category_62` bigint(10) DEFAULT NULL,
  `category_63` bigint(10) DEFAULT NULL,
  `category_64` bigint(10) DEFAULT NULL,
  PRIMARY KEY (`id_user_preference`),
  UNIQUE KEY `id_user_preference` (`id_user_preference`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=93356 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_pages_socialbakers`
--

CREATE TABLE IF NOT EXISTS `temp_pages_socialbakers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `idfb_page` bigint(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54623 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_pages_socialnumbers`
--

CREATE TABLE IF NOT EXISTS `temp_pages_socialnumbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `idfb_page` bigint(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35007 ;

-- --------------------------------------------------------

--
-- Table structure for table `temp_pages_social_bakers_numbers`
--

CREATE TABLE IF NOT EXISTS `temp_pages_social_bakers_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` bigint(11) NOT NULL,
  `idfb_page` bigint(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=70233 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_encoded_locations`
--

CREATE TABLE IF NOT EXISTS `tu_encoded_locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `short_name` varchar(255) NOT NULL COMMENT 'Short name of a location, such as NYC.',
  `full_name` varchar(255) NOT NULL COMMENT 'Full name of location, such as New York, NY, USA.',
  `latlng` varchar(50) NOT NULL COMMENT 'Latitude and longitude coordinates of a place, comma-delimited.',
  PRIMARY KEY (`id`),
  KEY `short_name` (`short_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Geo-encoded locations.' AUTO_INCREMENT=106 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_favorites`
--

CREATE TABLE IF NOT EXISTS `tu_favorites` (
  `post_id` varchar(80) NOT NULL COMMENT 'Post ID on a given network.',
  `author_user_id` varchar(30) NOT NULL COMMENT 'User ID of favorited post author on a given network.',
  `fav_of_user_id` varchar(30) NOT NULL COMMENT 'User ID who favorited post on a given network.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `fav_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time post was favorited.',
  UNIQUE KEY `post_faving_user` (`post_id`,`fav_of_user_id`,`network`),
  KEY `post_id` (`post_id`,`network`),
  KEY `author_user_id` (`author_user_id`,`network`),
  KEY `fav_of_user_id` (`fav_of_user_id`,`network`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Favorite posts.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_follower_count`
--

CREATE TABLE IF NOT EXISTS `tu_follower_count` (
  `network_user_id` varchar(30) NOT NULL COMMENT 'User ID on a particular service with a follower count.',
  `network` varchar(20) NOT NULL COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `date` date NOT NULL COMMENT 'Date of follower count.',
  `count` int(11) NOT NULL COMMENT 'Total number of followers.'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Follower counts by date and time.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_follows`
--

CREATE TABLE IF NOT EXISTS `tu_follows` (
  `user_id` varchar(30) NOT NULL COMMENT 'User ID on a particular service who has been followed.',
  `follower_id` varchar(30) NOT NULL COMMENT 'User ID on a particular service who has followed user_id.',
  `last_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last time this relationship was seen on the originating network.',
  `first_seen` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'First time this relationship was seen on the originating network.',
  `active` int(11) NOT NULL DEFAULT '1' COMMENT 'Whether or not the relationship is active (1 if so, 0 if not.)',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `debug_api_call` varchar(255) NOT NULL COMMENT 'Developer-only field for storing the API URL source of this data point.',
  UNIQUE KEY `network_follower_user` (`network`,`follower_id`,`user_id`),
  KEY `active` (`network`,`active`,`last_seen`),
  KEY `network` (`network`,`last_seen`),
  KEY `user_id` (`user_id`,`network`,`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Service user follow and friend relationships.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_groups`
--

CREATE TABLE IF NOT EXISTS `tu_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `group_id` varchar(50) NOT NULL COMMENT 'Group/list ID on the source network.',
  `network` varchar(20) NOT NULL COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `group_name` varchar(50) NOT NULL COMMENT 'Name of the group or list on the source network.',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Whether or not the group is active (1 if so, 0 if not.)',
  `first_seen` datetime NOT NULL COMMENT 'First time this group was seen on the originating network.',
  `last_seen` datetime NOT NULL COMMENT 'Last time this group was seen on the originating network.',
  PRIMARY KEY (`id`),
  KEY `group_network` (`group_id`,`network`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Groups/lists/circles of users.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_group_members`
--

CREATE TABLE IF NOT EXISTS `tu_group_members` (
  `group_id` varchar(50) NOT NULL COMMENT 'Group/list ID on the source network.',
  `network` varchar(20) NOT NULL COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `member_user_id` varchar(30) NOT NULL COMMENT 'User ID of group member on a given network.',
  `is_active` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Whether or not the user is active in the group (1 if so, 0 if not.)',
  `first_seen` datetime NOT NULL COMMENT 'First time this user was seen in the group.',
  `last_seen` datetime NOT NULL COMMENT 'Last time this user was seen in the group.',
  KEY `group_network` (`group_id`,`network`),
  KEY `member_network` (`member_user_id`,`network`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Service users who are members of groups/lists.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_group_member_count`
--

CREATE TABLE IF NOT EXISTS `tu_group_member_count` (
  `network` varchar(20) NOT NULL COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `member_user_id` varchar(30) NOT NULL COMMENT 'User ID on a particular service in a number of groups/lists.',
  `date` date NOT NULL COMMENT 'Date of group count.',
  `count` int(10) unsigned NOT NULL COMMENT 'Total number of groups the user is in.',
  KEY `member_network` (`member_user_id`,`network`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Group membership counts by date and time.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_hashtags`
--

CREATE TABLE IF NOT EXISTS `tu_hashtags` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `hashtag` varchar(255) NOT NULL COMMENT 'Hash tag, i.e., #latamclick.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network this hashtag appeared on in lower-case, e.g. twitter or facebook.',
  `count_cache` int(11) NOT NULL DEFAULT '0' COMMENT 'A count of times this hashtag was captured.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `network_hashtag` (`network`,`hashtag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Hashtags captured in the datastore.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_hashtags_posts`
--

CREATE TABLE IF NOT EXISTS `tu_hashtags_posts` (
  `post_id` varchar(80) NOT NULL COMMENT 'Post ID on a given network.',
  `hashtag_id` int(11) NOT NULL COMMENT 'Internal hashtag ID.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network this post appeared on in lower-case, e.g. twitter or facebook.',
  UNIQUE KEY `hashtag_post` (`hashtag_id`,`post_id`),
  KEY `post_id` (`network`,`post_id`),
  KEY `hashtag_id` (`hashtag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Hashtags captured per post.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_insights`
--

CREATE TABLE IF NOT EXISTS `tu_insights` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `instance_id` int(11) NOT NULL COMMENT 'Instance ID.',
  `slug` varchar(100) NOT NULL COMMENT 'Identifier for a type of statistic.',
  `prefix` varchar(255) NOT NULL COMMENT 'Prefix to the text content of the alert.',
  `text` text NOT NULL COMMENT 'Text content of the alert.',
  `related_data` text COMMENT 'Serialized related insight data, such as a list of users or a post.',
  `date` date NOT NULL COMMENT 'Date of insight.',
  `emphasis` int(11) NOT NULL DEFAULT '0' COMMENT 'Level of emphasis for insight presentation.',
  PRIMARY KEY (`id`),
  KEY `instance_id` (`instance_id`,`slug`,`date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Insights for a given service user.' AUTO_INCREMENT=2236 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_insight_baselines`
--

CREATE TABLE IF NOT EXISTS `tu_insight_baselines` (
  `date` date NOT NULL COMMENT 'Date of baseline statistic.',
  `instance_id` int(11) NOT NULL COMMENT 'Instance ID.',
  `slug` varchar(100) NOT NULL COMMENT 'Unique identifier for a type of statistic.',
  `value` int(11) NOT NULL COMMENT 'The numeric value of this stat/total/average.',
  UNIQUE KEY `unique_base` (`date`,`instance_id`,`slug`),
  KEY `date` (`date`,`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Insight baseline statistics.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_instances`
--

CREATE TABLE IF NOT EXISTS `tu_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `network_user_id` varchar(30) NOT NULL COMMENT 'User ID on a given network, like a user''s Twitter ID or Facebook user ID.',
  `network_viewer_id` varchar(30) NOT NULL COMMENT 'Network user ID of the viewing user (which can affect permissions).',
  `network_username` varchar(255) NOT NULL COMMENT 'Username on a given network, like a user''s Twitter username or Facebook user name.',
  `last_post_id` varchar(80) NOT NULL COMMENT 'Last network post ID fetched for this instance.',
  `crawler_last_run` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'The last time the crawler completed a run for this instance.',
  `total_posts_by_owner` int(11) DEFAULT '0' COMMENT 'Total posts by this instance as reported by service API.',
  `total_posts_in_system` int(11) DEFAULT '0' COMMENT 'Total posts in datastore authored by this instance.',
  `total_replies_in_system` int(11) DEFAULT NULL COMMENT 'Total replies in datastore authored by this instance.',
  `total_follows_in_system` int(11) DEFAULT NULL COMMENT 'Total active follows where instance is the followed user.',
  `posts_per_day` decimal(7,2) DEFAULT NULL COMMENT 'Average posts per day by instance.',
  `posts_per_week` decimal(7,2) DEFAULT NULL COMMENT 'Average posts per week by instance.',
  `percentage_replies` decimal(4,2) DEFAULT NULL COMMENT 'Percent of an instance''s posts which are replies.',
  `percentage_links` decimal(4,2) DEFAULT NULL COMMENT 'Percent of an instance''s posts which contain links.',
  `earliest_post_in_system` datetime DEFAULT NULL COMMENT 'Date and time of the earliest post authored by the instance in the datastore.',
  `earliest_reply_in_system` datetime DEFAULT NULL COMMENT 'Date and time of the earliest reply authored by the instance in the datastore.',
  `is_archive_loaded_posts` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not all the instance''s posts have been backfilled.',
  `is_archive_loaded_replies` int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not all the instance''s replies have been backfilled.',
  `is_archive_loaded_follows` int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not all the instance''s follows have been backfilled.',
  `is_public` int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not instance is public in Owloo, that is, viewable when no Owloo user is logged in.',
  `is_active` int(1) NOT NULL DEFAULT '1' COMMENT 'Whether or not the instance user is being actively crawled (0 if it is paused).',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The lowercase name of the source network, i.e., twitter or facebook.',
  `favorites_profile` int(11) DEFAULT '0' COMMENT 'Total instance favorites as reported by the service API.',
  `owner_favs_in_system` int(11) DEFAULT '0' COMMENT 'Total instance favorites saved in the datastore.',
  PRIMARY KEY (`id`),
  KEY `network_user_id` (`network_user_id`,`network`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Authed network user for which Owloo archives data.' AUTO_INCREMENT=419 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_instances_like_source`
--

CREATE TABLE IF NOT EXISTS `tu_instances_like_source` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `network_user_id` bigint(25) NOT NULL,
  `like_source_date` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mobile` int(11) NOT NULL DEFAULT '0',
  `ads` int(11) NOT NULL,
  `page_profile` int(11) NOT NULL,
  `like_story` int(11) NOT NULL,
  `recommended_pages` int(11) NOT NULL,
  `vertex_page` int(11) NOT NULL,
  `hovercard` int(11) NOT NULL,
  `timeline` int(11) NOT NULL,
  `page_browser` int(11) NOT NULL,
  `page_suggestions_on_liking` int(11) NOT NULL,
  `api` int(11) NOT NULL,
  `mobile_page_browser` int(11) NOT NULL,
  `search` int(11) NOT NULL,
  `photo_snowlift` int(11) NOT NULL,
  `others` int(11) NOT NULL,
  `favorites` int(11) NOT NULL,
  `external_connect` int(11) NOT NULL,
  `fan_context_story` int(11) NOT NULL,
  `ticker` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_instances_twitter`
--

CREATE TABLE IF NOT EXISTS `tu_instances_twitter` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `last_page_fetched_replies` int(11) NOT NULL DEFAULT '1' COMMENT 'Last page of replies fetched for this instance.',
  `last_page_fetched_tweets` int(11) NOT NULL DEFAULT '1' COMMENT 'Last page of tweets fetched for this instance.',
  `last_favorite_id` varchar(80) DEFAULT NULL COMMENT 'Last favorite post ID of the instance saved.',
  `last_unfav_page_checked` int(11) DEFAULT '0' COMMENT 'Last page of older favorites checked for backfilling.',
  `last_page_fetched_favorites` int(11) DEFAULT NULL COMMENT 'Last page of favorites fetched.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Twitter-specific instance metadata.' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_invites`
--

CREATE TABLE IF NOT EXISTS `tu_invites` (
  `invite_code` varchar(10) DEFAULT NULL COMMENT 'Invitation code.',
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the invitation was created, used to calculate expiration time.'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Individual user registration invitations.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_links`
--

CREATE TABLE IF NOT EXISTS `tu_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `url` varchar(255) NOT NULL COMMENT 'Link URL as it appears inside the post, ie, shortened in tweets.',
  `expanded_url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Link URL expanded from its shortened form.',
  `title` varchar(255) NOT NULL COMMENT 'Link title.',
  `description` varchar(255) NOT NULL COMMENT 'Link description.',
  `image_src` varchar(255) NOT NULL DEFAULT '' COMMENT 'URL of a thumbnail image associated with this link.',
  `caption` varchar(255) NOT NULL COMMENT 'Link or image caption.',
  `post_key` int(11) NOT NULL COMMENT 'Internal ID of the post in which this link appeared.',
  `error` varchar(255) NOT NULL DEFAULT '' COMMENT 'Details of any error expanding a link.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`,`post_key`),
  KEY `post_key` (`post_key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Links which appear in posts.' AUTO_INCREMENT=11167 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_links_short`
--

CREATE TABLE IF NOT EXISTS `tu_links_short` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `link_id` int(11) NOT NULL COMMENT 'Expanded link ID in links table.',
  `short_url` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'Shortened URL.',
  `click_count` int(11) NOT NULL COMMENT 'Total number of clicks as reported by shortening service.',
  `first_seen` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of short URL capture.',
  PRIMARY KEY (`id`),
  KEY `link_id` (`link_id`),
  KEY `short_url` (`short_url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Shortened URLs, potentially many per link.' AUTO_INCREMENT=224 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_mentions`
--

CREATE TABLE IF NOT EXISTS `tu_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `user_id` varchar(30) NOT NULL COMMENT 'The user ID inside the respective service, e.g. Twitter or Facebook user IDs.',
  `user_name` varchar(255) NOT NULL COMMENT 'The user''s name inside the respective service, e.g. Twitter or Facebook user name.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network that this post belongs to in lower-case, e.g. twitter or facebook.',
  `count_cache` int(11) NOT NULL DEFAULT '0' COMMENT 'A count of mentions a given user on a network has in the datastore.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`network`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Mentions captured per user. One row per user.' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_mentions_posts`
--

CREATE TABLE IF NOT EXISTS `tu_mentions_posts` (
  `post_id` varchar(80) NOT NULL COMMENT 'Post ID on a given network.',
  `author_user_id` varchar(30) NOT NULL COMMENT 'Author user ID of the post which contains the mention on a given network.',
  `mention_id` int(11) NOT NULL COMMENT 'Internal mention ID.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network which the mentioning post and mention comes from.',
  UNIQUE KEY `mention_post` (`mention_id`,`post_id`),
  KEY `post_id` (`network`,`post_id`),
  KEY `author_user_id` (`author_user_id`),
  KEY `mention_id` (`mention_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Mentions captured per post.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_options`
--

CREATE TABLE IF NOT EXISTS `tu_options` (
  `option_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique internal ID.',
  `namespace` varchar(50) NOT NULL COMMENT 'Option namespace, ie, application or specific plugin.',
  `option_name` varchar(50) NOT NULL COMMENT 'Name of option or setting.',
  `option_value` varchar(255) NOT NULL COMMENT 'Value of option.',
  `last_updated` datetime NOT NULL COMMENT 'Last time option was updated.',
  `created` datetime NOT NULL COMMENT 'When option was created.',
  PRIMARY KEY (`option_id`),
  KEY `namespace_key` (`namespace`),
  KEY `name_key` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Application and plugin options or settings.' AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_owners`
--

CREATE TABLE IF NOT EXISTS `tu_owners` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `full_name` varchar(200) NOT NULL COMMENT 'User full name.',
  `pwd` varchar(255) DEFAULT NULL COMMENT 'Hash of the owner password',
  `pwd_salt` varchar(255) NOT NULL COMMENT 'Salt for securely hashing the owner password',
  `email` varchar(200) NOT NULL COMMENT 'User email.',
  `activation_code` int(10) NOT NULL DEFAULT '0' COMMENT 'User activation code.',
  `joined` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Date user registered for an account.',
  `is_activated` int(1) NOT NULL DEFAULT '0' COMMENT 'If user is activated, 1 for true, 0 for false.',
  `is_admin` int(1) NOT NULL DEFAULT '0' COMMENT 'If user is an admin, 1 for true, 0 for false.',
  `last_login` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Last time user logged into Owloo.',
  `password_token` varchar(64) DEFAULT NULL COMMENT 'Password reset token.',
  `failed_logins` int(11) NOT NULL DEFAULT '0' COMMENT 'Current number of failed login attempts.',
  `account_status` varchar(150) NOT NULL DEFAULT '' COMMENT 'Description of account status, i.e., "Inactive due to excessive failed login attempts".',
  `api_key` varchar(32) NOT NULL COMMENT 'Key to authorize API calls.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Owloo user account details.' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_owner_instances`
--

CREATE TABLE IF NOT EXISTS `tu_owner_instances` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `owner_id` int(10) NOT NULL COMMENT 'Owner ID.',
  `instance_id` int(10) NOT NULL COMMENT 'Instance ID.',
  `oauth_access_token` varchar(255) DEFAULT NULL COMMENT 'OAuth access token (optional).',
  `oauth_access_token_secret` varchar(255) DEFAULT NULL COMMENT 'OAuth secret access token (optional).',
  `auth_error` varchar(255) DEFAULT NULL COMMENT 'Last authorization error, if there was one.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner_instance_id` (`owner_id`,`instance_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Service user auth tokens per owner.' AUTO_INCREMENT=54 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_places`
--

CREATE TABLE IF NOT EXISTS `tu_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `place_id` varchar(100) DEFAULT NULL COMMENT 'Place ID on a given network.',
  `place_type` varchar(100) DEFAULT NULL COMMENT 'Type of place.',
  `name` varchar(100) DEFAULT NULL COMMENT 'Short name of a place.',
  `full_name` varchar(255) DEFAULT NULL COMMENT 'Full name of a place.',
  `country_code` varchar(2) DEFAULT NULL COMMENT 'Country code where the place is located.',
  `country` varchar(100) DEFAULT NULL COMMENT 'Country where the place is located.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network this place appears on in lower-case, e.g. twitter or facebook.',
  `longlat` point DEFAULT NULL COMMENT 'Longitude/lattitude point.',
  `bounding_box` polygon DEFAULT NULL COMMENT 'Bounding box of place.',
  `icon` varchar(255) DEFAULT NULL COMMENT 'URL to an icon which represents the place type.',
  `map_image` varchar(255) DEFAULT NULL COMMENT 'URL to an image of a map representing the area this place is in.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `place_id` (`place_id`,`network`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Places on a given network.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_places_posts`
--

CREATE TABLE IF NOT EXISTS `tu_places_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `longlat` point NOT NULL COMMENT 'Longitude/lattitude point.',
  `post_id` varchar(80) NOT NULL COMMENT 'Post ID on a given network.',
  `place_id` varchar(100) DEFAULT NULL COMMENT 'Place ID on a given network.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network this post appeared on in lower-case, e.g. twitter or facebook.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`network`,`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Place where a post was published from. One row per post.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_plugins`
--

CREATE TABLE IF NOT EXISTS `tu_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `name` varchar(255) NOT NULL COMMENT 'Plugin display name, such as Hello Owloo.',
  `folder_name` varchar(255) NOT NULL COMMENT 'Name of folder where plugin lives.',
  `description` varchar(255) DEFAULT NULL COMMENT 'Plugin description.',
  `author` varchar(255) DEFAULT NULL COMMENT 'Plugin author.',
  `homepage` varchar(255) DEFAULT NULL COMMENT 'Plugin homepage URL.',
  `version` varchar(255) DEFAULT NULL COMMENT 'Plugin version.',
  `is_active` tinyint(4) NOT NULL COMMENT 'Whether or not the plugin is activated (1 if so, 0 if not.)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Application plugins.' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_posts`
--

CREATE TABLE IF NOT EXISTS `tu_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID..',
  `post_id` varchar(80) NOT NULL COMMENT 'The ID of the post inside the respective service.',
  `author_user_id` varchar(30) NOT NULL COMMENT 'The user ID inside the respective service, e.g. Twitter or Facebook user IDs.',
  `author_username` varchar(50) NOT NULL COMMENT 'The user''s username inside the respective service, e.g. Twitter or Facebook user name.',
  `author_fullname` varchar(50) NOT NULL COMMENT 'The user''s real, full name on a given service, e.g. Hector Steiner.',
  `author_avatar` varchar(255) NOT NULL COMMENT 'The URL to the user''s avatar for a given service.',
  `author_follower_count` int(11) NOT NULL COMMENT 'Post author''s follower count. [Twitter-specific]',
  `post_text` text NOT NULL COMMENT 'The textual content of a user''s post on a given service.',
  `is_protected` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Whether or not this post is protected, e.g. not publicly visible.',
  `source` varchar(255) DEFAULT NULL COMMENT 'The client used to publish this post, e.g. if you post from the Twitter web interface, this will be "web".',
  `location` varchar(255) DEFAULT NULL COMMENT 'Author-level location, e.g., the author''s location as set in his or her profile. Use author-level location if post-level location is not set.',
  `place` varchar(255) DEFAULT NULL COMMENT 'Post-level name of a place from which a post was published, ie, Woodland Hills, Los Angeles.',
  `place_id` varchar(255) DEFAULT NULL COMMENT 'Post-level place ID on a given network.',
  `geo` varchar(255) DEFAULT NULL COMMENT 'The post''s latitude and longitude coordinates.',
  `pub_date` datetime NOT NULL COMMENT 'The UTC date/time when this post was published.',
  `in_reply_to_user_id` varchar(30) DEFAULT NULL COMMENT 'The ID of the user that this post is in reply to.',
  `in_reply_to_post_id` varchar(80) DEFAULT NULL COMMENT 'The ID of the post that this post is in reply to.',
  `reply_count_cache` int(11) NOT NULL DEFAULT '0' COMMENT 'The total number of replies this post received in the data store.',
  `is_reply_by_friend` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Whether or not this reply was authored by a friend of the original post''s author.',
  `in_retweet_of_post_id` varchar(80) DEFAULT NULL COMMENT 'The ID of the post that this post is a retweet of. [Twitter-specific]',
  `old_retweet_count_cache` int(11) NOT NULL DEFAULT '0' COMMENT 'Manual count of old-style retweets as determined by Owloo. [Twitter-specific]',
  `is_retweet_by_friend` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Whether or not this retweet was posted by a friend of the original post''s author. [Twitter-specific]',
  `reply_retweet_distance` int(11) NOT NULL DEFAULT '0' COMMENT 'The distance (in km) away from the post that this post is in reply or retweet of [Twitter-specific-ish]',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'The network that this post belongs to in lower-case, e.g. twitter or facebook',
  `is_geo_encoded` int(1) NOT NULL DEFAULT '0' COMMENT 'Whether or not this post has been geo-encoded.',
  `in_rt_of_user_id` varchar(30) DEFAULT NULL COMMENT 'The ID of the user that this post is retweeting. [Twitter-specific]',
  `retweet_count_cache` int(11) NOT NULL DEFAULT '0' COMMENT 'Manual count of native retweets as determined by Owloo. [Twitter-specific]',
  `retweet_count_api` int(11) NOT NULL DEFAULT '0' COMMENT 'The total number of native retweets as reported by Twitter API. [Twitter-specific]',
  `favlike_count_cache` int(11) NOT NULL DEFAULT '0' COMMENT 'The total number of favorites or likes this post received.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_network` (`post_id`,`network`),
  KEY `author_username` (`author_username`),
  KEY `pub_date` (`pub_date`),
  KEY `author_user_id` (`author_user_id`),
  KEY `in_retweet_of_status_id` (`in_retweet_of_post_id`),
  KEY `in_reply_to_user_id` (`in_reply_to_user_id`),
  KEY `post_id` (`post_id`),
  KEY `network` (`network`),
  KEY `is_protected` (`is_protected`),
  KEY `in_reply_to_post_id` (`in_reply_to_post_id`),
  FULLTEXT KEY `post_fulltext` (`post_text`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Posts by service users on a given network.' AUTO_INCREMENT=97771 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_post_errors`
--

CREATE TABLE IF NOT EXISTS `tu_post_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `post_id` varchar(80) NOT NULL COMMENT 'Post ID on the originating service.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `error_code` int(11) NOT NULL COMMENT 'Error code issues from the service.',
  `error_text` varchar(255) NOT NULL COMMENT 'Error text as supplied from the service.',
  `error_issued_to_user_id` varchar(30) NOT NULL COMMENT 'User ID service issued error to.',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`network`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Errors in response to requests for post information.' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_stream_data`
--

CREATE TABLE IF NOT EXISTS `tu_stream_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `data` text NOT NULL COMMENT 'Raw stream data.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Unprocessed stream data. InnoDB for sel/del transactions.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_stream_procs`
--

CREATE TABLE IF NOT EXISTS `tu_stream_procs` (
  `process_id` int(11) NOT NULL COMMENT 'Stream process ID.',
  `email` varchar(100) NOT NULL COMMENT 'Email address of the user running the stream process.',
  `instance_id` int(11) NOT NULL COMMENT 'Internal instance ID receiving stream data.',
  `last_report` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Process heartbeat''s last beat time.',
  PRIMARY KEY (`process_id`),
  UNIQUE KEY `owner_instance` (`email`,`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Running stream process details.';

-- --------------------------------------------------------

--
-- Table structure for table `tu_users`
--

CREATE TABLE IF NOT EXISTS `tu_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `user_id` varchar(30) NOT NULL COMMENT 'User ID on a given network.',
  `user_name` varchar(255) NOT NULL COMMENT 'Username on a given network, like a user''s Twitter username or Facebook user name.',
  `full_name` varchar(255) NOT NULL COMMENT 'Full name on a given network.',
  `avatar` varchar(255) NOT NULL COMMENT 'URL to user''s avatar on a given network.',
  `location` varchar(255) DEFAULT NULL COMMENT 'Service user location.',
  `description` text COMMENT 'Service user description, like a Twitter user''s profile description.',
  `url` varchar(255) DEFAULT NULL COMMENT 'Service user''s URL.',
  `is_protected` tinyint(1) NOT NULL COMMENT 'Whether or not the user is public.',
  `follower_count` int(11) NOT NULL COMMENT 'Total number of followers a service user has.',
  `friend_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Total number of friends a service user has.',
  `post_count` int(11) NOT NULL DEFAULT '0' COMMENT 'Total number of posts the user has authored.',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Last time this user''s record was updated.',
  `found_in` varchar(100) DEFAULT NULL COMMENT 'What data source or API call the last update originated from (for developer debugging).',
  `last_post` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time of the latest post the user authored.',
  `joined` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'When the user joined the network.',
  `last_post_id` varchar(80) NOT NULL DEFAULT '' COMMENT 'Network post ID of the latest post the user authored.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  `favorites_count` int(11) DEFAULT NULL COMMENT 'Total number of posts the user has favorited.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`network`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Service user details.' AUTO_INCREMENT=49458 ;

-- --------------------------------------------------------

--
-- Table structure for table `tu_user_errors`
--

CREATE TABLE IF NOT EXISTS `tu_user_errors` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Internal unique ID.',
  `user_id` varchar(30) NOT NULL COMMENT 'User ID on a particular service.',
  `error_code` int(11) NOT NULL COMMENT 'Error code issues from the service.',
  `error_text` varchar(255) NOT NULL COMMENT 'Error text as supplied from the service.',
  `error_issued_to_user_id` varchar(30) NOT NULL COMMENT 'User ID service issued error to.',
  `network` varchar(20) NOT NULL DEFAULT 'twitter' COMMENT 'Originating network in lower case, i.e., twitter or facebook.',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Errors in response to requests for user information.' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user_favorites`
--

CREATE TABLE IF NOT EXISTS `user_favorites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` bigint(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  `id_element` bigint(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `date_add` date NOT NULL,
  `date_down` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=568 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
