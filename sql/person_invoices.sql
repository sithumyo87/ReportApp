-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 28, 2023 at 11:19 AM
-- Server version: 5.7.40
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `appthenexthop_reportapp`
--

-- --------------------------------------------------------

--
-- Table structure for table `person_invoices`
--

CREATE TABLE `person_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_other` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `person_invoices`
--

INSERT INTO `person_invoices` (`id`, `type`, `name`, `position`, `phone`, `phone_other`, `company`, `email`, `address`, `action`, `created_at`, `updated_at`) VALUES
(1, 1, 'Finance Manager', '', ' 951 661814', '', 'PTTEP INTERNATIONAL LIMITED (YANGON BRANCH OFFICE)', 'http://www.pttep.com', '2 Sei-Myaung Yeiktha Lane 8 ½ Mile Mayangone Township Yangon ,The Republic of The Union of Myanmar', 1, NULL, NULL),
(2, 1, 'tester', '', '09775443062', '', 'test', '', 'tst', 0, NULL, NULL),
(3, 1, 'Catherine', '', '09406661566', '', 'Four Paws International Myanmar', 'catherine.sanei@four-paws.org', 'Four Paws International Myanmar', 1, NULL, NULL),
(4, 1, 'IT Administrative Team', '', '01255188', '', 'Agoda International Myanmar Limited', 'IT-Admin@agoda.com', 'Agoda International Myanmar Limited\nNo. 1207, 12th  Floor, Sakura Tower, No( 339) Bogyoke Aung San Road, Kyauktada Township, Yangon, Myanmar.\nPhone : (+95 1 255 188/189)', 1, NULL, NULL),
(5, 1, 'Ms.R.Vanishree', 'Internation Business Operation', '0060388007000', '', 'BESTINET SDN. BHD. (838886-T) ', 'vanishree.krishnan@bestinet.com.my', 'Blok 5, Corporate Park,Star Central, Lingkaran Cyber Point Timur, Cyber 12\' 63000,cyberjaya Malaysia.', 0, NULL, NULL),
(6, 1, 'Bestinet Sdn Bhd', '', '0060388007000', '', 'Bestinet Sdn Bhd', 'zuriana.sulaiman@bestinet.com.my', 'Blok 5, Corporate Park,Star Central, Lingkaran Cyber Point Timur, Cyber 12\' 63000,cyberjaya Malaysia.', 1, NULL, NULL),
(7, 1, 'Finance Manager', '', '951661814', '', 'ANDAMAN TRANSPORTATION LIMITED (YANGON BRANCH OFFICE)', 'ZOC.Plan-Sup@pttep.com', '2 Sei-Myaung Yeiktha Lane, 8 ½ Mile Mayangone Township, Yangon ,The Republic of The Union of Myanmar.\nTel : (951) 661814,652700-4\nFax : (951) 667783', 1, NULL, NULL),
(8, 1, 'Accounting Services', 'Procurement Administration Officer', '00000000', '', 'British Council', 'MMProcurement@britishcouncil.org', '78 Kanna Road, Kyauktada Township 11181, Yangon, MYANMAR', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `person_invoices`
--
ALTER TABLE `person_invoices`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `person_invoices`
--
ALTER TABLE `person_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
