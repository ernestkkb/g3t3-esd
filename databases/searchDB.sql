--
-- Database: `searchDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `country_city`
--

DROP SCHEMA IF EXISTS searchDB;

CREATE SCHEMA searchDB;

USE searchDB;

CREATE TABLE IF NOT EXISTS `country_city` (
  `country` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `country_city`(`country`, `city`) VALUES 

("Singapore","Singapore"),
("Canada", "Toronto, Canada"),
("Canada", "Vancouver, Canada"),
("Canada", "Victoria, Canada"),
("United States", "New York, United States"),
("United States", "Chicago, United States"),
("United Kingdom", "London, United Kingdom"),
("United Kingdom", "Bath, United Kingdom"),
("Australia", "Brisbane, Australia"),
("Australia", "Perth, Australia"),
("Australia", "Melbourne, Australia")