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
-- Table structure for table `instagram_media_comments`
--

CREATE TABLE IF NOT EXISTS `instagram_media_comments` (
  `id` bigint(25) NOT NULL AUTO_INCREMENT,
  `id_profile` int(11) NOT NULL,
  `id_media` bigint(25) NOT NULL,
  `comment` text NOT NULL,
  `username` varchar(200) NOT NULL,
  `instagram_id` bigint(25) NOT NULL,
  `full_name` varchar(200) NOT NULL,
  `profile_picture` varchar(500) NOT NULL,
  `id_comment` varchar(50) NOT NULL,
  `created_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_profile` (`id_profile`,`id_media`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=220953 ;

--
-- Dumping data for table `instagram_media_comments`
--

INSERT INTO `instagram_media_comments` (`id`, `id_profile`, `id_media`, `comment`, `username`, `instagram_id`, `full_name`, `profile_picture`, `id_comment`, `created_time`) VALUES
(220929, 122, 138082, 'Eres mi idolo del basket', 'garciamigal_007', 1608904479, 'Juan.Carlos:)', 'https://igcdn-photos-e-a.akamaihd.net/hphotos-ak-xap1/t51.2885-19/928674_760390220702812_583094810_a.jpg', '880986092122747586', 1419241747),
(220930, 122, 138082, 'Nice shoes idol', 'maxene_laxa', 1521055167, 'franchesca maxene laxa', 'https://igcdn-photos-d-a.akamaihd.net/hphotos-ak-xap1/t51.2885-19/10809021_1497513097202387_287676496_a.jpg', '881106900023325010', 1419256149),
(220931, 122, 138082, 'I can''t wait till he wins the championship go 76ers hopefully he bring back a title for the 76ers what bout u guys @maxene_laxa  @rileyweis6 @garciamigal_007', 'blake.wilson12', 407814915, 'вlaĸe wιlѕon', 'https://igcdn-photos-d-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/10507840_1571905163032915_1658019796_a.jpg', '881140518678508155', 1419260156),
(220932, 122, 138082, 'Can you send me tho', 'gusbus_8', 1609490811, 'Gus Harris', 'https://instagramimages-a.akamaihd.net/profiles/anonymousUser.jpg', '881220667960799591', 1419269711),
(220933, 122, 138083, 'Derek jeter clap clap clap clap clap derek jeter...', 'jmulderrig5', 291595079, 'Jack Mulderrig', 'https://igcdn-photos-h-a.akamaihd.net/hphotos-ak-xaf1/t51.2885-19/10817844_337292713121151_355337604_a.jpg', '879926259634221685', 1419115405),
(220934, 122, 138083, 'Hi', 'jon_hikade', 681268810, 'Jonathan Hikade', 'https://igcdn-photos-b-a.akamaihd.net/hphotos-ak-xaf1/t51.2885-19/10848124_912907328719537_1416304312_a.jpg', '879984471095980904', 1419122345),
(220935, 122, 138083, 'Yeah JETER was just the best captain so sad he left ', 'suki_2004', 508965161, 'Suki  Hobbs', 'https://igcdn-photos-b-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/10860131_1376596285974041_1960719592_a.jpg', '880061044255961889', 1419131473),
(220936, 122, 138083, 'Re2pect', 'thatsniperwhogotthelooks', 1097176526, 'Ahmad Shiblak ', 'https://igcdn-photos-h-a.akamaihd.net/hphotos-ak-xpa1/t51.2885-19/10735034_855740977811823_698901843_a.jpg', '880172306222624966', 1419144737),
(220937, 122, 138083, 'How did he cheat @rockyboy2005', 'suki_2004', 508965161, 'Suki  Hobbs', 'https://igcdn-photos-b-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/10860131_1376596285974041_1960719592_a.jpg', '880765146757673431', 1419215409),
(220938, 122, 138084, '@mikaela_liamar', 'locohiram06', 528976978, 'Hiram', 'https://igcdn-photos-e-a.akamaihd.net/hphotos-ak-xaf1/t51.2885-19/10843666_822879371108428_898984732_a.jpg', '863988867781963889', 1417215520),
(220939, 122, 138084, 'TamirRice', 'dollazanddreams_11um8', 39024258, 'God', 'https://igcdn-photos-g-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/10838663_504510489690878_366106561_a.jpg', '868264607146914602', 1417725228),
(220940, 122, 138084, 'I thought Kevin Hart was Lance Stephenson and dwayne wade blew in his ear.', 'alecgator_', 309031961, 'A l e c', 'https://igcdn-photos-g-a.akamaihd.net/hphotos-ak-xpf1/t51.2885-19/10735343_857745504244510_19686918_a.jpg', '868791480373293228', 1417788036),
(220941, 122, 138084, 'Lebron James, Dwayne Wade, Chris Bosh and the whole miami heat was the franchise.', 'partyman_x3', 207818361, 'Enzo Vargas', 'https://igcdn-photos-a-a.akamaihd.net/hphotos-ak-xaf1/t51.2885-19/10724715_527787547357384_701329026_a.jpg', '870627779455561953', 1418006940),
(220942, 122, 138084, '#fannomatterwhat.  Always remember the 27 win streak close to history when u were with in miami with your true brothers', 'partyman_x3', 207818361, 'Enzo Vargas', 'https://igcdn-photos-a-a.akamaihd.net/hphotos-ak-xaf1/t51.2885-19/10724715_527787547357384_701329026_a.jpg', '870629245817795015', 1418007115),
(220943, 122, 138085, '2 of my favs sports and music dont get no better', 'iamsotrill', 1536842881, 'Fresh', 'https://igcdn-photos-b-a.akamaihd.net/hphotos-ak-xpa1/t51.2885-19/10731548_344813715692281_845131851_a.jpg', '876447015109211061', 1418700647),
(220944, 122, 138085, '@chenghan_ nwts', 'isaacmoo', 50224462, 'Isaac Moo', 'https://instagramimages-a.akamaihd.net/profiles/anonymousUser.jpg', '876934157379908368', 1418758719),
(220945, 122, 138085, '@eddiekiel ', 'max.collett', 380287, 'Max Collett', 'https://igcdn-photos-c-a.akamaihd.net/hphotos-ak-xpa1/t51.2885-19/925514_673742689347626_1984033637_a.jpg', '876987275195003252', 1418765051),
(220946, 122, 138085, '@carlos_est89', 'ma_har_ba', 471816564, '#67 FTR TheBlock , $lhs 2017', 'https://igcdn-photos-f-a.akamaihd.net/hphotos-ak-xaf1/t51.2885-19/10838652_613905708713901_1863946349_a.jpg', '880087670888936111', 1419134647),
(220947, 122, 138085, '2 best player in the game best rapper drake best basketball pk', 'byronmiller23', 1522704599, 'byron', 'https://igcdn-photos-h-a.akamaihd.net/hphotos-ak-xfa1/t51.2885-19/10865081_751667341587423_360578663_a.jpg', '880484869036650532', 1419181997),
(220948, 122, 138086, 'Pussy', 'javonruffin', 1334919883, 'Its that time ', 'https://igcdn-photos-h-a.akamaihd.net/hphotos-ak-xap1/t51.2885-19/10375724_1555881954647119_1667505568_a.jpg', '849510816268497429', 1415489602),
(220949, 122, 138086, '@isacarb223  don''t hate appreciate dont blame him because he wants to go home be happy he gave you guys 2 championships', 'mr.kush01', 1479961731, 'mr.kush01', 'https://igcdn-photos-b-a.akamaihd.net/hphotos-ak-xpa1/t51.2885-19/10808636_1505208526395169_1153468868_a.jpg', '851920559763173553', 1415776866),
(220950, 122, 138086, 'He didn''t give them shit. He went to Miami cause Cleveland wasn''t good enough the first go around. People forget d wade already had a championship.  So who helped who. And Cleveland stilll not good enough.', 'the_boys_five_time_champs', 1455924130, 'the_boys_five_time_champs', 'https://igcdn-photos-c-a.akamaihd.net/hphotos-ak-xfp1/t51.2885-19/10707127_496539803782762_703713613_a.jpg', '854565150526091913', 1416092126),
(220951, 122, 138086, '#andrewfromnba', 'serenazachariah', 494522434, 'Serena Zachariah ', 'https://igcdn-photos-a-a.akamaihd.net/hphotos-ak-xfp1/t51.2885-19/891474_1518966191721696_1466455376_a.jpg', '854735972985287084', 1416112489),
(220952, 122, 138086, '@iphonegiveaway10 just got me a iphone', 'nicorico46', 1464573836, 'Nico Engvall', 'https://igcdn-photos-f-a.akamaihd.net/hphotos-ak-xfp1/t51.2885-19/10483509_629161313866061_1096920618_a.jpg', '855242779000385683', 1416172905);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
