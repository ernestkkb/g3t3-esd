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
("Malaysia","George Town"),
("Malaysia","Kuala Lumpur"),
("Malaysia","Klang"),
("Malaysia","Johor Bahru"),
("Malaysia","Melaka"),
("Malaysia","Ipoh"),
("France", "Paris"),
("France", "Nice"),
("France", "Lyon"),
("Italy", "Venice")
