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
-- Database: `owloo_twitter`
--

-- --------------------------------------------------------

--
-- Table structure for table `owloo_curse_words`
--

CREATE TABLE IF NOT EXISTS `owloo_curse_words` (
  `owloo_word_id` int(10) NOT NULL AUTO_INCREMENT,
  `owloo_curse_text` varchar(50) NOT NULL,
  PRIMARY KEY (`owloo_word_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_daily_track`
--

CREATE TABLE IF NOT EXISTS `owloo_daily_track` (
  `owloo_user_twitter_id` bigint(20) NOT NULL,
  `owloo_followers_count` bigint(20) NOT NULL,
  `owloo_following_count` bigint(20) NOT NULL,
  `owloo_tweetcount` bigint(20) NOT NULL,
  `owloo_listed_count` bigint(20) NOT NULL,
  `owloo_updated_on` date NOT NULL,
  KEY `owloo_user_twitter_id` (`owloo_user_twitter_id`),
  KEY `owloo_updated_on` (`owloo_updated_on`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_hashtag`
--

CREATE TABLE IF NOT EXISTS `owloo_hashtag` (
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_hashword` varchar(200) NOT NULL,
  `owloo_id_str` bigint(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_last_id_str`
--

CREATE TABLE IF NOT EXISTS `owloo_last_id_str` (
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_id_str` bigint(30) NOT NULL,
  `owloo_tweet_count` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_login_user`
--

CREATE TABLE IF NOT EXISTS `owloo_login_user` (
  `owloo_user_id` int(4) NOT NULL AUTO_INCREMENT,
  `owloo_user_name` varchar(20) NOT NULL,
  `owloo_user_password` varbinary(250) NOT NULL,
  `owloo_created_on` date NOT NULL,
  PRIMARY KEY (`owloo_user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_mentions`
--

CREATE TABLE IF NOT EXISTS `owloo_mentions` (
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_screenanme` varchar(50) NOT NULL,
  `owloo_id_str` bigint(30) NOT NULL,
  KEY `owloo_user_id` (`owloo_user_id`),
  KEY `owloo_screenanme` (`owloo_screenanme`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_profile_log`
--

CREATE TABLE IF NOT EXISTS `owloo_profile_log` (
  `owloo_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_client_ip` varchar(25) NOT NULL,
  `owloo_entry_date` date NOT NULL,
  PRIMARY KEY (`owloo_log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6441 ;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_tweet_curse_data`
--

CREATE TABLE IF NOT EXISTS `owloo_tweet_curse_data` (
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_id_str` bigint(20) NOT NULL,
  `owloo_tweet_curse` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_user_master`
--

CREATE TABLE IF NOT EXISTS `owloo_user_master` (
  `owloo_user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `owloo_user_twitter_id` varchar(40) NOT NULL,
  `owloo_user_name` varchar(50) NOT NULL,
  `owloo_screen_name` varchar(50) NOT NULL,
  `owloo_user_photo` varchar(150) NOT NULL,
  `owloo_user_description` text NOT NULL,
  `owloo_user_location` varchar(200) NOT NULL,
  `owloo_user_language` varchar(20) NOT NULL,
  `owloo_user_verified_account` int(1) NOT NULL,
  `owloo_user_timezone` varchar(100) NOT NULL,
  `owloo_user_created_on` varchar(100) NOT NULL,
  `owloo_followers_count` bigint(20) NOT NULL,
  `owloo_following_count` bigint(20) NOT NULL,
  `owloo_tweetcount` bigint(20) NOT NULL,
  `owloo_listed_count` bigint(20) NOT NULL,
  `owloo_user_status` int(1) NOT NULL,
  `owloo_created_on` date NOT NULL,
  `owloo_updated_on` date NOT NULL,
  PRIMARY KEY (`owloo_user_id`),
  KEY `owloo_user_status` (`owloo_user_status`) COMMENT 'owloo_user_status'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4567 ;

-- --------------------------------------------------------

--
-- Table structure for table `owloo_user_rating`
--

CREATE TABLE IF NOT EXISTS `owloo_user_rating` (
  `owloo_rating_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_rating_points` int(2) NOT NULL,
  `owloo_rating_ip` varchar(20) NOT NULL,
  `owloo_rating_date` date NOT NULL,
  PRIMARY KEY (`owloo_rating_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=387 ;

-- --------------------------------------------------------

--
-- Table structure for table `owoo_tweet_data`
--

CREATE TABLE IF NOT EXISTS `owoo_tweet_data` (
  `owloo_user_id` bigint(20) NOT NULL,
  `owloo_id_str` bigint(20) NOT NULL,
  `owloo_tweet_text` text NOT NULL,
  `owloo_tweet_time` varchar(10) NOT NULL,
  `owloo_tweet_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
