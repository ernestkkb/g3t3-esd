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
("Canada", "Toronto"),
("Canada", "Vancouver"),
("Canada", "Victoria"),
("United States", "New York"),
("United States", "Chicago"),
("United Kingdom", "England"),
("United Kingdom", "Bath"),
("Australia", "Brisbane"),
("Australia", "Perth"),
("Australia", "Melbourne")