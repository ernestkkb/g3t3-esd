-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 28, 2020 at 09:41 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `scheduler`
--
CREATE DATABASE IF NOT EXISTS `scheduler` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `scheduler`;

-- --------------------------------------------------------

--
-- Table structure for table `scheduler`
--

DROP TABLE IF EXISTS `scheduler`;
CREATE TABLE IF NOT EXISTS `scheduler` (
  `id` varchar(10) NOT NULL,
  `tripName` text NOT NULL,
  `facebookID` varchar(20) NOT NULL,
  `placeOfInterest` json NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `paymentStatus` varchar(10) NOT NULL,
  `day` int(99) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `scheduler`
--

INSERT INTO `scheduler` (`id`, `tripName`, `facebookID`, `placeOfInterest`, `startDate`, `endDate`, `paymentStatus`, `day`) VALUES
('F2ph83qSXF', '', '1', '{\"name\": \"Helix Bridge\", \"address\": \"Singapore\"}', '2020-03-12', '2020-03-15', 'paid', 1),
('hgjsOfBfjf', '', '1', '{\"name\": \"Cloud Forest\", \"address\": \"18 Marina Gardens Dr, Singapore 018953\"}', '2020-03-12', '2020-03-15', 'paid', 1),
('QIpjua6l^M', 'Japan', '1', '{\"name\": \"Temple of the Six Banyan Trees\", \"address\": \"87 Liurong Rd, Zhong Shan Liu Lu, Yuexiu Qu, Guangzhou Shi, Guangdong Sheng, China, 510000\"}', '2020-03-12', '2020-03-15', 'paid', 3),
('qWpnIU6A7#', '', '1', '{\"name\": \"Singapore Botanic Gardens\", \"address\": \"1 Cluny Rd, Singapore 259569\"}', '2020-03-12', '2020-03-15', 'paid', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
