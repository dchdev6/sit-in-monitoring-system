-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2025 at 06:17 PM
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
-- Database: `ccs_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `announce`
--

CREATE TABLE `announce` (
  `announce_id` int(11) NOT NULL,
  `admin_name` varchar(20) NOT NULL,
  `date` varchar(20) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `id_number` int(11) NOT NULL,
  `lab` int(11) NOT NULL,
  `date` varchar(20) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `id_number`, `lab`, `date`, `message`) VALUES
(7, 20946976, 524, '2025-Mar-11', 'bati ako experience');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_number` varchar(50) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`notification_id`),
  KEY `id_number` (`id_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `id_number`, `message`) VALUES
(1, 20946976, 'Feedback Confirmed! | 2025-03-11\nYou have successfully submitted a feedback.'),
(2, 20946976, 'Feedback Confirmed! | 2025-03-11\nYou have successfully submitted a feedback.'),
(3, 20946976, 'Reservation Confirmed! | 2025-03-24\nYou have successfully submitted a reservation.'),
(4, 20946976, 'Reservation Confirmed! | 2025-03-24\nYou have successfully submitted a reservation.'),
(5, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(6, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(7, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(8, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(9, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(10, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(11, 20946976, 'Reservation Confirmed! | 2025-03-18\nYou have successfully submitted a reservation.'),
(12, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(13, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(14, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(15, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(16, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(17, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(18, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(19, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(20, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(21, 20946976, 'Your Reservation has been Denied! 2025-03-16'),
(22, 20946976, 'Your Reservation has been Denied! 2025-03-16');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `reservation_id` int(11) NOT NULL,
  `reservation_date` varchar(10) NOT NULL,
  `reservation_time` varchar(10) NOT NULL,
  `pc_number` int(11) NOT NULL,
  `lab` varchar(11) NOT NULL,
  `purpose` varchar(50) NOT NULL,
  `id_number` int(11) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`reservation_id`, `reservation_date`, `reservation_time`, `pc_number`, `lab`, `purpose`, `id_number`, `status`) VALUES
(1, '2025-03-24', '18:03', 0, '524', 'C Programming', 20946976, 'Decline'),
(2, '2025-03-24', '18:03', 0, '524', 'C Programming', 20946976, 'Decline'),
(3, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(4, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(5, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(6, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(7, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(8, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(9, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(10, '2025-03-18', '19:05', 0, '524', 'C Programming', 20946976, 'Decline'),
(11, '2025-03-17', '19:34', 0, '524', 'C Programming', 20946976, 'Decline');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id_number` int(11) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `middleName` varchar(50) NOT NULL,
  `yearLevel` int(11) NOT NULL,
  `password` varchar(50) NOT NULL,
  `course` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id_number`, `lastName`, `firstName`, `middleName`, `yearLevel`, `password`, `course`, `email`, `address`, `status`, `profile_image`) VALUES
(20946976, 'Arcana', 'Sean Joseph', 'C', 4, '123', 'BSCS', 'sean@gmail.com', 'Basak', 'TRUE', '482056734_1040856227860103_1401144500195147194_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `student_pc`
--

CREATE TABLE `student_pc` (
  `pc_id` int(11) NOT NULL,
  `lab_524` int(11) NOT NULL,
  `lab_526` int(11) NOT NULL,
  `lab_528` int(11) NOT NULL,
  `lab_530` int(11) NOT NULL,
  `lab_542` int(11) NOT NULL,
  `lab_Mac` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_session`
--

CREATE TABLE `student_session` (
  `id_number` int(11) NOT NULL,
  `session` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_session`
--

INSERT INTO `student_session` (`id_number`, `session`) VALUES
(20946976, 22);

--
-- Table structure for table `student_points`
--

CREATE TABLE `student_points` (
  `id_number` int(11) NOT NULL,
  `points` int(11) NOT NULL DEFAULT 0,
  `semester` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_number`),
  CONSTRAINT `student_points_ibfk_1` FOREIGN KEY (`id_number`) REFERENCES `students` (`id_number`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_sit_in`
--

CREATE TABLE `student_sit_in` (
  `sit_id` int(11) NOT NULL,
  `id_number` int(11) NOT NULL,
  `sit_purpose` varchar(50) NOT NULL,
  `sit_lab` varchar(20) NOT NULL,
  `sit_login` varchar(15) NOT NULL,
  `sit_logout` varchar(15) NOT NULL,
  `sit_date` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_sit_in`
--

INSERT INTO `student_sit_in` (`sit_id`, `id_number`, `sit_purpose`, `sit_lab`, `sit_login`, `sit_logout`, `sit_date`, `status`) VALUES
(1, 20946976, 'C-Programming', '524', '12:55:05pm', '11:04:00pm', '2025-03-11', 'Finished'),
(2, 20946976, 'C-Programming', '524', '11:07:26pm', '11:08:50pm', '2025-03-11', 'Finished'),
(3, 20946976, 'C-Programming', '524', '11:13:52pm', '23:18:23', '2025-03-11', 'Finished'),
(4, 20946976, 'C-Programming', '524', '11:18:49pm', '23:44:46', '2025-03-11', 'Finished'),
(5, 20946976, 'Java Programming', '524', '11:58:01pm', '23:58:17', '2025-03-11', 'Finished'),
(6, 20946976, 'C-Programming', '524', '10:50:59am', '12:02:59', '2025-03-13', 'Finished'),
(7, 20946976, 'C-Programming', '524', '12:05:14pm', '12:06:15', '2025-03-13', 'Finished'),
(8, 20946976, 'C-Programming', '542', '12:57:35pm', '12:57:50', '2025-03-13', 'Finished');

-- --------------------------------------------------------

--
-- Table structure for table `current_semester`
--

CREATE TABLE `current_semester` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `semester` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `current_semester`
--

INSERT INTO `current_semester` (`semester`, `academic_year`) VALUES
('Second Semester', '2024-2025');

--
-- Table structure for table `points_archive`
--

CREATE TABLE `points_archive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `semester` varchar(20) NOT NULL,
  `academic_year` varchar(20) NOT NULL,
  `archived_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `points_archive_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id_number`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announce`
--
ALTER TABLE `