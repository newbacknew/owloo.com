-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:34 PM
-- Server version: 5.5.40-cll
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
-- Table structure for table `blog_commentmeta`
--

CREATE TABLE IF NOT EXISTS `blog_commentmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`),
  KEY `disqus_dupecheck` (`meta_key`,`meta_value`(11))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_comments`
--

CREATE TABLE IF NOT EXISTS `blog_comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text NOT NULL,
  `comment_karma` int(11) NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) NOT NULL DEFAULT '',
  `comment_type` varchar(20) NOT NULL DEFAULT '',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_links`
--

CREATE TABLE IF NOT EXISTS `blog_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) NOT NULL DEFAULT '',
  `link_name` varchar(255) NOT NULL DEFAULT '',
  `link_image` varchar(255) NOT NULL DEFAULT '',
  `link_target` varchar(25) NOT NULL DEFAULT '',
  `link_description` varchar(255) NOT NULL DEFAULT '',
  `link_visible` varchar(20) NOT NULL DEFAULT 'Y',
  `link_owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `link_rating` int(11) NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) NOT NULL DEFAULT '',
  `link_notes` mediumtext NOT NULL,
  `link_rss` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_options`
--

CREATE TABLE IF NOT EXISTS `blog_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1573 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_postmeta`
--

CREATE TABLE IF NOT EXISTS `blog_postmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=516 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE IF NOT EXISTS `blog_posts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_excerpt` text NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) NOT NULL DEFAULT 'open',
  `post_password` varchar(20) NOT NULL DEFAULT '',
  `post_name` varchar(200) NOT NULL DEFAULT '',
  `to_ping` text NOT NULL,
  `pinged` text NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext NOT NULL,
  `post_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=193 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_terms`
--

CREATE TABLE IF NOT EXISTS `blog_terms` (
  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `slug` varchar(200) NOT NULL DEFAULT '',
  `term_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_term_relationships`
--

CREATE TABLE IF NOT EXISTS `blog_term_relationships` (
  `object_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `term_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `blog_term_taxonomy`
--

CREATE TABLE IF NOT EXISTS `blog_term_taxonomy` (
  `term_taxonomy_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) NOT NULL DEFAULT '',
  `description` longtext NOT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `count` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=132 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_un_termmeta`
--

CREATE TABLE IF NOT EXISTS `blog_un_termmeta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `un_term_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`meta_id`),
  KEY `un_term_id` (`un_term_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_usermeta`
--

CREATE TABLE IF NOT EXISTS `blog_usermeta` (
  `umeta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=231 ;

-- --------------------------------------------------------

--
-- Table structure for table `blog_users`
--

