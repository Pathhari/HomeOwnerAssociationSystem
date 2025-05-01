-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2024 at 12:45 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `usersidedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `gcash_payments`
--

CREATE TABLE `gcash_payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `receipt_file_name` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) NOT NULL,
  `payment_date` datetime NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gcash_payments`
--

INSERT INTO `gcash_payments` (`payment_id`, `user_id`, `name`, `amount`, `transaction_id`, `receipt_file_name`, `payment_type`, `payment_date`, `is_deleted`) VALUES
(9, 9, 'Haripath', 125.00, '26326136123', 'loss.png', 'Membership', '2024-01-01 06:36:33', 1),
(12, 10, 'Haripath', 125.00, '23232323', '384162519_853438936340812_4698566356268356843_n.jpg', 'Security And Maintenance', '2024-01-01 07:21:59', 0),
(13, 11, 'pathhari', 125.00, '23123123', '370126293_342544821479517_6783801847038004124_n.jpg', 'Security And Maintenance', '2024-01-01 07:32:02', 0),
(14, 12, 'micah', 250.00, '123123123', '393383318_346839547725618_7942436163376572075_n.jpg', 'Donation', '2024-01-01 07:34:26', 0),
(15, 10, 'Haripath', 125.00, '323213', '384162519_853438936340812_4698566356268356843_n.jpg', 'Membership', '2024-01-01 07:41:47', 0);

-- --------------------------------------------------------

--
-- Table structure for table `membership`
--

CREATE TABLE `membership` (
  `membership_id` int(11) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `block` varchar(50) DEFAULT NULL,
  `lot` varchar(50) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `phase` varchar(50) DEFAULT NULL,
  `cellphone` varchar(20) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email_address` varchar(255) NOT NULL,
  `education_post_grad` varchar(255) DEFAULT NULL,
  `education_college` varchar(255) DEFAULT NULL,
  `education_highschool` varchar(255) DEFAULT NULL,
  `education_elementary` varchar(255) DEFAULT NULL,
  `occupant_name` varchar(255) DEFAULT NULL,
  `occupant_age` int(11) DEFAULT NULL,
  `relation_to_member` varchar(255) DEFAULT NULL,
  `gender` enum('male','female') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership`
--

INSERT INTO `membership` (`membership_id`, `last_name`, `first_name`, `middle_name`, `block`, `lot`, `street`, `phase`, `cellphone`, `telephone`, `email_address`, `education_post_grad`, `education_college`, `education_highschool`, `education_elementary`, `occupant_name`, `occupant_age`, `relation_to_member`, `gender`, `created_at`, `updated_at`, `is_deleted`) VALUES
(14, 'de Jesus', 'Haripath', 'V.', '1', '32', 'tubao', '2', '09321312344', 'N/A', 'haripath@gmail.com', 'N/A', 'N/A', 'Bangoy high', 'Hizon', 'Filmar', 32, 'Brother', 'male', '2023-12-26 05:26:52', '2023-12-31 13:59:34', 1),
(15, 'De Jesus', 'Haripath', 'V.', '35', '25', 'Tubao', '2', '0931231233', 'N/A', 'hari@gmail.com', 'N/A', 'usep', 'bangoy', 'hizon', 'Filmar', 23, 'brother', 'male', '2023-12-31 22:35:42', '2023-12-31 22:45:11', 1),
(16, 'sample', 'hari', 'v.', '24', '32', 'powes', NULL, NULL, 'N/A', '', 'usep', 'sada', 'sample', 'hizon', 'haripath', 24, 'brother', 'male', '2023-12-31 22:49:04', '2023-12-31 23:07:05', 1),
(17, 'sample', 'hari', 'V.', '2', '23', 'Tubao', '2', '0931231233', 'N/A', 'asda@gmail.com', 'N/A', 'sada', 'asdad', 'kapitan tomas', 'jerry', 23, 'brother', 'male', '2023-12-31 23:41:32', '2023-12-31 23:42:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `superadmin`
--

CREATE TABLE `superadmin` (
  `superadmin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `superadmin`
--

INSERT INTO `superadmin` (`superadmin_id`, `username`, `password`) VALUES
(1, 'superadmin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `registration_date` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password`, `phone`, `registration_date`, `is_deleted`) VALUES
(6, 'haripath', 'de jesus', 'haripath@gmail.com', '$2y$10$8cODe4gNsIZQygRlGXy6Z.lSnF4fX6ikEbLU9fw/wPJuCn9nPlvD2', '9293299239', '2023-12-24 17:22:02', 1),
(7, 'ako', 'gan', 'gantot@gmail.com', '$2y$10$iN98PUiKRtyES9W1A1YLyO2pAk/HRFDrrfuZ0u49pGxUk/e4UuJuO', '231242424', '2023-12-24 21:11:59', 1),
(8, 'akoyikaw', 'ikawako', 'ikaw@gmail.com', '$2y$10$YTBbgfpbF3aO5sTpjZo4Tu.dsoxO/OiXLUw5g/WePtQe7HVx/B5h6', '321312355', '2023-12-25 19:45:10', 0),
(9, 'Haripath', 'De Jesus', 'hari@gmail.com', '$2y$10$haPAdD0DjUS0vQ7CVNyxHeGjeaEK6FjPTz3hcv5rqX0Hy5bFUG4vm', '09394323123', '2024-01-01 06:29:52', 0),
(10, 'Caella', 'Uson', 'caella@gmail.com', '$2y$10$T1PBsvoTvHGQHnJX4sjY1OekXtwuYrO1mWTvSqXK8I5S52rkqoiFu', '99233223', '2024-01-01 06:37:49', 0),
(11, 'path', 'hari', 'path@gmail.com', '$2y$10$6Frd0v39TRZ0.8sVlcEYvO8Oym1UF2IAgV80ejOdwa44RHMFMEvr6', '423213123', '2024-01-01 07:29:16', 0),
(12, 'mic', 'de je', 'dejesus@gmail.com', '$2y$10$DYxwm3lH.sRedHt59qDN8Ops99gK9wDfaImaXIMZVnv23MDQcsh4W', '23213123', '2024-01-01 07:33:46', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `gcash_payments`
--
ALTER TABLE `gcash_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `membership`
--
ALTER TABLE `membership`
  ADD PRIMARY KEY (`membership_id`);

--
-- Indexes for table `superadmin`
--
ALTER TABLE `superadmin`
  ADD PRIMARY KEY (`superadmin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `gcash_payments`
--
ALTER TABLE `gcash_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `membership`
--
ALTER TABLE `membership`
  MODIFY `membership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `superadmin`
--
ALTER TABLE `superadmin`
  MODIFY `superadmin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gcash_payments`
--
ALTER TABLE `gcash_payments`
  ADD CONSTRAINT `gcash_payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
