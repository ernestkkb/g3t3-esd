-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 30, 2020 at 09:20 AM
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
-- Database: `payment`
--

-- --------------------------------------------------------

--
-- Table structure for table `payment_process`
--
DROP SCHEMA IF EXISTS payment_process;
CREATE SCHEMA payment_process;
USE payment_process;


DROP TABLE IF EXISTS `payment_process`;
CREATE TABLE IF NOT EXISTS `payment_process` (
  `userid` varchar(100) NOT NULL,
  `ID` int(4) NOT NULL,
  `price` float NOT NULL,
  `paymentStatus` varchar(10) NOT NULL,
  PRIMARY KEY (`userid`,`tripID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_process`
--

INSERT INTO `payment_process` (`userid`, `tripID`, `price`, `paymentStatus`) VALUES
('1', 1, 888, 'paid'),
('1', 2, 666, 'paid'),
('1', 3, 333.5, 'unpaid'),
('2', 1, 100.1, 'unpaid');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
