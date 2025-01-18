-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2025 at 02:09 AM
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
-- Database: `baboohouse`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `building_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `identification_card` varchar(50) DEFAULT NULL,
  `rental_price` decimal(10,2) DEFAULT NULL,
  `lease_term` varchar(255) DEFAULT NULL,
  `payment_term` varchar(255) DEFAULT NULL,
  `lease_start_date` date DEFAULT NULL,
  `lease_end_date` date DEFAULT NULL,
  `photo_urls` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `building_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `rental_price` decimal(10,2) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `owner_phone` varchar(50) DEFAULT NULL,
  `building_type` varchar(50) DEFAULT NULL,
  `electricity_price` decimal(10,2) DEFAULT NULL,
  `water_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `photo_urls` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `management_income`
--

CREATE TABLE `management_income` (
  `management_income_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `commission` decimal(10,2) DEFAULT NULL,
  `received_commission` decimal(10,2) DEFAULT NULL,
  `actual_income` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `building_id` int(11) DEFAULT NULL,
  `room_name` varchar(255) DEFAULT NULL,
  `area` decimal(10,2) DEFAULT NULL,
  `rental_price` decimal(10,2) DEFAULT NULL,
  `room_type` varchar(50) DEFAULT NULL,
  `room_status` varchar(50) DEFAULT NULL,
  `photo_urls` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_income`
--

CREATE TABLE `sale_income` (
  `sale_income_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `commission` decimal(10,2) DEFAULT NULL,
  `received_commission` decimal(10,2) DEFAULT NULL,
  `actual_income` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `hometown` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `photo_urls` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `address`, `hometown`, `birthdate`, `phone`, `email`, `username`, `password`, `role`, `photo_urls`) VALUES
(1, NULL, NULL, NULL, NULL, NULL, 'admin@example.com', 'admin', '123456', 'admin', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `management_income`
--
ALTER TABLE `management_income`
  ADD PRIMARY KEY (`management_income_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `building_id` (`building_id`);

--
-- Indexes for table `sale_income`
--
ALTER TABLE `sale_income`
  ADD PRIMARY KEY (`sale_income_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `management_income`
--
ALTER TABLE `management_income`
  MODIFY `management_income_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sale_income`
--
ALTER TABLE `sale_income`
  MODIFY `sale_income_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `management_income`
--
ALTER TABLE `management_income`
  ADD CONSTRAINT `management_income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `management_income_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`);

--
-- Constraints for table `sale_income`
--
ALTER TABLE `sale_income`
  ADD CONSTRAINT `sale_income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `sale_income_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
