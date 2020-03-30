-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 30, 2020 at 07:22 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `package_recommendation`
--

-- --------------------------------------------------------

--
-- Table structure for table `package_recommendation`
--

DROP SCHEMA IF EXISTS package_recommendation;
CREATE SCHEMA package_recommendation;
USE package_recommendation;


DROP TABLE IF EXISTS `package_recommendation`;
CREATE TABLE IF NOT EXISTS `package_recommendation` (
  `packageID` int(4) NOT NULL,
  `city` varchar(100) NOT NULL,
  `placesOfInterest` json NOT NULL,
  `price` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `package_recommendation`
--

INSERT INTO `package_recommendation` (`packageID`, `city`, `placesOfInterest`, `price`) VALUES
(1, 'Singapore', '{\"name\": \"MBS\"}', 321),
(1, 'Singapore', '{\"name\": \"MBS\"}', 321),
(2, 'Kuala Lumpur', '{\"name\": \"Twin Tower\"}', 456);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