CREATE TABLE IF NOT EXISTS `blog_users` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL DEFAULT '',
  `user_pass` varchar(64) NOT NULL DEFAULT '',
  `user_nicename` varchar(50) NOT NULL DEFAULT '',
  `user_email` varchar(100) NOT NULL DEFAULT '',
  `user_url` varchar(100) NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(60) NOT NULL DEFAULT '',
  `user_status` int(11) NOT NULL DEFAULT '0',
  `display_name` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_cities`
--

CREATE TABLE IF NOT EXISTS `web_facebook_cities` (
  `id_city` int(5) NOT NULL,
  `name` varchar(300) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `idiom` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `grow_90` int(11) DEFAULT NULL,
  `chart_history` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_city`),
  KEY `country_code` (`country_code`),
  KEY `idiom` (`idiom`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_cities_ages`
--

CREATE TABLE IF NOT EXISTS `web_facebook_cities_ages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_age` int(3) NOT NULL,
  `name` varchar(20) CHARACTER SET utf8 NOT NULL,
  `id_city` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_age` (`id_age`,`id_city`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=50401 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_cities_comportamientos`
--

CREATE TABLE IF NOT EXISTS `web_facebook_cities_comportamientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comportamiento` int(3) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_city` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_comportamiento` (`id_comportamiento`,`id_city`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31501 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_cities_interests`
--

CREATE TABLE IF NOT EXISTS `web_facebook_cities_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_interest` int(3) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_city` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `grow_30` int(10) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_interest` (`id_interest`,`id_city`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31501 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_cities_relationships`
--

CREATE TABLE IF NOT EXISTS `web_facebook_cities_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_relationship` int(3) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_city` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_relationship` (`id_relationship`,`id_city`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=75601 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_continents`
--

CREATE TABLE IF NOT EXISTS `web_facebook_continents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `total_user` int(10) NOT NULL,
  `total_female` int(10) NOT NULL,
  `total_male` int(10) NOT NULL,
  `grow_30` int(10) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_countries`
--

CREATE TABLE IF NOT EXISTS `web_facebook_countries` (
  `id_country` int(3) NOT NULL,
  `code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `name_en` varchar(200) CHARACTER SET utf8 NOT NULL,
  `abbreviation` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `slug` varchar(200) CHARACTER SET utf8 NOT NULL,
  `idiom` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `id_continent` int(1) NOT NULL,
  `supports_region` tinyint(1) NOT NULL,
  `supports_city` tinyint(1) NOT NULL,
  `total_user` int(10) NOT NULL,
  `total_female` int(10) NOT NULL,
  `total_male` int(10) NOT NULL,
  `audience_history` varchar(1000) CHARACTER SET utf8 NOT NULL,
  `audience_grow_90` int(10) DEFAULT NULL,
  `audience_grow_180` int(10) DEFAULT NULL,
  `audience_grow_270` int(10) DEFAULT NULL,
  `audience_grow_360` int(10) DEFAULT NULL,
  `audience_down_360` int(10) DEFAULT NULL,
  `audience_female_grow_30` int(10) DEFAULT NULL,
  `audience_male_grow_30` int(10) DEFAULT NULL,
  `general_ranking` int(3) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_country`),
  UNIQUE KEY `slug` (`slug`),
  KEY `code` (`code`,`idiom`,`id_continent`,`supports_region`,`supports_city`,`total_user`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_countries_ages`
--

CREATE TABLE IF NOT EXISTS `web_facebook_countries_ages` (
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
-- Table structure for table `web_facebook_countries_comportamientos`
--

CREATE TABLE IF NOT EXISTS `web_facebook_countries_comportamientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comportamiento` int(4) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nivel` int(1) NOT NULL,
  `nivel_superior` int(4) NOT NULL,
  `mobile_device` tinyint(1) NOT NULL,
  `mobile_os` varchar(10) CHARACTER SET utf8 DEFAULT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) DEFAULT NULL,
  `total_female` int(11) DEFAULT NULL,
  `total_male` int(11) DEFAULT NULL,
  `grow_1` int(10) DEFAULT NULL,
  `grow_3` int(10) DEFAULT NULL,
  `grow_7` int(10) DEFAULT NULL,
  `grow_15` int(10) DEFAULT NULL,
  `grow_30` int(10) DEFAULT NULL,
  `chart_history` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_interest` (`id_comportamiento`,`nivel`,`nivel_superior`,`country_code`),
  KEY `mobile_device` (`mobile_device`),
  KEY `mobile_os` (`mobile_os`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46437 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_countries_interests`
--

CREATE TABLE IF NOT EXISTS `web_facebook_countries_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_interest` int(4) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `nivel` int(1) NOT NULL,
  `nivel_superior` int(4) NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `grow_1` int(10) DEFAULT NULL,
  `grow_3` int(10) DEFAULT NULL,
  `grow_7` int(10) DEFAULT NULL,
  `grow_15` int(10) DEFAULT NULL,
  `grow_30` int(10) DEFAULT NULL,
  `chart_history` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_interest` (`id_interest`,`nivel`,`nivel_superior`,`country_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=81264 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_countries_languages`
--

CREATE TABLE IF NOT EXISTS `web_facebook_countries_languages` (
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
-- Table structure for table `web_facebook_countries_relationships`
--

CREATE TABLE IF NOT EXISTS `web_facebook_countries_relationships` (
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
-- Table structure for table `web_facebook_pages`
--

CREATE TABLE IF NOT EXISTS `web_facebook_pages` (
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
  `charts` text CHARACTER SET utf8 NOT NULL,
  `likes_grow_1` int(11) DEFAULT NULL,
  `likes_grow_7` int(11) DEFAULT NULL,
  `likes_grow_15` int(11) DEFAULT NULL,
  `likes_grow_30` int(11) DEFAULT NULL,
  `likes_grow_60` int(11) DEFAULT NULL,
  `talking_about` int(11) NOT NULL,
  `talking_about_grow_1` int(11) DEFAULT NULL,
  `talking_about_grow_7` int(11) DEFAULT NULL,
  `talking_about_grow_15` int(11) DEFAULT NULL,
  `talking_about_grow_30` int(11) DEFAULT NULL,
  `talking_about_grow_60` int(11) DEFAULT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `country_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `country_slug` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `country_ranking` int(11) DEFAULT NULL,
  `first_country_code` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `first_country_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `first_country_slug` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `idiom` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `category_id` int(5) DEFAULT NULL,
  `category_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `sub_category_id` int(5) DEFAULT NULL,
  `sub_category_name` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `general_ranking` int(11) DEFAULT NULL,
  `first_local_fans_country_ranking` int(11) DEFAULT NULL,
  `first_local_fans_country_audience` int(10) DEFAULT NULL,
  `country_audience` int(10) DEFAULT NULL,
  `in_owloo_from` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_page`),
  KEY `country_code` (`country_code`,`first_country_code`,`idiom`),
  KEY `likes` (`likes`),
  KEY `talking_about` (`talking_about`),
  KEY `idiom` (`idiom`),
  KEY `parent` (`parent`),
  KEY `category_id` (`category_id`),
  KEY `username` (`username`),
  KEY `category_name` (`category_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_pages_categories`
--

CREATE TABLE IF NOT EXISTS `web_facebook_pages_categories` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_pages_local_fans`
--

CREATE TABLE IF NOT EXISTS `web_facebook_pages_local_fans` (
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
  `country_slug` varchar(200) CHARACTER SET utf8 NOT NULL,
  `likes_local_fans` int(11) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_code` (`country_code`),
  KEY `likes` (`likes_local_fans`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=534373 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_page_local_fans_country`
--

CREATE TABLE IF NOT EXISTS `web_facebook_page_local_fans_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_page` int(11) NOT NULL,
  `id_country` int(3) NOT NULL,
  `likes` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_page` (`id_page`,`id_country`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5526161 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_regions`
--

CREATE TABLE IF NOT EXISTS `web_facebook_regions` (
  `id_region` int(5) NOT NULL,
  `name` varchar(200) CHARACTER SET utf8 NOT NULL,
  `country_code` varchar(2) CHARACTER SET utf8 NOT NULL,
  `idiom` varchar(2) CHARACTER SET utf8 DEFAULT NULL,
  `total_user` int(11) NOT NULL,
  `total_female` int(11) NOT NULL,
  `total_male` int(11) NOT NULL,
  `grow_90` int(11) DEFAULT NULL,
  `chart_history` varchar(1000) CHARACTER SET utf8 DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id_region`),
  KEY `country_code` (`country_code`),
  KEY `idiom` (`idiom`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_regions_ages`
--

CREATE TABLE IF NOT EXISTS `web_facebook_regions_ages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_age` int(3) NOT NULL,
  `name` varchar(20) NOT NULL,
  `id_region` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_age` (`id_age`,`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2121 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_regions_comportamientos`
--

CREATE TABLE IF NOT EXISTS `web_facebook_regions_comportamientos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_comportamiento` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `id_region` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_comportamiento` (`id_comportamiento`,`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1326 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_regions_interests`
--

CREATE TABLE IF NOT EXISTS `web_facebook_regions_interests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_interest` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `id_region` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `grow_30` int(10) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_interest` (`id_interest`,`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1326 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_facebook_regions_relationships`
--

CREATE TABLE IF NOT EXISTS `web_facebook_regions_relationships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_relationship` int(3) NOT NULL,
  `name` varchar(200) NOT NULL,
  `id_region` int(5) NOT NULL,
  `total_user` int(10) NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_relationship` (`id_relationship`,`id_region`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3181 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_instagram_categories`
--

CREATE TABLE IF NOT EXISTS `web_instagram_categories` (
  `id_category` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `web_instagram_profiles`
--

CREATE TABLE IF NOT EXISTS `web_instagram_profiles` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `name` varchar(200) NOT NULL,
  `bio` text NOT NULL,
  `website` varchar(500) NOT NULL,
  `picture` varchar(500) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `in_owloo_from` date NOT NULL,
  `followed_by_count` int(11) NOT NULL,
  `follows_count` int(11) NOT NULL,
  `media_count` int(11) NOT NULL,
  `charts` text NOT NULL,
  `accumulate_down_30` int(10) DEFAULT NULL,
  `followed_by_grow_1` int(11) DEFAULT NULL,
  `followed_by_grow_7` int(11) DEFAULT NULL,
  `followed_by_grow_15` int(11) DEFAULT NULL,
  `followed_by_grow_30` int(11) DEFAULT NULL,
  `most_mentions` varchar(1500) NOT NULL,
  `most_hashtags` varchar(1000) NOT NULL,
  `last_post` text NOT NULL,
  `post_by_engagement_rate` text NOT NULL,
  `general_ranking` int(10) DEFAULT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `web_twitter_profiles`
--

CREATE TABLE IF NOT EXISTS `web_twitter_profiles` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `screen_name` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `picture` varchar(200) NOT NULL,
  `cover` varchar(200) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `location` varchar(200) NOT NULL,
  `idiom` varchar(20) NOT NULL,
  `in_twitter_from` date NOT NULL,
  `followers_count` int(10) NOT NULL,
  `following_count` int(10) NOT NULL,
  `tweet_count` int(10) NOT NULL,
  `accumulate_down_30` int(10) NOT NULL,
  `charts` text NOT NULL,
  `average_growth` int(10) DEFAULT NULL,
  `followers_grow_1` int(10) DEFAULT NULL,
  `followers_grow_7` int(10) DEFAULT NULL,
  `followers_grow_15` int(10) DEFAULT NULL,
  `followers_grow_30` int(10) DEFAULT NULL,
  `most_mentions` varchar(1500) NOT NULL,
  `most_hashtags` varchar(1000) NOT NULL,
  `klout` varchar(1500) NOT NULL,
  `general_ranking` int(10) DEFAULT NULL,
  `in_owloo_from` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idiom` (`idiom`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5337 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
