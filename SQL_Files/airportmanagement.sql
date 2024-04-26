-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2024 at 11:06 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `airportmanagement`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Username` varchar(30) NOT NULL,
  `AdminPass` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Username`, `AdminPass`) VALUES
('superadmin', 'Air737super2024Admin?');

-- --------------------------------------------------------

--
-- Table structure for table `airlines`
--

CREATE TABLE `airlines` (
  `AirlineName` char(30) NOT NULL,
  `NumOfFlights` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `airlines`
--

INSERT INTO `airlines` (`AirlineName`, `NumOfFlights`) VALUES
('American Airlines', 0),
('Delta', 1),
('JetBlue', 1),
('Southwest', 1),
('Spirit', 1);

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

CREATE TABLE `flights` (
  `AirlineName` char(30) NOT NULL,
  `FlightNo` int(11) NOT NULL,
  `NumOfPassengers` int(11) NOT NULL,
  `Origin` char(50) NOT NULL,
  `Destination` char(50) NOT NULL,
  `SeatsRemaining` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`AirlineName`, `FlightNo`, `NumOfPassengers`, `Origin`, `Destination`, `SeatsRemaining`) VALUES
('Delta', 1, 30, 'Nashville', 'Las Vegas', 25),
('JetBlue', 2, 40, 'Nashville', 'Baltimore', 39),
('Southwest', 3, 40, 'Nashville', 'New York', 39),
('Spirit', 4, 20, 'Nashville', 'Dallas', 19);

-- --------------------------------------------------------

--
-- Table structure for table `passengers`
--

CREATE TABLE `passengers` (
  `Fname` varchar(30) DEFAULT NULL,
  `Lname` varchar(30) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `FlightNo` int(11) DEFAULT NULL,
  `SSN` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `passengers`
--

INSERT INTO `passengers` (`Fname`, `Lname`, `DateOfBirth`, `FlightNo`, `SSN`) VALUES
('Tyree', 'Giles', '1996-02-12', 4, 698),
('Donna', 'Rucker', '1951-03-23', 3, 3111),
('Henry', 'Moseley', '2005-05-21', 1, 3231),
('Walker', 'Barnett', '2002-07-05', 1, 3612),
('Alec', 'Creasy', '2000-06-29', 1, 7291),
('Logan', 'Tate', '2001-05-07', 2, 7349),
('Angie', 'Creasy', '1977-04-20', 1, 8309),
('Spence', 'Creasy', '1976-10-09', 1, 8450);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `Fname` char(30) NOT NULL,
  `Lname` char(30) NOT NULL,
  `EmployeeID` int(11) NOT NULL,
  `AirlineName` char(30) NOT NULL,
  `FlightNo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`Fname`, `Lname`, `EmployeeID`, `AirlineName`, `FlightNo`) VALUES
('Matt', 'Clay', 73098, 'Southwest', 1),
('Alec', 'Creasy', 73724, 'Southwest', 1),
('Dakota', 'Hertslet', 502613, 'Delta', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `airlines`
--
ALTER TABLE `airlines`
  ADD PRIMARY KEY (`AirlineName`);

--
-- Indexes for table `flights`
--
ALTER TABLE `flights`
  ADD PRIMARY KEY (`FlightNo`),
  ADD KEY `ForeignKeyName` (`AirlineName`);

--
-- Indexes for table `passengers`
--
ALTER TABLE `passengers`
  ADD PRIMARY KEY (`SSN`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`EmployeeID`),
  ADD KEY `ForeignKey` (`AirlineName`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
