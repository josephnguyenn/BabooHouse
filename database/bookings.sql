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
SET time_zone = "+07:00";
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
  `photo_urls` text DEFAULT NULL,
  `approved` TINYINT(1) DEFAULT 0
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
  `last_access` datetime DEFAULT NULL,
  `photo_urls` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Indexes for dumped tables
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `building_id` (`building_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `buildings`
  ADD PRIMARY KEY (`building_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `management_income`
  ADD PRIMARY KEY (`management_income_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`),
  ADD KEY `building_id` (`building_id`);

ALTER TABLE `sale_income`
  ADD PRIMARY KEY (`sale_income_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `booking_id` (`booking_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT for dumped tables
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `buildings`
  MODIFY `building_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `management_income`
  MODIFY `management_income_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `sale_income`
  MODIFY `sale_income_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Constraints for dumped tables
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

ALTER TABLE `buildings`
  ADD CONSTRAINT `buildings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

ALTER TABLE `management_income`
  ADD CONSTRAINT `management_income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `management_income_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`building_id`) ON DELETE CASCADE;

ALTER TABLE `sale_income`
  ADD CONSTRAINT `sale_income_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_income_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE;

COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Sample data for `users`
INSERT INTO `users` (`user_id`, `name`, `address`, `hometown`, `birthdate`, `phone`, `email`, `username`, `password`, `role`, `last_access`, `photo_urls`) VALUES
(1, 'Quản Trị Viên', '123 Đường Nguyễn Thị Minh Khai, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1985-02-15', '0901234567', 'admin@baboohouse.vn', 'admin', 'hashed_password', 'admin', NOW(), NULL),
(2, 'Nguyễn Văn A', '456 Đường Lê Lợi, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1990-01-01', '0912345678', 'a@example.com', 'user_a', 'hashed_password', 'user', NOW(), NULL),
(3, 'Trần Thị B', '789 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1992-02-02', '0908765432', 'b@example.com', 'user_b', 'hashed_password', 'user', NOW(), NULL),
(4, 'Lê Văn C', '101 Đường Phạm Ngọc Thạch, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1988-03-03', '0934567890', 'c@example.com', 'user_c', 'hashed_password', 'user', NOW(), NULL),
(5, 'Phạm Thị D', '202 Đường Cách Mạng Tháng Tám, Phường 10, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1995-04-04', '0945678901', 'd@example.com', 'user_d', 'hashed_password', 'user', NOW(), NULL),
(6, 'Nguyễn Văn E', '303 Đường Nguyễn Thị Minh Khai, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1993-05-05', '0956789012', 'e@example.com', 'user_e', 'hashed_password', 'user', NOW(), NULL),
(7, 'Trần Thị F', '404 Đường Lê Văn Sĩ, Phường 13, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1991-06-06', '0967890123', 'f@example.com', 'user_f', 'hashed_password', 'user', NOW(), NULL),
(8, 'Lê Văn G', '505 Đường Đinh Tiên Hoàng, Phường 1, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1989-07-07', '0978901234', 'g@example.com', 'user_g', 'hashed_password', 'user', NOW(), NULL),
(9, 'Phạm Thị H', '606 Đường Xô Viết Nghệ Tĩnh, Phường 17, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1987-08-08', '0989012345', 'h@example.com', 'user_h', 'hashed_password', 'user', NOW(), NULL),
(10, 'Nguyễn Văn I', '707 Đường Lê Quý Đôn, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1994-09-09', '0990123456', 'i@example.com', 'user_i', 'hashed_password', 'user', NOW(), NULL),
(11, 'Trần Thị J', '808 Đường Bạch Đằng, Phường 2, Quận Tân Bình, Hồ Chí Minh', 'Hồ Chí Minh', '1996-10-10', '0901123456', 'j@example.com', 'user_j', 'hashed_password', 'user', NOW(), NULL),
(12, 'Lê Văn K', '909 Đường Hồng Bàng, Phường 12, Quận 5, Hồ Chí Minh', 'Hồ Chí Minh', '1988-11-11', '0912234567', 'k@example.com', 'user_k', 'hashed_password', 'user', NOW(), NULL),
(13, 'Phạm Thị L', '1010 Đường Trần Hưng Đạo, Phường 1, Quận 5, Hồ Chí Minh', 'Hồ Chí Minh', '1990-12-12', '0923345678', 'l@example.com', 'user_l', 'hashed_password', 'user', NOW(), NULL),
(14, 'Nguyễn Văn M', '1111 Đường Nguyễn Trãi, Phường 3, Quận 5, Hồ Chí Minh', 'Hồ Chí Minh', '1985-01-13', '0934456789', 'm@example.com', 'user_m', 'hashed_password', 'user', NOW(), NULL),
(15, 'Trần Thị N', '1212 Đường Lê Thánh Tôn, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1992-02-14', '0945567890', 'n@example.com', 'user_n', 'hashed_password', 'user', NOW(), NULL),
(16, 'Lê Văn O', '1313 Đường Nguyễn Cư Trinh, Phường Phạm Ngũ Lão, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1987-03-15', '0956678901', 'o@example.com', 'user_o', 'hashed_password', 'user', NOW(), NULL),
(17, 'Phạm Thị P', '1414 Đường Trần Đình Xu, Phường Nguyễn Cư Trinh, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1993-04-16', '0967789012', 'p@example.com', 'user_p', 'hashed_password', 'user', NOW(), NULL),
(18, 'Nguyễn Văn Q', '1515 Đường Hàng Xanh, Phường 17, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1989-05-17', '0978890123', 'q@example.com', 'user_q', 'hashed_password', 'user', NOW(), NULL),
(19, 'Trần Thị R', '1616 Đường Nguyễn Thái Học, Phường 9, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1994-06-18', '0989901234', 'r@example.com', 'user_r', 'hashed_password', 'user', NOW(), NULL),
(20, 'Lê Văn S', '1717 Đường Bến Vân Đồn, Phường 1, Quận 4, Hồ Chí Minh', 'Hồ Chí Minh', '1991-07-19', '0901234568', 's@example.com', 'user_s', 'hashed_password', 'user', NOW(), NULL),
(21, 'Phạm Thị T', '1818 Đường Nguyễn Tất Thành, Phường 18, Quận 4, Hồ Chí Minh', 'Hồ Chí Minh', '1995-08-20', '0912345679', 't@example.com', 'user_t', 'hashed_password', 'user', NOW(), NULL),
(22, 'Nguyễn Văn U', '1919 Đường Nguyễn Huệ, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1986-09-21', '0923456780', 'u@example.com', 'user_u', 'hashed_password', 'user', NOW(), NULL),
(23, 'Trần Thị V', '2020 Đường Đinh Bộ Lĩnh, Phường 26, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1992-10-22', '0934567891', 'v@example.com', 'user_v', 'hashed_password', 'user', NOW(), NULL),
(24, 'Lê Văn W', '2121 Đường Điện Biên Phủ, Phường 15, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1988-11-23', '0945678902', 'w@example.com', 'user_w', 'hashed_password', 'user', NOW(), NULL),
(25, 'Phạm Thị X', '2222 Đường Trần Khánh Dư, Phường Tân Định, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1990-12-24', '0956789013', 'x@example.com', 'user_x', 'hashed_password', 'user', NOW(), NULL),
(26, 'Nguyễn Văn Y', '2323 Đường Trần Quốc Thảo, Phường 7, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1994-01-25', '0967890124', 'y@example.com', 'user_y', 'hashed_password', 'user', NOW(), NULL),
(27, 'Trần Thị Z', '2424 Đường Nam Kỳ Khởi Nghĩa, Phường 7, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1996-02-26', '0978901235', 'z@example.com', 'user_z', 'hashed_password', 'user', NOW(), NULL),
(28, 'Lê Văn AA', '2525 Đường Cao Thắng, Phường 12, Quận 10, Hồ Chí Minh', 'Hồ Chí Minh', '1991-03-27', '0989012346', 'aa@example.com', 'user_aa', 'hashed_password', 'user', NOW(), NULL),
(29, 'Phạm Thị BB', '2626 Đường Trương Định, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1993-04-28', '0901123457', 'bb@example.com', 'user_bb', 'hashed_password', 'user', NOW(), NULL),
(30, 'Nguyễn Văn CC', '2727 Đường Nguyễn Hữu Cảnh, Phường 22, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1988-05-29', '0912234568', 'cc@example.com', 'user_cc', 'hashed_password', 'user', NOW(), NULL),
(31, 'Trần Thị DD', '2828 Đường Đinh Tiên Hoàng, Phường 1, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1995-06-30', '0923345679', 'dd@example.com', 'user_dd', 'hashed_password', 'user', NOW(), NULL),
(32, 'Lê Văn EE', '2929 Đường Phạm Văn Đồng, Phường 25, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1987-07-31', '0934456780', 'ee@example.com', 'user_ee', 'hashed_password', 'user', NOW(), NULL),
(33, 'Phạm Thị FF', '3030 Đường Lạc Long Quân, Phường 10, Quận 11, Hồ Chí Minh', 'Hồ Chí Minh', '1994-08-01', '0945567891', 'ff@example.com', 'user_ff', 'hashed_password', 'user', NOW(), NULL),
(34, 'Nguyễn Văn GG', '3131 Đường Tôn Thất Thuyết, Phường 1, Quận 4, Hồ Chí Minh', 'Hồ Chí Minh', '1991-09-02', '0956678902', 'gg@example.com', 'user_gg', 'hashed_password', 'user', NOW(), NULL),
(35, 'Trần Thị HH', '3232 Đường Lê Thánh Tôn, Phường Bến Nghé, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1999-10-03', '0967789013', 'hh@example.com', 'user_hh', 'hashed_password', 'user', NOW(), NULL),
(36, 'Lê Văn II', '3333 Đường Nguyễn Tất Thành, Phường 18, Quận 4, Hồ Chí Minh', 'Hồ Chí Minh', '1986-11-04', '0978890124', 'ii@example.com', 'user_ii', 'hashed_password', 'user', NOW(), NULL),
(37, 'Phạm Thị JJ', '3434 Đường Trần Hưng Đạo, Phường 1, Quận 5, Hồ Chí Minh', 'Hồ Chí Minh', '1995-12-05', '0989901235', 'jj@example.com', 'user_jj', 'hashed_password', 'user', NOW(), NULL),
(38, 'Nguyễn Văn KK', '3535 Đường Nguyễn Thị Minh Khai, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1998-01-06', '0901234568', 'kk@example.com', 'user_kk', 'hashed_password', 'user', NOW(), NULL),
(39, 'Trần Thị LL', '3636 Đường Trần Quốc Thảo, Phường 7, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1992-02-07', '0912345679', 'll@example.com', 'user_ll', 'hashed_password', 'user', NOW(), NULL),
(40, 'Lê Văn MM', '3737 Đường Nguyễn Thái Học, Phường 9, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1989-03-08', '0923456780', 'mm@example.com', 'user_mm', 'hashed_password', 'user', NOW(), NULL),
(41, 'Phạm Thị NN', '3838 Đường Lê Văn Sĩ, Phường 13, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1994-04-09', '0934567891', 'nn@example.com', 'user_nn', 'hashed_password', 'user', NOW(), NULL),
(42, 'Nguyễn Văn OO', '3939 Đường Đinh Bộ Lĩnh, Phường 26, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1986-05-10', '0945678902', 'oo@example.com', 'user_oo', 'hashed_password', 'user', NOW(), NULL),
(43, 'Trần Thị PP', '4040 Đường Phan Xích Long, Phường 2, Quận Phú Nhuận, Hồ Chí Minh', 'Hồ Chí Minh', '1991-06-11', '0956789013', 'pp@example.com', 'user_pp', 'hashed_password', 'user', NOW(), NULL),
(44, 'Lê Văn QQ', '4141 Đường Lê Quý Đôn, Phường 6, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1995-07-12', '0967890124', 'qq@example.com', 'user_qq', 'hashed_password', 'user', NOW(), NULL),
(45, 'Phạm Thị RR', '4242 Đường Cách Mạng Tháng Tám, Phường 10, Quận 3, Hồ Chí Minh', 'Hồ Chí Minh', '1987-08-13', '0978901235', 'rr@example.com', 'user_rr', 'hashed_password', 'user', NOW(), NULL),
(46, 'Nguyễn Văn SS', '4343 Đường Nguyễn Cư Trinh, Phường Phạm Ngũ Lão, Quận 1, Hồ Chí Minh', 'Hồ Chí Minh', '1993-09-14', '0989012346', 'ss@example.com', 'user_ss', 'hashed_password', 'user', NOW(), NULL),
(47, 'Trần Thị TT', '4444 Đường Xô Viết Nghệ Tĩnh, Phường 17, Quận Bình Thạnh, Hồ Chí Minh', 'Hồ Chí Minh', '1990-10-15', '0901123457', 'tt@example.com', 'user_tt', 'hashed_password', 'user', NOW(), NULL),
(48, 'Lê Văn UU', '4545 Đường Bến Vân Đồn, Phường 1, Quận 4, Hồ Chí Minh', 'Hồ Chí Minh', '1989-11-16', '0912234568', 'uu@example.com', 'user_uu', 'hashed_password', 'user', NOW(), NULL),
(49, 'Phạm Thị VV', '4646 Đường Nguyễn Thái Bình, Phường 2, Quận Tân Bình, Hồ Chí Minh', 'Hồ Chí Minh', '1991-12-17', '0923345679', 'vv@example.com', 'user_vv', 'hashed_password', 'user', NOW(), NULL),
(50, 'Nguyễn Văn WW', '4747 Đường Trần Hưng Đạo, Phường 1, Quận 5, Hồ Chí Minh', 'Hồ Chí Minh', '1992-01-18', '0934456780', 'ww@example.com', 'user_ww', 'hashed_password', 'user', NOW(), NULL);


-- Sample data for `buildings`
INSERT INTO `buildings` (building_id, user_id, name, address, rental_price, owner_name, owner_phone, building_type, electricity_price, water_price, description, last_modified, photo_urls, approved) VALUES
(1, 1, 'Tòa nhà A', '123 Lê Lai, Quận 1', 1000.00, 'Nguyễn Văn A', '0901234567', 'Căn hộ', 3.00, 2.00, 'Căn hộ đẹp ở trung tâm thành phố.', NOW(), NULL, 1),
(2, 2, 'Tòa nhà B', '456 Nguyễn Huệ, Quận 1', 1500.00, 'Trần Thị B', '0902345678', 'Chung cư', 3.50, 2.50, 'Chung cư sang trọng với nhiều tiện nghi.', NOW(), NULL, 1),
(3, 3, 'Tòa nhà C', '789 Võ Văn Kiệt, Quận 1', 900.00, 'Lê Văn C', '0903456789', 'Văn phòng', 2.50, 1.50, 'Không gian văn phòng hiện đại.', NOW(), NULL, 1),
(4, 4, 'Tòa nhà D', '321 Nguyễn Thi Minh Khai, Quận 3', 800.00, 'Phạm Văn D', '0904567890', 'Căn hộ', 2.80, 1.80, 'Căn hộ ấm cúng trong khu vực yên tĩnh.', NOW(), NULL, 1),
(5, 6, 'Tòa nhà E', '654 Trần Hưng Đạo, Quận 5', 700.00, 'Nguyễn Thị E', '0905678901', 'Cửa hàng', 3.20, 2.20, 'Mặt bằng cửa hàng ở khu vực đông đúc.', NOW(), NULL, 1),
(6, 9, 'Tòa nhà F', '111 Đinh Tiên Hoàng, Quận Bình Thạnh', 850.00, 'Nguyễn Văn F', '0906789012', 'Nhà ở', 2.90, 1.90, 'Nhà ở gần công viên.', NOW(), NULL, 1),
(7, 10, 'Tòa nhà G', '222 Nguyễn Văn Cừ, Quận 5', 950.00, 'Trần Thị G', '0907890123', 'Căn hộ', 3.10, 2.10, 'Căn hộ với tầm nhìn đẹp.', NOW(), NULL, 1),
(8, 10, 'Tòa nhà H', '333 Lê Văn Sỹ, Quận 3', 1100.00, 'Lê Văn H', '0908901234', 'Chung cư', 3.40, 2.40, 'Chung cư gần trường học.', NOW(), NULL, 1),
(9, 10, 'Tòa nhà I', '444 Nguyễn Thị Thập, Quận 7', 1200.00, 'Nguyễn Thị I', '0909012345', 'Nhà ở', 3.60, 2.60, 'Nhà ở gần trung tâm thương mại.', NOW(), NULL, 1),
(10, 14, 'Tòa nhà J', '555 Trường Sa, Quận 3', 1300.00, 'Phạm Văn J', '0900123456', 'Văn phòng', 3.70, 2.70, 'Văn phòng tiện nghi.', NOW(), NULL, 1),
(11, 16, 'Tòa nhà K', '666 Hoàng Sa, Quận 1', 1400.00, 'Nguyễn Văn K', '0901234568', 'Căn hộ', 3.80, 2.80, 'Căn hộ sang trọng với hồ bơi.', NOW(), NULL, 1),
(12, 18, 'Tòa nhà L', '777 Trần Não, Quận 2', 1600.00, 'Trần Thị L', '0902345679', 'Chung cư', 3.90, 2.90, 'Chung cư bên bờ sông.', NOW(), NULL, 1),
(13, 20, 'Tòa nhà M', '888 Đường số 1, Quận 2', 1700.00, 'Lê Văn M', '0903456780', 'Nhà ở', 4.00, 3.00, 'Nhà ở gần khu vui chơi.', NOW(), NULL, 1),
(14, 30, 'Tòa nhà N', '999 Lê Quý Đôn, Quận 3', 1800.00, 'Nguyễn Thị N', '0904567891', 'Cửa hàng', 4.10, 3.10, 'Cửa hàng trong khu vực đông đúc.', NOW(), NULL, 1),
(15, 33, 'Tòa nhà O', '1010 Nguyễn Đình Chiểu, Quận 3', 1900.00, 'Phạm Văn O', '0905678902', 'Văn phòng', 4.20, 3.20, 'Văn phòng có vị trí thuận lợi.', NOW(), NULL, 1),
(16, 36, 'Tòa nhà P', '1111 Trương Định, Quận 1', 2000.00, 'Nguyễn Văn P', '0906789013', 'Căn hộ', 4.30, 3.30, 'Căn hộ hiện đại với đầy đủ nội thất.', NOW(), NULL, 1),
(17, 39, 'Tòa nhà Q', '1212 Phạm Ngọc Thạch, Quận 1', 2100.00, 'Trần Thị Q', '0907890124', 'Chung cư', 4.40, 3.40, 'Chung cư gần bến xe.', NOW(), NULL, 1),
(18, 41, 'Tòa nhà R', '1313 Ngô Thời Nhiệm, Quận 5', 2200.00, 'Lê Văn R', '0908901235', 'Nhà ở', 4.50, 3.50, 'Nhà ở gần trường đại học.', NOW(), NULL, 1),
(19, 41, 'Tòa nhà S', '1414 Nguyễn Tất Thành, Quận 4', 2300.00, 'Nguyễn Thị S', '0909012346', 'Cửa hàng', 4.60, 3.60, 'Cửa hàng ở khu vực đông đúc.', NOW(), NULL, 1),
(20, 42, 'Tòa nhà T', '1515 Lê Văn Khương, Quận 12', 2400.00, 'Phạm Văn T', '0900123457', 'Văn phòng', 4.70, 3.70, 'Văn phòng với tầm nhìn đẹp.', NOW(), NULL, 1),
(21, 44, 'Tòa nhà U', '1616 Đường số 2, Quận 9', 2500.00, 'Nguyễn Văn U', '0901234569', 'Căn hộ', 4.80, 3.80, 'Căn hộ gần khu công nghệ cao.', NOW(), NULL, 1),
(22, 48, 'Tòa nhà V', '1717 Nguyễn Huệ, Quận 1', 2600.00, 'Trần Thị V', '0902345670', 'Chung cư', 4.90, 3.90, 'Chung cư sang trọng ở trung tâm.', NOW(), NULL, 1),
(23, 48, 'Tòa nhà W', '1818 Võ Thị Sáu, Quận 3', 2700.00, 'Lê Văn W', '0903456781', 'Nhà ở', 5.00, 4.00, 'Nhà ở yên tĩnh và thoáng mát.', NOW(), NULL, 1),
(24, 49, 'Tòa nhà X', '1919 Trần Quốc Toản, Quận 10', 2800.00, 'Nguyễn Thị X', '0904567892', 'Cửa hàng', 5.10, 4.10, 'Cửa hàng có vị trí đắc địa.', NOW(), NULL, 1),
(25, 49, 'Tòa nhà Y', '2020 Trần Hưng Đạo, Quận 5', 2900.00, 'Phạm Văn Y', '0905678903', 'Văn phòng', 5.20, 4.20, 'Văn phòng hiện đại với trang thiết bị đầy đủ.', NOW(), NULL, 1);


-- Sample data for `rooms`
INSERT INTO `rooms` (room_id, building_id, room_name, area, rental_price, room_type, room_status, photo_urls) VALUES
(1, 1, 'Phòng 101', 30.00, 500.00, 'Căn hộ', 'Còn trống', NULL),
(2, 1, 'Phòng 102', 25.00, 450.00, 'Căn hộ', 'Còn trống', NULL),
(3, 1, 'Phòng 201', 35.00, 600.00, 'Căn hộ', 'Còn trống', NULL),
(4, 1, 'Phòng 202', 28.00, 550.00, 'Căn hộ', 'Còn trống', NULL),
(5, 2, 'Phòng 301', 40.00, 700.00, 'Văn phòng', 'Còn trống', NULL),
(6, 2, 'Phòng 302', 45.00, 750.00, 'Văn phòng', 'Còn trống', NULL),
(7, 2, 'Phòng 401', 30.00, 500.00, 'Căn hộ', 'Còn trống', NULL),
(8, 2, 'Phòng 402', 32.00, 520.00, 'Căn hộ', 'Còn trống', NULL),
(9, 2, 'Phòng 501', 20.00, 400.00, 'Cửa hàng', 'Còn trống', NULL),
(10, 25, 'Phòng 502', 22.00, 380.00, 'Cửa hàng', 'Còn trống', NULL),
(11, 24, 'Phòng 601', 25.00, 450.00, 'Căn hộ', 'Còn trống', NULL),
(12, 25, 'Phòng 602', 27.00, 480.00, 'Căn hộ', 'Còn trống', NULL),
(13, 24, 'Phòng 701', 35.00, 600.00, 'Căn hộ', 'Còn trống', NULL),
(14, 24, 'Phòng 702', 30.00, 550.00, 'Căn hộ', 'Còn trống', NULL),
(15, 3, 'Phòng 801', 20.00, 400.00, 'Cửa hàng', 'Còn trống', NULL),
(16, 3, 'Phòng 802', 22.00, 420.00, 'Cửa hàng', 'Còn trống', NULL),
(17, 3, 'Phòng 901', 28.00, 480.00, 'Nhà ở', 'Còn trống', NULL),
(18, 4, 'Phòng 902', 30.00, 500.00, 'Nhà ở', 'Còn trống', NULL),
(19, 4, 'Phòng 1001', 40.00, 700.00, 'Văn phòng', 'Còn trống', NULL),
(20, 4, 'Phòng 1002', 45.00, 750.00, 'Văn phòng', 'Còn trống', NULL),
(21, 4, 'Phòng 1101', 30.00, 500.00, 'Căn hộ', 'Còn trống', NULL),
(22, 5, 'Phòng 1102', 32.00, 520.00, 'Căn hộ', 'Còn trống', NULL),
(23, 5, 'Phòng 1201', 35.00, 600.00, 'Căn hộ', 'Còn trống', NULL),
(24, 6, 'Phòng 1202', 28.00, 550.00, 'Căn hộ', 'Còn trống', NULL),
(25, 6, 'Phòng 1301', 40.00, 700.00, 'Nhà ở', 'Còn trống', NULL),
(26, 6, 'Phòng 1302', 45.00, 750.00, 'Nhà ở', 'Còn trống', NULL),
(27, 7, 'Phòng 1401', 30.00, 500.00, 'Cửa hàng', 'Còn trống', NULL),
(28, 7, 'Phòng 1402', 32.00, 520.00, 'Cửa hàng', 'Còn trống', NULL),
(29, 8, 'Phòng 1501', 25.00, 450.00, 'Văn phòng', 'Còn trống', NULL),
(30, 8, 'Phòng 1502', 27.00, 480.00, 'Văn phòng', 'Còn trống', NULL),
(31, 9, 'Phòng 1601', 35.00, 600.00, 'Căn hộ', 'Còn trống', NULL),
(32, 9, 'Phòng 1602', 30.00, 550.00, 'Căn hộ', 'Còn trống', NULL),
(33, 9, 'Phòng 1701', 28.00, 480.00, 'Nhà ở', 'Còn trống', NULL),
(34, 10, 'Phòng 1702', 30.00, 500.00, 'Nhà ở', 'Còn trống', NULL),
(35, 11, 'Phòng 1801', 40.00, 700.00, 'Văn phòng', 'Còn trống', NULL),
(36, 12, 'Phòng 1802', 45.00, 750.00, 'Văn phòng', 'Còn trống', NULL),
(37, 13, 'Phòng 1901', 20.00, 400.00, 'Cửa hàng', 'Còn trống', NULL),
(38, 13, 'Phòng 1902', 22.00, 420.00, 'Cửa hàng', 'Còn trống', NULL),
(39, 13, 'Phòng 2001', 35.00, 600.00, 'Căn hộ', 'Còn trống', NULL),
(40, 14, 'Phòng 2002', 30.00, 550.00, 'Căn hộ', 'Còn trống', NULL),
(41, 14, 'Phòng 2101', 28.00, 480.00, 'Nhà ở', 'Còn trống', NULL),
(42, 15, 'Phòng 2102', 30.00, 500.00, 'Nhà ở', 'Còn trống', NULL),
(43, 16, 'Phòng 2201', 40.00, 700.00, 'Văn phòng', 'Còn trống', NULL),
(44, 17, 'Phòng 2202', 45.00, 750.00, 'Văn phòng', 'Còn trống', NULL),
(45, 18, 'Phòng 2301', 30.00, 500.00, 'Căn hộ', 'Còn trống', NULL),
(46, 19, 'Phòng 2302', 32.00, 520.00, 'Căn hộ', 'Còn trống', NULL),
(47, 20, 'Phòng 2401', 35.00, 600.00, 'Chung cư', 'Còn trống', NULL),
(48, 21, 'Phòng 2402', 28.00, 550.00, 'Chung cư', 'Còn trống', NULL),
(49, 22, 'Phòng 2501', 40.00, 700.00, 'Văn phòng', 'Còn trống', NULL),
(50, 23, 'Phòng 2502', 45.00, 750.00, 'Văn phòng', 'Còn trống', NULL);

-- Sample data for `bookings`
INSERT INTO `bookings` (booking_id, room_id, building_id, user_id, guest_name, identification_card, rental_price, lease_term, payment_term, lease_start_date, lease_end_date, photo_urls) VALUES
(1, 1, 1, 1, 'Nguyễn Văn A', '123456789', 500.00, '1 tháng', 'Trả trước', '2023-01-01', '2023-01-31', NULL),
(2, 2, 2, 2, 'Trần Thị B', '987654321', 600.00, '6 tháng', 'Trả hàng tháng', '2023-02-01', '2023-07-31', NULL),
(3, 3, 3, 3, 'Lê Văn C', '456123789', 700.00, '1 năm', 'Trả hàng năm', '2023-03-01', '2024-02-29', NULL),
(4, 4, 4, 4, 'Nguyễn Văn D', '321654987', 500.00, '3 tháng', 'Trả trước', '2023-04-01', '2023-06-30', NULL),
(5, 5, 5, 5, 'Trần Thị E', '654789321', 400.00, '1 tháng', 'Trả trước', '2023-05-01', '2023-05-31', NULL),
(6, 6, 1, 6, 'Nguyễn Văn F', '789456123', 450.00, '2 tháng', 'Trả hàng tháng', '2023-06-01', '2023-07-31', NULL),
(7, 7, 2, 7, 'Lê Thị G', '159753486', 600.00, '6 tháng', 'Trả hàng tháng', '2023-07-01', '2023-12-31', NULL),
(8, 8, 3, 10, 'Nguyễn Văn H', '753159486', 550.00, '1 năm', 'Trả hàng năm', '2023-08-01', '2024-07-31', NULL),
(9, 9, 4, 11, 'Trần Thị I', '951753486', 480.00, '3 tháng', 'Trả trước', '2023-09-01', '2023-11-30', NULL),
(10, 10, 5, 13, 'Lê Văn J', '159357486', 700.00, '1 tháng', 'Trả trước', '2023-10-01', '2023-10-31', NULL),
(11, 11, 1, 15, 'Nguyễn Văn K', '753951486', 800.00, '6 tháng', 'Trả hàng tháng', '2023-11-01', '2024-04-30', NULL),
(12, 12, 2, 18, 'Trần Thị L', '852147963', 900.00, '1 năm', 'Trả hàng năm', '2023-12-01', '2024-11-30', NULL),
(13, 13, 3, 20, 'Lê Văn M', '654321789', 750.00, '2 tháng', 'Trả hàng tháng', '2024-01-01', '2024-02-29', NULL),
(14, 14, 4, 25, 'Nguyễn Thị N', '987123654', 600.00, '1 tháng', 'Trả trước', '2024-02-01', '2024-02-29', NULL),
(15, 15, 5, 25, 'Trần Văn O', '321789456', 550.00, '3 tháng', 'Trả trước', '2024-03-01', '2024-05-31', NULL),
(16, 16, 1, 50, 'Lê Thị P', '654789123', 620.00, '6 tháng', 'Trả hàng tháng', '2024-04-01', '2024-09-30', NULL),
(17, 17, 2, 39, 'Nguyễn Văn Q', '456987123', 580.00, '1 năm', 'Trả hàng năm', '2024-05-01', '2025-04-30', NULL),
(18, 18, 3, 47, 'Trần Thị R', '789456321', 700.00, '2 tháng', 'Trả hàng tháng', '2024-06-01', '2024-07-31', NULL),
(19, 19, 4, 28, 'Lê Văn S', '321654789', 650.00, '1 tháng', 'Trả trước', '2024-07-01', '2024-07-31', NULL),
(20, 20, 5, 33, 'Nguyễn Thị T', '654321456', 720.00, '6 tháng', 'Trả hàng tháng', '2024-08-01', '2025-01-31', NULL),
(21, 21, 1, 35, 'Trần Văn U', '987654123', 500.00, '1 năm', 'Trả hàng năm', '2024-09-01', '2025-08-31', NULL),
(22, 22, 2, 36, 'Lê Thị V', '159753456', 600.00, '3 tháng', 'Trả trước', '2024-10-01', '2024-12-31', NULL),
(23, 23, 3, 39, 'Nguyễn Văn W', '753159123', 700.00, '1 tháng', 'Trả trước', '2024-11-01', '2024-11-30', NULL),
(24, 24, 4, 41, 'Trần Thị X', '951753123', 780.00, '6 tháng', 'Trả hàng tháng', '2024-12-01', '2025-05-31', NULL),
(25, 25, 5, 44, 'Lê Văn Y', '159357123', 850.00, '1 năm', 'Trả hàng năm', '2025-01-01', '2025-12-31', NULL);
-- Sample data for `management_income`
INSERT INTO `management_income` (`management_income_id`, `user_id`, `booking_id`, `commission`, `received_commission`, `actual_income`, `amount`) VALUES
(1, 2, 1, 60.00, 60.00, 540.00, 600.00),
(2, 3, 2, 80.00, 80.00, 720.00, 800.00),
(3, 4, 3, 70.00, 70.00, 630.00, 700.00),
(4, 5, 4, 90.00, 90.00, 810.00, 900.00),
(5, 6, 5, 75.00, 75.00, 675.00, 750.00),
(6, 7, 6, 95.00, 95.00, 855.00, 950.00),
(7, 8, 7, 65.00, 65.00, 585.00, 650.00),
(8, 9, 8, 85.00, 85.00, 765.00, 850.00),
(9, 10, 9, 90.00, 90.00, 810.00, 900.00),
(10, 11, 10, 100.00, 100.00, 850.00, 950.00);

-- Sample data for `sale_income`
INSERT INTO `sale_income` (`sale_income_id`, `user_id`, `booking_id`, `commission`, `received_commission`, `actual_income`, `amount`) VALUES
(1, 2, 1, 30.00, 30.00, 570.00, 600.00),
(2, 3, 2, 40.00, 40.00, 760.00, 800.00),
(3, 4, 3, 35.00, 35.00, 665.00, 700.00),
(4, 5, 4, 45.00, 45.00, 855.00, 900.00),
(5, 6, 5, 37.50, 37.50, 712.50, 750.00),
(6, 7, 6, 47.50, 47.50, 902.50, 950.00),
(7, 8, 7, 32.50, 32.50, 552.50, 650.00),
(8, 9, 8, 42.50, 42.50, 807.50, 850.00),
(9, 10, 9, 45.00, 45.00, 855.00, 900.00),
(10, 11, 10, 50.00, 50.00, 900.00, 950.00);