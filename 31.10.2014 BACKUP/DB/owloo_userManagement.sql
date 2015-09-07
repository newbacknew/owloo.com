-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2014 at 12:33 AM
-- Server version: 5.5.37-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `owloo_userManagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `buy_twitter_stats`
--

CREATE TABLE IF NOT EXISTS `buy_twitter_stats` (
  `id_buy` int(11) NOT NULL AUTO_INCREMENT,
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_twitter_id` bigint(20) NOT NULL,
  `owloo_twitter_screen_name` varchar(200) NOT NULL,
  `code` varchar(10) NOT NULL,
  `date_since` date NOT NULL,
  `date_until` date NOT NULL,
  `price` float NOT NULL,
  `currency` varchar(10) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `date_pay` datetime DEFAULT NULL,
  `user_ip` varchar(20) NOT NULL,
  `activo` varchar(2) NOT NULL DEFAULT 'si',
  `date` datetime NOT NULL,
  PRIMARY KEY (`id_buy`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=172 ;

-- --------------------------------------------------------

--
-- Table structure for table `buy_twitter_stats_paypal`
--

CREATE TABLE IF NOT EXISTS `buy_twitter_stats_paypal` (
  `id_buy_paypal` int(11) NOT NULL AUTO_INCREMENT,
  `txn_id` varchar(50) NOT NULL,
  `id_buy` int(11) NOT NULL,
  `code_buy` varchar(10) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `payment_amount` float NOT NULL,
  `payment_currency` varchar(10) NOT NULL,
  `payer_email` varchar(200) NOT NULL,
  `receiver_email` varchar(200) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id_buy_paypal`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `buy_twitter_stats_paypal_log`
--

CREATE TABLE IF NOT EXISTS `buy_twitter_stats_paypal_log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_buy_paypal` int(11) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `post` varchar(2000) NOT NULL,
  `part_access` varchar(20) NOT NULL,
  `ip_address` varchar(20) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_categories`
--

CREATE TABLE IF NOT EXISTS `forum_categories` (
  `c_id` smallint(5) NOT NULL AUTO_INCREMENT,
  `c_sort` smallint(3) NOT NULL,
  `c_sub` tinyint(1) NOT NULL,
  `c_name` char(128) NOT NULL,
  `c_permissions` text NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE IF NOT EXISTS `forum_posts` (
  `p_id` int(10) NOT NULL AUTO_INCREMENT,
  `p_author` int(12) NOT NULL,
  `p_postdate` int(11) NOT NULL,
  `p_replydate` int(11) NOT NULL,
  `p_istopic` tinyint(1) NOT NULL DEFAULT '0',
  `p_topictitle` char(128) NOT NULL,
  `p_catid` smallint(5) NOT NULL,
  `p_topicid` int(10) NOT NULL,
  `p_post` mediumtext NOT NULL,
  `p_sticky` tinyint(1) NOT NULL DEFAULT '0',
  `p_locked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='p_sticky' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE IF NOT EXISTS `friends` (
  `f_id` int(24) NOT NULL,
  `user_id` int(10) NOT NULL,
  `friend_id` int(10) NOT NULL,
  `status` enum('pending','accepted') NOT NULL,
  `date` int(30) NOT NULL,
  UNIQUE KEY `f_id` (`f_id`),
  KEY `user_id` (`user_id`,`friend_id`),
  KEY `friend_id` (`friend_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invites`
--

CREATE TABLE IF NOT EXISTS `invites` (
  `i_id` int(12) NOT NULL AUTO_INCREMENT,
  `i_by` int(12) NOT NULL,
  `i_to` char(100) NOT NULL,
  `i_acceptedby` int(11) NOT NULL,
  `i_code` char(32) NOT NULL,
  `i_status` tinyint(1) NOT NULL,
  `i_date` int(12) NOT NULL,
  PRIMARY KEY (`i_id`),
  UNIQUE KEY `i_to` (`i_to`),
  KEY `i_by` (`i_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `username` char(50) NOT NULL,
  `triggers` text NOT NULL,
  `password` char(64) NOT NULL,
  `membergroup` tinyint(4) NOT NULL DEFAULT '1',
  `other_membergroups` char(255) NOT NULL,
  `avatar` char(255) NOT NULL,
  `reset_key` char(40) NOT NULL,
  `reset_timer` int(11) NOT NULL,
  `email` char(100) NOT NULL,
  `date_registered` int(12) NOT NULL,
  `lastactivity` int(12) NOT NULL,
  `activation_key` char(22) NOT NULL,
  `session` char(64) NOT NULL,
  `account_key` char(8) NOT NULL,
  `firstadmin` tinyint(1) NOT NULL DEFAULT '0',
  `invites` smallint(5) unsigned NOT NULL DEFAULT '0',
  `subscription_id` int(10) NOT NULL,
  `subscription_start` int(11) NOT NULL,
  `f_posts` mediumint(7) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4114 ;

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

CREATE TABLE IF NOT EXISTS `membership_plans` (
  `ms_id` int(10) NOT NULL AUTO_INCREMENT,
  `ms_name` char(200) NOT NULL,
  `ms_description` text NOT NULL,
  `ms_price` decimal(10,2) NOT NULL,
  `ms_duration` int(10) NOT NULL,
  `ms_durationtype` tinyint(1) NOT NULL,
  `ms_groupid` int(10) NOT NULL,
  PRIMARY KEY (`ms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_groups`
--

CREATE TABLE IF NOT EXISTS `member_groups` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `colour` char(6) NOT NULL,
  `default_group` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `member_payments`
--

CREATE TABLE IF NOT EXISTS `member_payments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(12) NOT NULL,
  `plan_id` int(10) NOT NULL,
  `g_id` int(10) NOT NULL,
  `plan_name` char(100) NOT NULL,
  `payment_date` int(11) NOT NULL,
  `expiry_date` int(11) NOT NULL,
  `plan_length` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `returndata` text NOT NULL,
  `status` char(20) NOT NULL DEFAULT '0',
  `trans_id` char(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trans_id` (`trans_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `private_messages`
--

CREATE TABLE IF NOT EXISTS `private_messages` (
  `pm_id` int(10) NOT NULL AUTO_INCREMENT,
  `to_user` int(12) NOT NULL,
  `from_user` int(12) NOT NULL,
  `subject` char(200) NOT NULL,
  `message` text NOT NULL,
  `date` int(30) NOT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  PRIMARY KEY (`pm_id`),
  KEY `to_user` (`to_user`),
  KEY `from_user` (`from_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3540 ;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `u_id` int(10) NOT NULL,
  `f_id` int(10) NOT NULL,
  `f_val` text NOT NULL,
  KEY `f_id` (`f_id`),
  KEY `u_id` (`u_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `profile_fields`
--

CREATE TABLE IF NOT EXISTS `profile_fields` (
  `p_id` int(10) NOT NULL AUTO_INCREMENT,
  `p_type` tinyint(1) NOT NULL,
  `p_options` text NOT NULL,
  `p_label` char(75) NOT NULL,
  `p_group` char(50) NOT NULL,
  `p_profile` tinyint(1) NOT NULL DEFAULT '0',
  `p_signup` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` char(64) NOT NULL DEFAULT '',
  `session_data` text NOT NULL,
  `session_expire` int(20) NOT NULL DEFAULT '0',
  `session_agent` char(64) NOT NULL,
  `session_ip` char(64) NOT NULL,
  `session_host` char(64) NOT NULL,
  `session_key` char(64) NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `social_connect`
--

CREATE TABLE IF NOT EXISTS `social_connect` (
  `member_id` int(12) NOT NULL,
  `provider_name` char(20) NOT NULL,
  `provider_uid` char(255) NOT NULL,
  `provider_identify` char(35) NOT NULL,
  UNIQUE KEY `provider_identify` (`provider_identify`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `invites`
--
ALTER TABLE `invites`
  ADD CONSTRAINT `invites_ibfk_2` FOREIGN KEY (`i_by`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `private_messages`
--
ALTER TABLE `private_messages`
  ADD CONSTRAINT `private_messages_ibfk_1` FOREIGN KEY (`to_user`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`f_id`) REFERENCES `profile_fields` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profiles_ibfk_2` FOREIGN KEY (`u_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `social_connect`
--
ALTER TABLE `social_connect`
  ADD CONSTRAINT `social_connect_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
