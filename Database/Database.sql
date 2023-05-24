-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 19, 2023 at 04:21 PM
-- Server version: 10.6.12-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u850489700_swimclub`
--

-- --------------------------------------------------------

--
-- Table structure for table `Gala`
--

CREATE TABLE `Gala` (
  `id` int(10) UNSIGNED NOT NULL,
  `eventName` text NOT NULL,
  `venue` text NOT NULL,
  `date` date NOT NULL,
  `fee` varchar(20) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `Gala`
--

INSERT INTO `Gala` (`id`, `eventName`, `venue`, `date`, `fee`, `description`) VALUES
(2, 'Warm Waters', 'Accra', '2023-05-17', 'Free', 'Fierst'),
(3, 'The Rookies', 'China', '2023-05-23', 'Free', 'New Swimmers'),
(4, 'Nigeria Opens', 'Abuja', '2023-05-28', '£100', 'In Nigeria'),
(6, 'The Oscars', 'Lagos', '2026-09-17', '£50', 'Autralian Events');

-- --------------------------------------------------------

--
-- Table structure for table `galaPerformance`
--

CREATE TABLE `galaPerformance` (
  `id` int(10) UNSIGNED NOT NULL,
  `galaID` int(10) UNSIGNED NOT NULL,
  `userID` int(10) UNSIGNED NOT NULL,
  `Position` int(2) NOT NULL,
  `Distance` int(4) NOT NULL,
  `strokeId` int(10) UNSIGNED NOT NULL,
  `time` varchar(10) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Role`
--

