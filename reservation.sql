-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 06:51 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
(11, '2025-03-17', '19:34', 0, '524', 'C Programming', 20946976, 'Decline'),
(12, '2025-05-01', '10:30', 20, '544', 'Web Design & Development', 20946976, 'Approve'),
(13, '2025-05-01', '10:40', 5, '517', 'Computer Application', 20946977, 'Approve'),
(14, '2025-05-02', '10:25', 15, '524', 'C#', 20946976, 'Approve'),
(15, '2025-05-02', '23:57', 25, '524', 'C#', 20946976, 'Approve'),
(16, '2025-05-07', '17:00', 27, '517', 'Embedded System & IoT', 20946976, 'Decline'),
(17, '2025-05-07', '11:28', 6, '517', 'Digital Logic & Design', 20946976, 'Decline'),
(18, '2025-05-10', '12:45', 28, '517', 'C Programming', 20946976, 'Decline'),
(19, '2025-05-07', '10:40', 1, '517', 'System Integration & Architecture', 20946978, 'Approve'),
(20, '2025-05-08', '09:00 AM -', 3, 'lab_517', 'PHP', 20946976, 'Decline'),
(21, '2025-05-09', '2:00 PM - ', 2, 'lab_517', 'Python-Programming', 20946976, 'Approve'),
(22, '2025-05-08', '4:00 PM - ', 1, 'lab_517', 'Java', 20946976, 'Approve'),
(23, '2025-05-08', '4:00 PM - ', 1, 'lab_517', 'C#', 20946976, 'Approve'),
(24, '2025-05-09', '4:00 PM - ', 2, 'lab_517', 'C#', 20946976, 'Approve'),
(25, '2025-05-08', '4:00 PM - ', 1, 'lab_517', 'C-Programming', 20946976, 'Approve'),
(26, '2025-05-08', '5:00 PM - ', 1, 'lab_517', 'C#', 20946976, 'Approve');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`reservation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
