-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 01, 2020 at 08:26 AM
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
-- Table structure for table `package`
--

DROP TABLE IF EXISTS `package`;
CREATE TABLE IF NOT EXISTS `package` (
  `id` varchar(10) NOT NULL,
  `tripName` text NOT NULL,
  `placeOfInterest` json NOT NULL,
  `day` int(99) NOT NULL,
  `tripID` varchar(15) NOT NULL,
  PRIMARY KEY (`id`,`tripID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `package`
--

INSERT INTO `package` (`id`, `tripName`, `placeOfInterest`, `day`, `tripID`) VALUES
('1WisKRTJPZ', 'United States, New York', '{\"name\": \"Bryant Park\", \"address\": \"New York, NY 10018, USA\"}', 3, 'OkjrD6SzQw'),
('2swaYLAvvf', 'Australia, Melbourne', '{\"name\": \"ACMI (Australian Centre for the Moving Image)\", \"address\": \"Federation Square, Flinders St, Melbourne VIC 3000, Australia\"}', 2, 'cPG0LAtqIk'),
('2YeRQNl4Jf', 'United States, New York', '{\"name\": \"The Museum of Modern Art\", \"address\": \"11 W 53rd St, New York, NY 10019, USA\"}', 4, 'OkjrD6SzQw'),
('44VAUSiYnn', 'Australia, Melbourne', '{\"name\": \"Melbourne Cricket Ground\", \"address\": \"Brunton Ave, Richmond VIC 3002, Australia\"}', 5, 'cPG0LAtqIk'),
('66JXgGCqMy', 'Australia, Melbourne', '{\"name\": \"Cooks\' Cottage\", \"address\": \"Fitzroy Gardens, Wellington Parade, East Melbourne VIC 3002, Australia\"}', 4, 'cPG0LAtqIk'),
('6kqcwaTyud', 'Canada, Vancouver', '{\"name\": \"VanDusen Botanical Garden\", \"address\": \"5251 Oak St, Vancouver, BC V6M 4H1, Canada\"}', 3, '6vLq62DGv0'),
('6RKsN4lArN', 'Australia, Melbourne', '{\"name\": \"Crown Melbourne\", \"address\": \"8 Whiteman St, Southbank VIC 3006, Australia\"}', 5, 'cPG0LAtqIk'),
('90kDZqdpKb', 'Canada, Vancouver', '{\"name\": \"Jericho Beach Park\", \"address\": \"3941 Point Grey Rd, Vancouver, BC V6R 1B5, Canada\"}', 5, '6vLq62DGv0'),
('96PLfUzaoG', 'Canada, Vancouver', '{\"name\": \"Canada Place\", \"address\": \"999 Canada Pl, Vancouver, BC V6C 3T4, Canada\"}', 1, '6vLq62DGv0'),
('b9H7uQaOjK', 'Australia, Melbourne', '{\"name\": \"Melbourne Zoo\", \"address\": \"Elliott Ave, Parkville VIC 3052, Australia\"}', 3, 'cPG0LAtqIk'),
('BGmIwF2Bbl', 'Canada, Vancouver', '{\"name\": \"Vancouver Seawall\", \"address\": \"Vancouver, BC V6G 3E2, Canada\"}', 4, '6vLq62DGv0'),
('C4Ich1vJ4T', 'Canada, Vancouver', '{\"name\": \"Museum of Vancouver\", \"address\": \"1100 Chestnut St, Vancouver, BC V6J 3J9, Canada\"}', 4, '6vLq62DGv0'),
('COFQZ3WaqV', 'United States, New York', '{\"name\": \"Radio City Music Hall\", \"address\": \"1260 6th Ave, New York, NY 10020, USA\"}', 5, 'OkjrD6SzQw'),
('COq7agYqhW', 'Australia, Melbourne', '{\"name\": \"National Gallery of Victoria\", \"address\": \"180 St Kilda Rd, Melbourne VIC 3006, Australia\"}', 4, 'cPG0LAtqIk'),
('EYXMHQwOQO', 'Canada, Vancouver', '{\"name\": \"Vancouver Aquarium\", \"address\": \"845 Avison Way, Vancouver, BC V6G 3E2, Canada\"}', 4, '6vLq62DGv0'),
('f4NaO8R08K', 'Australia, Melbourne', '{\"name\": \"St Paul\'s Cathedral, Melbourne\", \"address\": \"Flinders St, Melbourne VIC 3000, Australia\"}', 3, 'cPG0LAtqIk'),
('FA82vHsGRg', 'Canada, Vancouver', '{\"name\": \"Science World at TELUS World of Science\", \"address\": \"1455 Quebec St, Vancouver, BC V6A 3Z7, Canada\"}', 1, '6vLq62DGv0'),
('HBaoeuVII8', 'Australia, Melbourne', '{\"name\": \"St Patrick\'s Cathedral\", \"address\": \"1 Cathedral Pl, East Melbourne VIC 3002, Australia\"}', 2, 'cPG0LAtqIk'),
('hD345D1FKe', 'Canada, Vancouver', '{\"name\": \"Vancouver Art Gallery\", \"address\": \"750 Hornby St, Vancouver, BC V6Z 2H7, Canada\"}', 5, '6vLq62DGv0'),
('I8Q8EkZCMZ', 'Australia, Melbourne', '{\"name\": \"Australian Sports Museum\", \"address\": \"Melbourne Cricket Ground, Brunton Ave, Melbourne VIC 3000, Australia\"}', 4, 'cPG0LAtqIk'),
('inFoq4IlN9', 'United States, New York', '{\"name\": \"The Metropolitan Museum of Art\", \"address\": \"1000 5th Ave, New York, NY 10028, USA\"}', 2, 'OkjrD6SzQw'),
('lcbYBMoOkX', 'Australia, Melbourne', '{\"name\": \"Eureka Skydeck\", \"address\": \"7 Riverside Quay, Southbank VIC 3006, Australia\"}', 2, 'cPG0LAtqIk'),
('lYQQQROHeB', 'Canada, Vancouver', '{\"name\": \"Dr. Sun Yat-Sen Classical Chinese Garden\", \"address\": \"578 Carrall St, Vancouver, BC V6B 5K2, Canada\"}', 5, '6vLq62DGv0'),
('mCfsC82lW2', 'United States, New York', '{\"name\": \"Top of The Rock\", \"address\": \"30 Rockefeller Plaza, New York, NY 10112, USA\"}', 3, 'OkjrD6SzQw'),
('MGedzaokci', 'Canada, Vancouver', '{\"name\": \"Museum of Anthropology\", \"address\": \"6393 NW Marine Dr, Vancouver, BC V6T 1Z2, Canada\"}', 2, '6vLq62DGv0'),
('nk4EoQVyPG', 'United States, New York', '{\"name\": \"Empire State Building\", \"address\": \"20 W 34th St, New York, NY 10001, USA\"}', 1, 'OkjrD6SzQw'),
('NpK0EO7Wru', 'Australia, Melbourne', '{\"name\": \"Melbourne Museum\", \"address\": \"11 Nicholson St, Carlton VIC 3053, Australia\"}', 1, 'cPG0LAtqIk'),
('nuZYK1Wd81', 'Australia, Melbourne', '{\"name\": \"Federation Square\", \"address\": \"Swanston St & Flinders St, Melbourne VIC 3000, Australia\"}', 3, 'cPG0LAtqIk'),
('o7Ah1ROELa', 'United States, New York', '{\"name\": \"The High Line\", \"address\": \"New York, NY 10011, USA\"}', 3, 'OkjrD6SzQw'),
('OAjAz6wIP2', 'United States, New York', '{\"name\": \"Governors Island National Monument\", \"address\": \"10 South St, New York, NY 10004, USA\"}', 2, 'OkjrD6SzQw'),
('OAZjz1FSBl', 'United States, New York', '{\"name\": \"Brooklyn Bridge\", \"address\": \"Brooklyn Bridge, New York, NY 10038, USA\"}', 2, 'OkjrD6SzQw'),
('pPTCW6gI72', 'United States, New York', '{\"name\": \"One World Observatory\", \"address\": \"285 Fulton St, New York, NY 10006, USA\"}', 4, 'OkjrD6SzQw'),
('qEO2YGj61V', 'Canada, Vancouver', '{\"name\": \"Stanley Park\", \"address\": \"Vancouver, BC V6G 1Z4, Canada\"}', 2, '6vLq62DGv0'),
('qLCuemdyaF', 'United States, New York', '{\"name\": \"Central Park\", \"address\": \"New York, NY, USA\"}', 1, 'OkjrD6SzQw'),
('qpXhJDu9KQ', 'Australia, Melbourne', '{\"name\": \"Royal Botanic Gardens Victoria - Melbourne Gardens\", \"address\": \"Birdwood Ave, South Yarra VIC 3141, Australia\"}', 1, 'cPG0LAtqIk'),
('qqrGKUEEuZ', 'United States, New York', '{\"name\": \"Washington Square Park\", \"address\": \"New York, NY 10012, USA\"}', 5, 'OkjrD6SzQw'),
('tkkXgM44yd', 'Canada, Vancouver', '{\"name\": \"Bloedel Conservatory\", \"address\": \"4600 Cambie St, Vancouver, BC V5Y 2M4, Canada\"}', 3, '6vLq62DGv0'),
('UW5eKFm0qc', 'Canada, Vancouver', '{\"name\": \"Queen Elizabeth Park\", \"address\": \"4600 Cambie St, Vancouver, BC V5Z 2Z1, Canada\"}', 1, '6vLq62DGv0'),
('VpVRngRdOL', 'United States, New York', '{\"name\": \"Times Square\", \"address\": \"Manhattan, NY 10036, USA\"}', 4, 'OkjrD6SzQw'),
('VyhLpfhVvP', 'Australia, Melbourne', '{\"name\": \"Shrine of Remembrance\", \"address\": \"Birdwood Ave, Melbourne VIC 3001, Australia\"}', 1, 'cPG0LAtqIk'),
('wlBvJaOPOU', 'United States, New York', '{\"name\": \"St. Patrick\'s Cathedral\", \"address\": \"5th Ave, New York, NY 10022, USA\"}', 2, 'OkjrD6SzQw'),
('WOVs3j3zpo', 'Australia, Melbourne', '{\"name\": \"SEA LIFE Melbourne Aquarium\", \"address\": \"King St, Melbourne VIC 3000, Australia\"}', 2, 'cPG0LAtqIk'),
('x5FvfuY26C', 'Canada, Vancouver', '{\"name\": \"Vancouver Lookout\", \"address\": \"555 W Hastings St, Vancouver, BC V6B 4N6, Canada\"}', 3, '6vLq62DGv0'),
('xbISAGIPJF', 'Canada, Vancouver', '{\"name\": \"Gastown Steam Clock\", \"address\": \"305 Water St, Vancouver, BC V6B 1B9, Canada\"}', 2, '6vLq62DGv0'),
('xhqYqhcR1H', 'Canada, Vancouver', '{\"name\": \"UBC Botanical Garden\", \"address\": \"Administration Bldg, 6804 SW Marine Dr, Vancouver, BC V6T 1Z4, Canada\"}', 5, '6vLq62DGv0'),
('YGeKQtjNHM', 'Canada, Vancouver', '{\"name\": \"English Bay Beach\", \"address\": \"Beach Ave, Vancouver, BC V6C 3C1, Canada\"}', 4, '6vLq62DGv0'),
('YMbMnbIbLF', 'United States, New York', '{\"name\": \"Statue of Liberty National Monument\", \"address\": \"New York, NY 10004, USA\"}', 1, 'OkjrD6SzQw'),
('ypoIms4rvR', 'United States, New York', '{\"name\": \"Rockefeller Center\", \"address\": \"45 Rockefeller Plaza, New York, NY 10111, USA\"}', 2, 'OkjrD6SzQw'),
('z7vOuoBpI0', 'Australia, Melbourne', '{\"name\": \"Queen Victoria Market\", \"address\": \"Queen St, Melbourne VIC 3000, Australia\"}', 1, 'cPG0LAtqIk'),
('ZdTx4TwFZA', 'Australia, Melbourne', '{\"name\": \"Immigration Museum\", \"address\": \"400 Flinders St, Melbourne VIC 3000, Australia\"}', 3, 'cPG0LAtqIk');

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
  `paymentStatus` varchar(10) NOT NULL,
  `day` int(99) NOT NULL,
  `tripID` varchar(15) NOT NULL,
  PRIMARY KEY (`id`,`facebookID`,`tripID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
