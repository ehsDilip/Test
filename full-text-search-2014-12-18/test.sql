-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 20, 2014 at 08:40 AM
-- Server version: 5.5.24-log
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `testorder`
--

CREATE TABLE IF NOT EXISTS `testorder` (
  `text_id` int(3) NOT NULL AUTO_INCREMENT,
  `text_name` text NOT NULL,
  `date` int(20) NOT NULL,
  PRIMARY KEY (`text_id`),
  KEY `text_id` (`text_id`),
  FULLTEXT KEY `text_name` (`text_name`),
  FULLTEXT KEY `text_name_2` (`text_name`),
  FULLTEXT KEY `text_name_3` (`text_name`),
  FULLTEXT KEY `text_name_4` (`text_name`),
  FULLTEXT KEY `text_name_5` (`text_name`),
  FULLTEXT KEY `text_name_6` (`text_name`),
  FULLTEXT KEY `text_name_7` (`text_name`),
  FULLTEXT KEY `text_name_8` (`text_name`),
  FULLTEXT KEY `text_name_9` (`text_name`),
  FULLTEXT KEY `text_name_10` (`text_name`),
  FULLTEXT KEY `text_name_11` (`text_name`),
  FULLTEXT KEY `text_name_12` (`text_name`),
  FULLTEXT KEY `text_name_13` (`text_name`),
  FULLTEXT KEY `text_name_14` (`text_name`),
  FULLTEXT KEY `text_name_15` (`text_name`),
  FULLTEXT KEY `text_name_16` (`text_name`),
  FULLTEXT KEY `text_name_17` (`text_name`),
  FULLTEXT KEY `text_name_18` (`text_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `testorder`
--

INSERT INTO `testorder` (`text_id`, `text_name`, `date`) VALUES
(6, 'hillodss', 20141114),
(7, 'djfjfhofkfkf', 20141114),
(8, 'hrfrllodssfjfdfrfr', 20141114),
(9, 'MySQL is a very fast database', 20141114),
(10, 'Green is everyone''s favourite colour', 20141114),
(11, 'Databases are helpful for storing data', 20141114),
(12, 'PHP is a very nice language', 20141114),
(13, 'PHP is a very nice language', 20141114);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
