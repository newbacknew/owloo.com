-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 31, 2014 at 08:02 PM
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
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id_category` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(100) NOT NULL,
  `subcategory` varchar(100) NOT NULL,
  PRIMARY KEY (`id_category`),
  UNIQUE KEY `id_category` (`id_category`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=65 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id_category`, `category`, `subcategory`) VALUES
(1, 'Actividades', 'Juegos (consola)'),
(2, 'Actividades', 'Juegos (sociales / online)'),
(3, 'Actividades', 'Viajes'),
(4, 'Actividades', 'Comida / restaurantes'),
(5, 'Actividades', 'Literatura / Lectura'),
(6, 'Intereses', 'Automóviles'),
(7, 'Intereses', 'Cerveza / vino / licores'),
(8, 'Intereses', 'Organizaciones benéficas / causas'),
(9, 'Intereses', 'Educación / enseñanza'),
(10, 'Intereses', 'Entretenimiento (TV)'),
(11, 'Intereses', 'Medio ambiente'),
(12, 'Intereses', 'Salud y bienestar'),
(13, 'Intereses', 'Hogar y jardinería'),
(14, 'Intereses', 'Noticias'),
(15, 'Intereses', 'Mascotas (Todas)'),
(16, 'Intereses', 'Cultura pop'),
(17, 'Usuarios de celulares (todos)', 'Usuarios de celulares (todos)'),
(18, 'Mobile Users (Android)', 'Android (todos)'),
(19, 'Mobile Users (iOS)', 'iPad (1, 2 y 3)'),
(20, 'Mobile Users (iOS)', 'iPhone (4, 4S y 5)'),
(21, 'Mobile Users (iOS)', 'iPod Touch'),
(22, 'Mobile Users (Other OS)', 'RIM / Blackberry'),
(23, 'Mobile Users (Other OS)', 'Windows'),
(24, 'Deportes', 'Todos los deportes'),
(25, 'Deportes', 'Béisbol'),
(26, 'Deportes', 'Baloncesto'),
(27, 'Deportes', 'Deportes extremos'),
(28, 'Deportes', 'Fútbol americano'),
(29, 'Deportes', 'Golf'),
(30, 'Deportes', 'Hockey sobre hielo'),
(31, 'Deportes', 'Deportes motor / NASCAR'),
(32, 'Deportes', 'Fútbol'),
(33, 'Deportes', 'Tenis'),
(34, 'Actividades', 'Cocina'),
(35, 'Actividades', 'Baile'),
(36, 'Actividades', 'Bricolaje / manualidades'),
(37, 'Actividades', 'Planificación de eventos'),
(38, 'Actividades', 'Jardinería'),
(39, 'Actividades', 'Actividades deportivas al aire libre'),
(40, 'Actividades', 'Carga de fotos'),
(41, 'Actividades', 'Fotografía'),
(42, 'Deportes', 'Cricket'),
(43, 'Deportes', 'Deportes de fantasía'),
(44, 'Usuarios de celulares (todos)', 'Usuarios de celulares básicos'),
(45, 'Usuarios de celulares (todos)', 'Nuevos usuarios de smartphone'),
(46, 'Usuarios de celulares (todos)', 'Usuarios de smartphone / tabletas'),
(47, 'Mobile Users (Android)', 'Android (todos)'),
(48, 'Mobile Users (Android)', 'HTC'),
(49, 'Mobile Users (Android)', 'LG'),
(50, 'Mobile Users (Android)', 'Motorola'),
(51, 'Mobile Users (Android)', 'Samsung'),
(52, 'Mobile Users (Android)', 'Sony'),
(53, 'Mobile Users (Android)', 'Android (otro)'),
(54, 'Usuarios móviles (iOS)', 'iOS / Apple (todos)'),
(55, 'Usuarios móviles (iOS)', 'iPad 1'),
(56, 'Usuarios móviles (iOS)', 'iPad 2'),
(57, 'Usuarios móviles (iOS)', 'iPad 3'),
(58, 'Usuarios móviles (iOS)', 'iPhone 4'),
(59, 'Usuarios móviles (iOS)', 'iPhone 4S'),
(60, 'Usuarios móviles (iOS)', 'iPhone 5'),
(61, 'Compras', 'Productos de belleza'),
(62, 'Compras', 'Electrodomésticos'),
(63, 'Compras', 'Moda'),
(64, 'Compras', 'Artículos de lujo');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
