-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 03, 2025 at 05:20 PM
-- Server version: 10.6.22-MariaDB-cll-lve-log
-- PHP Version: 8.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jotahcom_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE `assets` (
  `asset_id` int(11) NOT NULL,
  `asset_name` text NOT NULL,
  `asset_description` text DEFAULT NULL,
  `serial_number` varchar(255) NOT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `status` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `assets`
--

INSERT INTO `assets` (`asset_id`, `asset_name`, `asset_description`, `serial_number`, `cost`, `status`, `location`, `image_url`, `created_at`) VALUES
(1, 'Bitcoin', 'Digital currency asset', 'BTC-001', 25000.00, 'In Use', 'Digital Wallet', 'https://placehold.co/100x100?text=Bitcoin', '2025-09-03 12:43:48'),
(2, 'Ethereum', 'Smart contract platform asset', 'ETH-001', 1800.00, 'In Use', 'Digital Wallet', 'https://placehold.co/100x100?text=Ethereum', '2025-09-03 12:43:48'),
(3, 'Dogecoin', 'Meme-based digital currency asset', 'DOGE-001', 0.08, 'In Use', 'Digital Wallet', 'https://placehold.co/100x100?text=Dogecoin', '2025-09-03 12:43:48'),
(4, 'Gold Coin', 'Physical gold bullion coin', 'GC-2022-045', 2000.00, 'In Storage', 'Vault', 'https://placehold.co/100x100?text=Gold', '2025-09-03 12:43:48'),
(5, 'Silver Coin', 'Physical silver bullion coin', 'SC-2021-088', 25.00, 'In Storage', 'Vault', 'https://placehold.co/100x100?text=Silver', '2025-09-03 12:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `phone_number`, `password_hash`, `created_at`, `otp_code`, `otp_expires_at`) VALUES
(1, 'Jamachi Mauricennadi', 'jmaurice', 'jmauricennadi@gmail.com', '09024428551', '$2y$10$/dtmmBrqrinLp1XjZvZ7s.texRTb.RkGv3DGF4rdbyZT6gGiN19.q', '2025-09-01 15:43:46', NULL, NULL),
(2, 'Jamachi Mauricennadi', 'mark', 'jmauricennadi@me.com', '09024428551', '$2y$10$jHFJzIi0q9TmDYAsZ0aDW.QphuJfzax.YYAPx.RyjtQsoHvr/Xyhe', '2025-09-01 15:50:37', NULL, NULL),
(3, 'Jamachi Mauricennadi', 'BerryLee', 'ceezamark@gmail.com', '09024428551', '$2y$10$jTL3RxGi6H850LHWwdFoNeJlrMNAC3xltSMXpmC.E43KQrB3XAR.e', '2025-09-01 15:57:46', '789835', '2025-09-02 21:24:37'),
(4, 'Jamachi Mauricennadi', 'mauricennadi', 'jmauricennadi@maurice.com', '09024428551', '$2y$10$6XY6YxXuqbESKOmyMI3nu.VeFoPwG4fyMLVm06TBbjAgnUNtMqSL2', '2025-09-02 11:46:53', NULL, NULL),
(5, 'Kigerly Butam', 'getmin', 'obewanti@proton.me', '08107636902', '$2y$10$0Qao1qQhcwVgeSnNzcUCGu5hcR8ywvkI0igJ.npaHwPLL/8eQJ99a', '2025-09-03 11:07:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_assets`
--

CREATE TABLE `user_assets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `asset_name` varchar(255) NOT NULL,
  `asset_symbol` varchar(10) NOT NULL,
  `asset_amount` decimal(18,8) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `website_content`
--

CREATE TABLE `website_content` (
  `content_key` varchar(255) NOT NULL,
  `content_value` text NOT NULL,
  `section` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `website_content`
--

INSERT INTO `website_content` (`content_key`, `content_value`, `section`) VALUES
('copyright_text', '© 2024 WorldLiberty Financial, Inc. All Rights Reserved.', 'footer'),
('hero_button', 'Inspired by Donald J. Trump', 'hero'),
('hero_subtitle', 'Be DeFiant', 'hero'),
('hero_text', 'We\'re leading a financial revolution by dismantling the stranglehold of traditional financial institutions and putting the power back where it belongs: in your hands.', 'hero'),
('hero_title', 'Shape a New Era of Finance', 'hero'),
('privacy_policy_link', 'Privacy Policy', 'footer'),
('site_name', 'World Liberty Financial', 'header'),
('trump_disclaimer_1', 'None of Donald J. Trump, any of his family members or any director, officer or employee of the Trump Organization, DT Marks DEFI LLC or any of their respective affiliates is an officer, director, founder, or employee of World Liberty Financial or its affiliates. None of World Liberty Financial, Inc., its affiliates or the World Liberty Financial platform is owned, managed, or operated, by Donald J. Trump, any of his family members, the Trump Organization, DT Marks DEFI LLC or any of their respective directors, officers, employees, affiliates, or principals. $WLFI tokens and use of the World Liberty Financial platform are offered and sold solely by World Liberty Financial or its affiliates. DT Marks DeFi, LLC and its affiliates, including Donald J. Trump has or may receive approximately 22.5 billion tokens from World Liberty Financial, and will be entitled to receive significant fees for services provided to World Liberty Financial, which amount cannot yet be determined. World Liberty Financial and $WLFI are not political and not part of any political campaign.', 'body'),
('uk_residency_disclaimer', 'If you are resident in the UK, you acknowledge that this information is only intended to be available to persons who meet the requirements of qualified investors (i) who have professional experience in matters relating to investments and who fall within the definition of “investment professional” in Article 19(5) of the Financial Services and Markets Act 2000 (Financial Promotion) Order 2005, as amended (the “Order”); or (ii) who are high net worth entities, unincorporated associations or partnerships falling within Article 49(2) of the Order; or (iii) any other persons to whom this information may lawfully be communicated under the Order. Persons who do not fall within these categories should not act or rely on the information contained herein.', 'footer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assets`
--
ALTER TABLE `assets`
  ADD PRIMARY KEY (`asset_id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_assets`
--
ALTER TABLE `user_assets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `website_content`
--
ALTER TABLE `website_content`
  ADD PRIMARY KEY (`content_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assets`
--
ALTER TABLE `assets`
  MODIFY `asset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_assets`
--
ALTER TABLE `user_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_assets`
--
ALTER TABLE `user_assets`
  ADD CONSTRAINT `user_assets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
