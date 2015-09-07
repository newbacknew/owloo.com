-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:24 PM
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
-- Table structure for table `instagram_media`
--

CREATE TABLE IF NOT EXISTS `instagram_media` (
  `id_media` bigint(25) NOT NULL AUTO_INCREMENT,
  `id_profile` int(11) NOT NULL,
  `id_instagram_media` varchar(50) NOT NULL,
  `caption_text` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `images_standard_resolution` varchar(200) NOT NULL,
  `videos_standard_resolution` varchar(200) DEFAULT NULL,
  `filter` varchar(50) NOT NULL,
  `comments_count` int(10) NOT NULL,
  `likes_count` int(10) NOT NULL,
  `location_latitude` decimal(10,8) DEFAULT NULL,
  `location_longitude` decimal(10,8) DEFAULT NULL,
  `location_name` varchar(100) DEFAULT NULL,
  `location_id` bigint(20) DEFAULT NULL,
  `link` varchar(200) NOT NULL,
  `created_time` int(11) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `updated_at` datetime NOT NULL,
  `date_add_in_owloo` date NOT NULL,
  PRIMARY KEY (`id_media`),
  UNIQUE KEY `id_instagram_media` (`id_instagram_media`),
  KEY `id_profile` (`id_profile`),
  KEY `type` (`type`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=147048 ;

--
-- Dumping data for table `instagram_media`
--

INSERT INTO `instagram_media` (`id_media`, `id_profile`, `id_instagram_media`, `caption_text`, `type`, `images_standard_resolution`, `videos_standard_resolution`, `filter`, `comments_count`, `likes_count`, `location_latitude`, `location_longitude`, `location_name`, `location_id`, `link`, `created_time`, `active`, `updated_at`, `date_add_in_owloo`) VALUES
(147031, 112, '872672530173657969_4348967', 'Link''s starter kit.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10832005_302648693267256_1975615186_n.jpg', NULL, 'Normal', 240, 5774, '40.74182801', '-74.00209475', NULL, NULL, 'http://instagram.com/p/wcWwrmPgNx/', 1418250694, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147032, 112, '872624519485719069_4348967', 'This Christmas, I choose you. ', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10838581_790945600953729_2098340680_n.jpg', NULL, 'Amaro', 440, 7167, '40.74183843', '-74.00224205', NULL, NULL, 'http://instagram.com/p/wcL2CKPgId/', 1418244970, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147033, 112, '872484166631817813_4348967', 'Good morning. ', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xfa1/t51.2885-15/10848452_361750727339410_428870605_n.jpg', NULL, 'X-Pro II', 1371, 11879, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wbr7oYPgJV/', 1418228239, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147034, 112, '871947413294547891_4348967', 'Move it, football head.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xfa1/t51.2885-15/10838915_634401643331571_290025790_n.jpg', NULL, 'Normal', 514, 9009, '40.74186638', '-74.00207293', NULL, NULL, 'http://instagram.com/p/wZx414PgOz/', 1418164253, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147035, 112, '871902131169132795_4348967', 'Crafting: expectations vs reality.', 'image', 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10838728_829811277058073_977474996_n.jpg', NULL, 'Normal', 430, 5336, '40.74190133', '-74.00199138', NULL, NULL, 'http://instagram.com/p/wZnl5nPgD7/', 1418158855, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147036, 112, '871875513646186790_4348967', 'Fan art of our hero and muse.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10838577_359119677599307_940497293_n.jpg', NULL, 'Normal', 612, 6063, '40.74187031', '-74.00207510', NULL, NULL, 'http://instagram.com/p/wZhikHPgEm/', 1418155682, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147037, 112, '871227056614932660_4348967', 'Happy Monday.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10817715_892961820735844_650918213_n.jpg', NULL, 'Mayfair', 558, 9847, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wXOGRcPgC0/', 1418078380, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147038, 112, '871192620330516737_4348967', 'The greatest love story of our generation.', 'image', 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10860108_593579094080648_2123902838_n.jpg', NULL, 'Normal', 790, 9111, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wXGRKJvgEB/', 1418074275, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147039, 112, '871070637295010182_4348967', '#nowplaying the cure for Monday blues.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10843801_1576174312618725_758176116_n.jpg', NULL, 'Amaro', 184, 6240, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wWqiEmPgGG/', 1418059733, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147040, 112, '869027831160374261_4348967', 'A startling revelation about our favorite emoji ', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xap1/t51.2885-15/10808469_565583513544215_85879668_n.jpg', NULL, 'Amaro', 1896, 10148, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wPaDVIPgP1/', 1417816212, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147041, 112, '868975431619838648_4348967', '', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xfa1/t51.2885-15/10843808_1632864983607469_1064096640_n.jpg', NULL, 'Normal', 257, 3159, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wPOI0QPgK4/', 1417809965, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147042, 112, '868904260966678904_4348967', 'Let''s play a game! Can you find the five differences? (...I hope that guy is ok)', 'video', 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10852584_811879155535980_572413623_n.jpg', 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t50.2886-16/10831524_1749254471966277_1423451256_n.mp4', 'Normal', 158, 2684, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wO99JavgF4/', 1417801481, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147043, 112, '868276378347110582_4348967', 'Schmowzow! Finn''s essentials.', 'image', 'http://scontent-a.cdninstagram.com/hphotos-xaf1/t51.2885-15/10831809_1007552519274409_799926016_n.jpg', NULL, 'Normal', 148, 5609, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wMvMQKPgC2/', 1417726631, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147044, 112, '868228880370893758_4348967', 'I wish this was real.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xap1/t51.2885-15/10809861_715787451850154_1836146903_n.jpg', NULL, 'Normal', 1324, 8034, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wMkZEOPgO-/', 1417720969, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147045, 112, '867752437878752155_4348967', 'Tiny sushi set made of paper by @adamtots ', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xap1/t51.2885-15/10809606_327340000785722_872947336_n.jpg', NULL, 'Normal', 234, 5963, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wK4D6kvgOb/', 1417664173, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147046, 112, '867592439726998162_4348967', 'You should want a bad Zig like this.', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xaf1/t51.2885-15/10818121_773281586041890_685421434_n.jpg', NULL, 'Normal', 197, 5180, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wKTrorvgKS/', 1417645100, 1, '2014-12-22 19:00:03', '2014-12-22'),
(147047, 112, '867556734808227852_4348967', '', 'image', 'http://scontent-b.cdninstagram.com/hphotos-xfa1/t51.2885-15/10843952_542117602592479_519638581_n.jpg', NULL, 'Normal', 269, 6205, NULL, NULL, NULL, NULL, 'http://instagram.com/p/wKLkD4vgAM/', 1417640843, 1, '2014-12-22 19:00:03', '2014-12-22');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
