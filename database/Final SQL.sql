-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 19, 2024 at 06:26 AM
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
-- Database: `lms`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `BookId` int(10) NOT NULL,
  `Author` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `BookId` int(10) NOT NULL,
  `Title` varchar(50) DEFAULT NULL,
  `Publisher` varchar(50) DEFAULT NULL,
  `Year` varchar(50) DEFAULT NULL,
  `Availability` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`BookId`, `Title`, `Publisher`, `Year`, `Availability`) VALUES
(1, 'OS', 'PEARSON', '2006', 0),
(2, 'DBMS', 'TARGET67', '2010', 0),
(3, 'TOC', 'NITC', '2018', 4),
(4, 'TOC', 'NITC', '2018', 1),
(5, 'DAA', 'y', '2014', 0),
(6, 'DSA', 'X', '2010', 10),
(7, 'Discrete Structures', 'Pearson', '2010', 10),
(8, 'Database Processing', 'Prentice Hall', '2013', 12),
(9, 'Computer System Architecture', 'Prentice Hall', '2015', 4),
(10, 'C: How to program', 'Prentice Hall', '2009', 3),
(11, 'Atomic and Nuclear Systems', 'Pearson India', '2017', 12),
(12, 'The PlayBook', 'Stinson', '2010', 11),
(13, 'General Theory of Relativity', 'Pearson India', '2012', 5),
(14, 'Heat and Thermodynamics', 'Pearson', '2013', 9),
(15, 'Machine Design', 'Pearson India', '2012', 5),
(16, 'Nuclear Physics', 'Pearson India', '1998', 7),
(17, 'Operating System', 'Pearson India', '1990', 7),
(18, 'Theory of Machines', 'Pearson', '1992', 11);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `M_Id` int(10) NOT NULL,
  `IDNo` varchar(50) DEFAULT NULL,
  `Msg` varchar(255) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--


-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--



-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE `recommendations` (
  `R_ID` int(10) NOT NULL,
  `Book_Name` varchar(50) DEFAULT NULL,
  `Description` varchar(255) DEFAULT NULL,
  `IDNo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `record`
--

CREATE TABLE `record` (
  `IDNo` varchar(50) NOT NULL,
  `BookId` int(10) NOT NULL,
  `Date_of_Issue` date DEFAULT NULL,
  `Due_Date` date DEFAULT NULL,
  `Date_of_Return` date DEFAULT NULL,
  `Dues` int(10) DEFAULT NULL,
  `Renewals_left` int(10) DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Returned` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `renew`
--

CREATE TABLE `renew` (
  `IDNo` varchar(50) NOT NULL,
  `BookId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `return`
--

CREATE TABLE `return` (
  `IDNo` varchar(50) NOT NULL,
  `BookId` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `IDNo` varchar(50) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Type` varchar(50) DEFAULT NULL,
  `EmailId` varchar(50) DEFAULT NULL,
  `MobNo` bigint(11) DEFAULT NULL,
  `Password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`IDNo`, `Name`, `Type`, `EmailId`, `MobNo`, `Password`) VALUES
('23063589', 'admin', 'Admin', 'admin@gmail.com', 123456789, 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`BookId`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`M_Id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recommendations`
--
ALTER TABLE `recommendations`
  ADD PRIMARY KEY (`R_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `BookId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `M_Id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `recommendations`
--
ALTER TABLE `recommendations`
  MODIFY `R_ID` int(10) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
