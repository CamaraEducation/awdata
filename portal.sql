-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 16, 2024 at 12:58 PM
-- Server version: 8.0.35-0ubuntu0.20.04.1
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `aw_application`
--

CREATE TABLE `aw_application` (
  `id` int NOT NULL,
  `devicename` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `duration` float NOT NULL,
  `datetimeadded` varchar(100) NOT NULL,
  `app` text NOT NULL,
  `title` longtext NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aw_records`
--

CREATE TABLE `aw_records` (
  `id` int NOT NULL,
  `last` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `aw_records`
--

INSERT INTO `aw_records` (`id`, `last`) VALUES
(1, 0),
(2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `aw_usage`
--

CREATE TABLE `aw_usage` (
  `id` int NOT NULL,
  `devicename` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `duration` float NOT NULL,
  `datetimeadded` varchar(100) NOT NULL,
  `cstatus` varchar(20) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `id` int NOT NULL,
  `school` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `ownership` varchar(100) NOT NULL,
  `region` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `last` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`id`, `school`, `category`, `ownership`, `region`, `country`, `last`) VALUES
(1, 'Camara School', 'Secondary', 'Government', 'Addis Ababa', 'Ethiopia', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aw_application`
--
ALTER TABLE `aw_application`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aw_records`
--
ALTER TABLE `aw_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `aw_usage`
--
ALTER TABLE `aw_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aw_application`
--
ALTER TABLE `aw_application`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aw_records`
--
ALTER TABLE `aw_records`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `aw_usage`
--
ALTER TABLE `aw_usage`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `config`
--
ALTER TABLE `config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
