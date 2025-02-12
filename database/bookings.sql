-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2025 at 02:42 PM
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
  `phone` varchar(20) DEFAULT NULL,
  `guest_name` varchar(255) DEFAULT NULL,
  `identification_card` varchar(50) DEFAULT NULL,
  `rental_price` decimal(10,2) DEFAULT NULL,
  `signed_date` date DEFAULT NULL,
  `deposit_term` date DEFAULT NULL,
  `lease_term` varchar(255) DEFAULT NULL,
  `payment_term` varchar(50) DEFAULT NULL,
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
  `street` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `rental_price` decimal(10,2) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `owner_phone` varchar(50) DEFAULT NULL,
  `building_type` varchar(50) DEFAULT NULL,
  `electricity_price` decimal(10,2) DEFAULT NULL,
  `water_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `last_modified` datetime DEFAULT NULL,
  `photo_urls` text DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`building_id`, `user_id`, `name`, `street`, `district`, `city`, `rental_price`, `owner_name`, `owner_phone`, `building_type`, `electricity_price`, `water_price`, `description`, `last_modified`, `photo_urls`, `approved`) VALUES
(1, 2, 'Tòa Nhà A', '123 Đường A', 'Hải Châu', 'Đà Nẵng', 5000.00, 'Nguyễn Văn A', '0901234567', 'Chung cư', 1000.00, 5000.00, 'Tòa nhà đẹp và hiện đại.', NOW(), NULL, 1),
(2, 2, 'Tòa Nhà B', '456 Đường B', 'Thanh Khê', 'Đà Nẵng', 6000.00, 'Trần Thị B', '0912345678', 'Chung cư', 1200.00, 6000.00, 'Tòa nhà cao cấp với đầy đủ tiện nghi.', NOW(), NULL, 1),
(3, 3, 'Tòa Nhà C', '789 Đường C', 'Ngũ Hành Sơn', 'Đà Nẵng', 5500.00, 'Lê Văn C', '0923456789', 'Chung cư', 1100.00, 5500.00, 'Tòa nhà nằm gần trung tâm.', NOW(), NULL, 1),
(4, 4, 'Tòa Nhà D', '321 Đường D', 'Liên Chiểu', 'Đà Nẵng', 6500.00, 'Phạm Thị D', '0934567890', 'Chung cư', 1300.00, 6500.00, 'Tòa nhà có view đẹp.', NOW(), NULL, 1),
(5, 5, 'Tòa Nhà E', '654 Đường E', 'Sơn Trà', 'Đà Nẵng', 7000.00, 'Đỗ Văn E', '0945678901', 'Chung cư', 1400.00, 7000.00, 'Tòa nhà an ninh tốt.', NOW(), NULL, 1);
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

--
-- Dumping data for table `management_income`
--

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

--
-- Dumping data for table `rooms`
--
INSERT INTO `rooms` (`room_id`, `building_id`, `room_name`, `area`, `rental_price`, `room_type`, `room_status`, `photo_urls`) VALUES
(1, 1, 'Phòng 101', 30.00, 3000000.00, 'Phòng đơn', 'Còn trống', NULL),
(2, 1, 'Phòng 102', 35.00, 3500000.00, 'Phòng đôi', 'Còn trống', NULL),
(3, 1, 'Phòng 103', 40.00, 4000000.00, 'Phòng VIP', 'Còn trống', NULL),
(4, 2, 'Phòng 201', 30.00, 3200000.00, 'Phòng đơn', 'Còn trống', NULL),
(5, 2, 'Phòng 202', 35.00, 3700000.00, 'Phòng đôi', 'Còn trống', NULL),
(6, 2, 'Phòng 203', 45.00, 4200000.00, 'Phòng VIP', 'Còn trống', NULL),
(7, 3, 'Phòng 301', 32.00, 3100000.00, 'Phòng đơn', 'Còn trống', NULL),
(8, 3, 'Phòng 302', 38.00, 3600000.00, 'Phòng đôi', 'Còn trống', NULL),
(9, 3, 'Phòng 303', 42.00, 4100000.00, 'Phòng VIP', 'Còn trống', NULL),
(10, 4, 'Phòng 401', 30.00, 3300000.00, 'Phòng đơn', 'Còn trống', NULL),
(11, 4, 'Phòng 402', 36.00, 3800000.00, 'Phòng đôi', 'Còn trống', NULL),
(12, 4, 'Phòng 403', 44.00, 4300000.00, 'Phòng VIP', 'Còn trống', NULL),
(13, 5, 'Phòng 501', 31.00, 3400000.00, 'Phòng đơn', 'Còn trống', NULL),
(14, 5, 'Phòng 502', 37.00, 3900000.00, 'Phòng đôi', 'Còn trống', NULL),
(15, 5, 'Phòng 503', 43.00, 4400000.00, 'Phòng VIP', 'Còn trống', NULL);
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

--
-- Dumping data for table `sale_income`
--


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
  `last_access` datetime DEFAULT NULL,
  `photo_urls` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `address`, `hometown`, `birthdate`, `phone`, `email`, `username`, `password`, `role`, `last_access`, `photo_urls`) VALUES
(1, 'Quản Trị Viên', '123 Đường Nguyễn Thị Minh Khai, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1985-02-15', '0901234567', 'admin@baboohouse.vn', 'admin', 'hashed_password', 'admin', '2025-02-04 11:53:00', NULL),
(2, 'Nguyễn Văn A', '456 Đường Lê Lai, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Hà Nội', '1990-01-01', '0912345678', 'a.nguyen@example.com', 'nguyenvana', 'matkhau123', 'user', NOW(), NULL),
(3, 'Trần Thị B', '789 Đường Trần Hưng Đạo, Phường 7, Quận 5, Hồ Chí Minh', 'Đà Nẵng', '1988-03-15', '0923456789', 'b.tran@example.com', 'tranthib', 'matkhau123', 'user', NOW(), NULL),
(4, 'Lê Văn C', '321 Đường Nguyễn Thái Bình, Phường 12, Quận 3, Hồ Chí Minh', 'Hải Phòng', '1995-05-25', '0934567890', 'c.le@example.com', 'levanc', 'matkhau123', 'user', NOW(), NULL),
(5, 'Phạm Thị D', '654 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Nha Trang', '1992-07-30', '0945678901', 'd.pham@example.com', 'phamthid', 'matkhau123', 'user', NOW(), NULL),
(6, 'Đỗ Văn E', '987 Đường Lê Lợi, Phường 6, Quận 1, Hồ Chí Minh', 'Vũng Tàu', '1989-09-20', '0956789012', 'e.do@example.com', 'dovanE', 'matkhau123', 'user', NOW(), NULL),
(7, 'Ngô Thị F', '135 Đường Hùng Vương, Phường 10, Quận 5, Hồ Chí Minh', 'Cần Thơ', '1991-11-11', '0967890123', 'f.ngo@example.com', 'ngothif', 'matkhau123', 'user', NOW(), NULL),
(8, 'Bùi Văn G', '246 Đường Võ Văn Kiệt, Phường 2, Quận 1, Hồ Chí Minh', 'Long An', '1993-12-12', '0978901234', 'g.bui@example.com', 'buivang', 'matkhau123', 'user', NOW(), NULL),
(9, 'Vũ Thị H', '369 Đường Lê Văn Sỹ, Phường 13, Quận 3, Hồ Chí Minh', 'Tiền Giang', '1994-04-04', '0989012345', 'h.vu@example.com', 'vuthih', 'matkhau123', 'user', NOW(), NULL),
(10, 'Nguyễn Văn I', '159 Đường Trần Quốc Thảo, Phường 9, Quận 3, Hồ Chí Minh', 'Ninh Thuận', '1987-06-06', '0990123456', 'i.nguyen@example.com', 'nguyenvani', 'matkhau123', 'user', NOW(), NULL),
(11, 'Lê Thị K', '753 Đường Nguyễn Văn Cừ, Phường 2, Quận 5, Hồ Chí Minh', 'Bạc Liêu', '1996-08-08', '0901234567', 'k.le@example.com', 'lethik', 'matkhau123', 'user', NOW(), NULL);


CREATE TABLE `notifications` (
    `id` int(11) NOT NULL,
    `user_id` int(11) NOT NULL,
    `building_id` int(11) DEFAULT NULL,
    `booking_id` int(11) DEFAULT NULL,
    `message` VARCHAR(255) DEFAULT NULL,
    `type` VARCHAR(50) DEFAULT 'general',
    `is_read` BOOLEAN DEFAULT FALSE,
    `created_at` datetime DEFAULT NOW()
)  ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications` 
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `booking_id` (`booking_id`);



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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `buildings`
--
ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `management_income`
--
ALTER TABLE `management_income`
  ADD CONSTRAINT `management_income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `management_income_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_income`
--
ALTER TABLE `sale_income`
  ADD CONSTRAINT `sale_income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_income_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;

ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;