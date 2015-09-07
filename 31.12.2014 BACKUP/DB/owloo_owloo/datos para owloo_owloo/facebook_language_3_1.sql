-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:05 PM
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

--
-- Dumping data for table `facebook_language_3_1`
--

INSERT INTO `facebook_language_3_1` (`id_language`, `key_language`, `nombre`, `name`, `active_fb_get_data`, `active`) VALUES
(1, 6, 'Inglés (Estados Unidos)', 'English (US)', 1, 1),
(2, 1, 'Catalán', 'Català', 1, 1),
(3, 2, 'Checo', 'Čeština', 1, 1),
(4, 3, 'Galés', 'Cymraeg', 1, 1),
(5, 4, 'Danés', 'Dansk', 1, 1),
(6, 5, 'Alemán', 'Deutsch', 1, 1),
(7, 23, 'Español', NULL, 1, 1),
(8, 7, 'Español (España)', NULL, 1, 1),
(9, 8, 'Finlandés', 'Suomi', 1, 1),
(10, 9, 'Francés (Francia)', 'Français (France)', 1, 1),
(11, 30, 'Húngaro', 'Magyar', 1, 1),
(12, 10, 'Italiano', NULL, 1, 1),
(13, 11, 'Japonés', '日本語', 1, 1),
(14, 12, 'Coreano', '한국어', 1, 1),
(15, 13, 'Noruego (Bokmål)', 'Norsk (bokmål)', 1, 1),
(16, 14, 'Holandés', 'Nederlands', 1, 1),
(17, 15, 'Polaco', 'Polski', 1, 1),
(18, 16, 'Portugués (Brasil)', 'Português (Brasil)', 1, 1),
(19, 31, 'Portugués (Portugal)', 'Português (Portugal)', 1, 1),
(20, 32, 'Rumano', 'Română', 1, 1),
(21, 17, 'Ruso', 'Русский', 1, 1),
(22, 33, 'Eslovaco', 'Slovenčina', 1, 1),
(23, 34, 'Esloveno', 'Slovenščina', 1, 1),
(24, 18, 'Sueco', 'Svenska', 1, 1),
(25, 35, 'Tailandés', 'ภาษาไทย', 1, 1),
(26, 19, 'Turco', 'Türkçe', 1, 1),
(27, 20, 'Chino Simplificado (China)', '中文(简体)', 1, 1),
(28, 21, 'Chino Tradicional (Hong Kong)', '中文(香港)', 1, 1),
(29, 22, 'Chino Tradicional (Taiwán)', '中文(台灣)', 1, 1),
(30, 36, 'Afrikáans', 'Afrikaans', 1, 1),
(31, 45, 'Bengalí', 'বাংলা', 1, 1),
(32, 37, 'Búlgaro', 'Български', 1, 1),
(33, 38, 'Croata', 'Hrvatski', 1, 1),
(34, 24, 'Inglés (Reino Unido)', 'English (UK)', 1, 1),
(35, 44, 'Francés (Canadá)', 'Français (Canada)', 1, 1),
(36, 39, 'Griego', 'Ελληνικά', 1, 1),
(37, 46, 'Hindi', 'हिन्दी', 1, 1),
(38, 25, 'Indonesio', 'Bahasa Indonesia', 1, 1),
(39, 40, 'Lituano', 'Lietuvių', 1, 1),
(40, 41, 'Malayo', 'Bahasa Melayu', 1, 1),
(41, 47, 'Punyabí', 'ਪੰਜਾਬੀ', 1, 1),
(42, 42, 'Serbio', 'Српски', 1, 1),
(43, 26, 'Filipino', NULL, 1, 1),
(44, 48, 'Tamil', 'தமிழ்', 1, 1),
(45, 49, 'Telugú', 'తెలుగు', 1, 1),
(46, 50, 'Malabar', 'മലയാളം', 1, 1),
(47, 52, 'Ucraniano', 'Українська', 1, 1),
(48, 27, 'Vietnamita', 'Tiếng Việt', 1, 1),
(49, 28, 'Árabe', 'العربية', 1, 1),
(50, 29, 'Hebreo', 'עברית', 1, 1),
(51, 1001, 'Inglés (todo)', NULL, 1, 1),
(52, 1002, 'Español (todos)', NULL, 1, 1),
(53, 1003, 'Francés (Todo)', NULL, 1, 1),
(54, 1004, 'Chino (todo)', NULL, 1, 1),
(55, 1005, 'Portugués (todo)', NULL, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