CREATE TABLE `Role` (
  `id` int(10) UNSIGNED NOT NULL,
  `Name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `Role`
--

INSERT INTO `Role` (`id`, `Name`) VALUES
(1, 'Adult'),
(2, 'Parent'),
(3, 'Coach'),
(4, 'Admin'),
(5, 'Child'),
(6, 'Parent & Member');

-- --------------------------------------------------------

--
-- Table structure for table `Squad`
--

CREATE TABLE `Squad` (
  `id` int(10) UNSIGNED NOT NULL,
  `Name` text NOT NULL,
  `Desciption` text NOT NULL,
  `CoachID` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `Squad`
--

INSERT INTO `Squad` (`id`, `Name`, `Desciption`, `CoachID`) VALUES
(1, 'Default', 'No squad Swimmers', 0),
(13, 'Jango', 'Under 20s Men', 48),
(17, 'Diamonds', 'Olympic Swimmers', 48),
(18, 'Sharks', 'Veteran Swimmers', 48);

-- --------------------------------------------------------

--
-- Table structure for table `Strokes`
--

CREATE TABLE `Strokes` (
  `id` int(10) UNSIGNED NOT NULL,
  `Name` varchar(20) NOT NULL,
  `strokeId` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `Strokes`
--

INSERT INTO `Strokes` (`id`, `Name`, `strokeId`, `description`) VALUES
(1, 'Free Style', 1, 'Also known as front crawl, it is the fastest and most common stroke. Swimmers use an alternating arm motion and a flutter kick.'),
(2, 'Back Stroke', 2, ' Swimmers swim on their backs and use alternating arm movements and a flutter kick. The face is kept out of the water, and swimmers rely on the ceiling or overhead markers for guidance.'),
(3, 'Breast Stroke', 3, 'Swimmers perform a simultaneous movement of the arms in a half-circle motion, followed by a simultaneous kick in which the legs move symmetrically. The stroke includes a glide phase, and the head can be held above or below the water surface.'),
(4, 'Butterfly', 4, 'Swimmers use a simultaneous arm movement known as the butterfly arm pull, followed by an undulating dolphin kick. The stroke requires a significant amount of strength and coordination.');

-- --------------------------------------------------------

--
-- Table structure for table `Training`
--

CREATE TABLE `Training` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `Desciption` text NOT NULL,
  `squadID` int(10) UNSIGNED NOT NULL,
  `trainingDays` varchar(12) NOT NULL,
  `trainingTime` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `Training`
--

INSERT INTO `Training` (`id`, `name`, `Desciption`, `squadID`, `trainingDays`, `trainingTime`) VALUES
(17, 'Autralian Gala Train', 'Training for the Australian Gala Event', 13, 'Mondays', '5pm-6pm'),
(21, 'General Training', 'Training for General Swimmers', 1, 'Mondays', '5pm-6pm');

-- --------------------------------------------------------

--
-- Table structure for table `trainingPerformance`
--

CREATE TABLE `trainingPerformance` (
  `id` int(10) UNSIGNED NOT NULL,
  `trainingId` int(10) UNSIGNED NOT NULL,
  `userId` int(10) UNSIGNED NOT NULL,
  `Distance` int(4) NOT NULL,
  `strokeId` int(10) UNSIGNED NOT NULL,
  `time` varchar(10) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `address` text NOT NULL,
  `postCode` varchar(7) NOT NULL,
  `d_o_b` date NOT NULL,
  `age` int(10) UNSIGNED NOT NULL,
  `gender` varchar(20) NOT NULL,
  `roleID` int(10) UNSIGNED NOT NULL,
  `squadID` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `firstName`, `lastName`, `email`, `password`, `address`, `postCode`, `d_o_b`, `age`, `gender`, `roleID`, `squadID`) VALUES
(50, 'Adam', 'Smith', 'adamsmith@gmail.com', '$2y$10$kKLVnYQ0ywiJ5j4iyZGj../ZSiItJ2//PwhXgA6qm8wcXFOH4ab5q', '6, Henry Dunn Avenue, Hanley', 'ST1 5FF', '1973-07-05', 49, 'Male', 4, NULL),
(51, 'Tyler', 'Thompson', 'tyler@gmail.com', '$2y$10$XVNgwvP1TuWo4yKl3ITnJ.cbuLxpYNb7hp6yvtVl/VmrrPLbS2ajO', '6, Henry Dunn Avenue, Hanley', 'ST1 5FF', '1985-11-09', 37, 'Male', 3, NULL),
(52, 'Stone', 'Oduola', 'Angela', '$2y$10$5wH.eH/esQKIzL1DY4ZV/Ot7liEPZGsoY0BevuDBysEZ5Kqixhmmy', '6, Henry Dunn Avenue, Hanley', 'ST1 5FF', '1972-06-08', 50, 'Female', 3, NULL),
(53, 'Ayodele', 'Oduola', 'gbengajohn4god@gmail.com', '$2y$10$67zrjENN4GZBov9jHtyhT.WtQqObrx29tLJ5tHr4UhvKnb59BZpvS', '6, Henry Dunn Avenue, Hanley', 'ST1 5FF', '1995-04-22', 28, 'Male', 2, 1),
(54, 'Ayodele', 'Oduola', 'gbengajohn4god@yahoo.com', '$2y$10$uECzj/dblcwNVEmO6tVAlOQO8U0jd8K8QfMNUYuzefCt6O9ZUtDmu', '6, Henry Dunn Avenue, Hanley', 'ST1 5FF', '1999-06-09', 23, 'Male', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Ward`
--

CREATE TABLE `Ward` (
  `id` int(10) NOT NULL,
  `Parentid` int(10) UNSIGNED NOT NULL,
  `Userid` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Gala`
--
ALTER TABLE `Gala`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galaPerformance`
--
ALTER TABLE `galaPerformance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `strokeId` (`strokeId`),
  ADD KEY `userID` (`userID`),
  ADD KEY `galaID` (`galaID`);

--
-- Indexes for table `Role`
--
ALTER TABLE `Role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Squad`
--
ALTER TABLE `Squad`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Strokes`
--
ALTER TABLE `Strokes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Training`
--
ALTER TABLE `Training`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `squadID` (`squadID`);

--
-- Indexes for table `trainingPerformance`
--
ALTER TABLE `trainingPerformance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainingId` (`trainingId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `stroke_id` (`strokeId`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH,
  ADD KEY `roleID` (`roleID`),
  ADD KEY `squadID` (`squadID`);

--
-- Indexes for table `Ward`
--
ALTER TABLE `Ward`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Parentid` (`Parentid`),
  ADD KEY `Userid` (`Userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Gala`
--
ALTER TABLE `Gala`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `galaPerformance`
--
ALTER TABLE `galaPerformance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `Role`
--
ALTER TABLE `Role`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `Squad`
--
ALTER TABLE `Squad`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `Strokes`
--
ALTER TABLE `Strokes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Training`
--
ALTER TABLE `Training`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `trainingPerformance`
--
ALTER TABLE `trainingPerformance`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `Ward`
--
ALTER TABLE `Ward`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `galaPerformance`
--
ALTER TABLE `galaPerformance`
  ADD CONSTRAINT `galaPerformance_ibfk_1` FOREIGN KEY (`strokeId`) REFERENCES `Strokes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `galaPerformance_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `galaPerformance_ibfk_3` FOREIGN KEY (`galaID`) REFERENCES `Gala` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Training`
--
ALTER TABLE `Training`
  ADD CONSTRAINT `Training_ibfk_1` FOREIGN KEY (`squadID`) REFERENCES `Squad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trainingPerformance`
--
ALTER TABLE `trainingPerformance`
  ADD CONSTRAINT `trainingPerformance_ibfk_1` FOREIGN KEY (`trainingId`) REFERENCES `Training` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainingPerformance_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trainingPerformance_ibfk_3` FOREIGN KEY (`strokeId`) REFERENCES `Strokes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `Users_ibfk_2` FOREIGN KEY (`roleID`) REFERENCES `Role` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Users_ibfk_3` FOREIGN KEY (`squadID`) REFERENCES `Squad` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `Ward`
--
ALTER TABLE `Ward`
  ADD CONSTRAINT `Ward_ibfk_1` FOREIGN KEY (`Parentid`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Ward_ibfk_2` FOREIGN KEY (`Userid`) REFERENCES `Users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
