-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 02, 2013 at 01:29 AM
-- Server version: 5.0.96-community
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `thesoftw_demos`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pagination`
--

CREATE TABLE IF NOT EXISTS `tbl_pagination` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `age` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

--
-- Dumping data for table `tbl_pagination`
--

INSERT INTO `tbl_pagination` (`id`, `name`, `age`) VALUES
(1, 'name1', 1),
(2, 'demo2', 7),
(3, 'demo3', 8),
(4, 'demo4', 9),
(5, 'demo5', 10),
(6, 'demo6', 11),
(7, 'demo7', 12),
(8, 'demo8', 13),
(9, 'demo9', 14),
(10, 'demo10', 15),
(11, 'demo11', 16),
(12, 'demo12', 17),
(13, 'demo13', 18),
(14, 'demo14', 19),
(15, 'demo15', 20),
(16, 'demo16', 21),
(17, 'demo17', 22),
(18, 'demo18', 23),
(19, 'demo19', 24),
(20, 'demo20', 25),
(21, 'demo21', 26),
(22, 'demo22', 27),
(23, 'demo23', 28),
(24, 'demo24', 29),
(25, 'demo25', 30),
(26, 'demo26', 31),
(27, 'demo27', 32),
(28, 'demo28', 33),
(29, 'demo29', 34),
(30, 'demo30', 35),
(31, 'demo31', 36),
(32, 'demo32', 37),
(33, 'demo33', 38),
(34, 'demo34', 39),
(35, 'demo35', 40),
(36, 'demo36', 41),
(37, 'demo37', 42),
(38, 'demo38', 43),
(39, 'demo39', 44),
(40, 'demo40', 45),
(41, 'demo41', 46),
(42, 'demo42', 47),
(43, 'demo43', 48),
(44, 'demo44', 49),
(45, 'demo45', 50),
(46, 'demo46', 51),
(47, 'demo47', 52),
(48, 'demo48', 53),
(49, 'demo49', 54),
(50, 'demo50', 55),
(51, 'demo51', 56),
(52, 'demo52', 57),
(53, 'demo53', 58),
(54, 'demo54', 59),
(55, 'demo55', 60),
(56, 'demo56', 61),
(57, 'demo57', 62),
(58, 'demo58', 63),
(59, 'demo59', 64),
(60, 'demo60', 65),
(61, 'demo61', 66),
(62, 'demo62', 67),
(63, 'demo63', 68),
(64, 'demo64', 69),
(65, 'demo65', 70),
(66, 'demo66', 71),
(67, 'demo67', 72),
(68, 'demo68', 73),
(69, 'demo69', 74),
(70, 'demo70', 75),
(71, 'demo71', 76),
(72, 'demo72', 77),
(73, 'demo73', 78),
(74, 'demo74', 79),
(75, 'demo75', 80),
(76, 'demo76', 81),
(77, 'demo77', 82),
(78, 'demo78', 83),
(79, 'demo79', 84),
(80, 'demo80', 85),
(81, 'demo81', 86),
(82, 'demo82', 87),
(83, 'demo83', 88),
(84, 'demo84', 89),
(85, 'demo85', 90),
(86, 'demo86', 91),
(87, 'demo87', 92),
(88, 'demo88', 93),
(89, 'demo89', 94),
(90, 'demo90', 95),
(91, 'demo91', 96),
(92, 'demo92', 97),
(93, 'demo93', 98),
(94, 'demo94', 99),
(95, 'demo95', 100),
(96, 'demo96', 101),
(97, 'demo97', 102),
(98, 'demo98', 103),
(99, 'demo99', 104),
(100, 'demo100', 105);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
         