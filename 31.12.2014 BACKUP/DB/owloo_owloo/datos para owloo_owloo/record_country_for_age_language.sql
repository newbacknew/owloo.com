-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:29 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147236 ;

--
-- Dumping data for table `record_country_for_age_language`
--

INSERT INTO `record_country_for_age_language` (`id`, `id_country`, `rango_13_15`, `rango_16_17`, `rango_18_28`, `rango_29_34`, `rango_35_44`, `rango_45_54`, `rango_55_64`, `rango_65_65`, `language_spanish`, `language_english`, `language_chinese`, `language_portuguese`, `language_hindi`, `language_frances`, `language_aleman`, `language_italiano`, `language_ruso`, `language_japones`, `language_coreano`, `language_holandes`, `language_arabe`, `language_bengali`, `language_turco`, `language_malayo`, `language_polaco`, `language_indonesio`, `language_filipino`, `language_tailandes`, `language_vietnamita`, `relationship_single`, `relationship_has_a_relationship`, `relationship_married`, `relationship_comprometido`, `education_en_la_escuela_secundaria`, `education_en_la_universidad`, `education_con_estudios_universitarios`, `date`) VALUES
(147224, 57, 128000, 170000, 1060000, 480000, 640000, 320000, 156000, 72000, 40000, 1180000, 2800, 3000, 860, 36000, 60000, 24000, 102000, 3400, 1040, 2800, 9000, 260, 122000, 120, 3600, 560, 440, 200, 460, 460000, 400000, 500000, 50000, 24000, 66000, 1180000, '2014-12-31'),
(147225, 80, 144000, 200000, 1240000, 360000, 300000, 130000, 50000, 24000, 2400000, 154000, 5600, 180000, 300, 8800, 16800, 16000, 1840, 6000, 3000, 180, 4800, 100, 820, 80, 460, 440, 140, 160, 60, 540000, 240000, 260000, 62000, 26000, 78000, 1100000, '2014-12-31'),
(147226, 39, 2400000, 3400000, 22000000, 5800000, 5000000, 2200000, 900000, 500000, 400000, 40000000, 340000, 26000, 18600, 160000, 64000, 68000, 19400, 460000, 360000, 16800, 220000, 3400, 10000, 34000, 3400, 24000, 6400000, 11400, 9200, 8600000, 4800000, 5800000, 840000, 300000, 1180000, 22000000, '2014-12-31'),
(147227, 100, 56000, 92000, 800000, 172000, 126000, 54000, 22000, 14200, 50000, 500000, 3800, 3400, 400, 38000, 24000, 240000, 4000, 1360, 400, 940, 5800, 200, 12400, 220, 580, 640, 300, 180, 100, 280000, 82000, 148000, 50000, 8200, 26000, 600000, '2014-12-31'),
(147228, 17, 1140000, 1660000, 10600000, 4600000, 5400000, 3400000, 2000000, 1380000, 1460000, 3400000, 108000, 320000, 17800, 28000000, 480000, 440000, 120000, 88000, 22000, 38000, 300000, 8800, 154000, 5600, 54000, 19800, 15200, 14600, 24000, 5400000, 4800000, 3800000, 640000, 280000, 560000, 11000000, '2014-12-31'),
(147229, 58, 172000, 240000, 1620000, 700000, 880000, 400000, 220000, 152000, 42000, 440000, 5000, 3400, 1180, 46000, 130000, 18400, 80000, 5600, 1780, 2800, 6000, 260, 3200, 360, 16400, 1940, 1480, 720, 24000, 720000, 740000, 540000, 84000, 72000, 82000, 1180000, '2014-12-31'),
(147230, 40, 1100000, 1040000, 5400000, 2200000, 1920000, 740000, 480000, 240000, 90000, 1200000, 14000, 8600, 3000, 114000, 380000, 52000, 162000, 17400, 4000, 8200, 13600, 760, 8000, 520, 12600000, 1300, 1400, 760, 6000, 2200000, 1560000, 1700000, 340000, 480000, 560000, 4400000, '2014-12-31'),
(147231, 18, 880000, 1640000, 10400000, 4400000, 5000000, 3800000, 1560000, 820000, 720000, 5200000, 86000, 112000, 30000, 1140000, 26000000, 380000, 440000, 52000, 19200, 74000, 200000, 6200, 600000, 3800, 340000, 24000, 24000, 24000, 36000, 4400000, 4600000, 4800000, 500000, 580000, 820000, 9600000, '2014-12-31'),
(147232, 59, 28000, 42000, 300000, 144000, 164000, 84000, 42000, 22000, 13000, 400000, 1100, 980, 160, 7200, 36000, 16800, 4400, 900, 220, 660, 1100, 40, 2800, 60, 440, 200, 180, 160, 60, 132000, 138000, 114000, 14400, 6800, 14200, 280000, '2014-12-31'),
(147233, 60, 9200, 11000, 64000, 30000, 42000, 36000, 26000, 18400, 6400, 160000, 1020, 1040, 160, 4000, 6400, 1520, 1500, 720, 240, 560, 480, 20, 220, 20, 7200, 140, 660, 720, 300, 44000, 38000, 60000, 10800, 2800, 11400, 104000, '2014-12-31'),
(147234, 19, 122000, 220000, 1620000, 800000, 1020000, 520000, 190000, 120000, 70000, 1100000, 15600, 10200, 4400, 168000, 152000, 98000, 50000, 10400, 3400, 5400, 36000, 2800, 44000, 760, 8400, 2800, 4600, 900, 1120, 760000, 360000, 780000, 58000, 38000, 100000, 1860000, '2014-12-31'),
(147235, 20, 108000, 170000, 1620000, 900000, 900000, 500000, 260000, 192000, 16400, 1540000, 3200000, 3200, 6200, 22000, 9400, 4600, 2800, 56000, 17400, 1660, 8200, 700, 1200, 4400, 560, 78000, 34000, 10400, 6400, 660000, 260000, 660000, 52000, 46000, 102000, 1340000, '2014-12-31');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
