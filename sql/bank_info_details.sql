-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 07, 2023 at 01:17 PM
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
-- Table structure for table `bank_info_details`
--

CREATE TABLE `bank_info_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_info_id` int(11) NOT NULL,
  `label_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `value_name` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_info_details`
--

INSERT INTO `bank_info_details` (`id`, `bank_info_id`, `label_name`, `value_name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bank Account Name', 'Next Hop Co,.Ltd', '2023-02-02 05:15:44', '2023-02-02 05:27:39'),
(4, 1, 'Bank Name', 'CO-OPERATIVE BANK LTD (CB Bank)', '2023-02-02 05:28:10', '2023-02-02 05:28:10'),
(5, 1, 'SWIFT Code', 'CPOBMMMY', '2023-02-02 05:28:24', '2023-02-02 05:28:24'),
(6, 1, 'Bank Account No. USD ($)', '0045101200001872', '2023-02-02 05:28:42', '2023-02-02 05:29:31'),
(7, 1, 'Branch Address', 'No.(191), Botahtaung Pagoda Street(Middle block),Pazundaung, Yangon, Myanmar.', '2023-02-02 05:29:04', '2023-02-02 05:29:04'),
(8, 2, 'Bank Account Name', 'Next Hop Co,.Ltd', '2023-02-02 05:31:12', '2023-02-02 05:31:12'),
(9, 2, 'Bank Name', 'CO-OPERATIVE BANK LTD (CB Bank)', '2023-02-02 05:31:26', '2023-02-02 05:31:26'),
(10, 2, 'Bank Account No: MMK (Ks)', '0086600300001031', '2023-02-02 05:31:41', '2023-02-02 05:31:41'),
(11, 2, 'Branch Address', 'Building(371), Room(302), Yazardrit Housing, Botahtaung Township, Yangon, Myanmar.', '2023-02-02 05:31:58', '2023-02-02 05:31:58'),
(12, 3, 'Bank Account Name', 'NEXT HOP IT SERVICE PROVIDEER PTE. LTD', '2023-02-02 05:32:38', '2023-02-02 05:32:38'),
(13, 3, 'Bank Name', 'OCBC Bank', '2023-02-02 05:32:52', '2023-02-02 05:32:52'),
(14, 3, 'Bank Account No SGD', '595156100001', '2023-02-02 05:33:04', '2023-02-02 05:33:04'),
(15, 3, 'Bank Account No Multi-Currency', '601556012201', '2023-02-02 05:33:18', '2023-02-02 05:33:18'),
(16, 3, 'SWIFT Code', 'OCBCSGSG or OCVCSGSGXXX', '2023-02-02 05:33:34', '2023-02-02 05:33:34'),
(17, 3, 'Bank Code', 'OCBC - code assigned to OVERSEA-CHINESE BAKING CORPORATION LIMITED', '2023-02-02 05:33:46', '2023-02-02 05:33:46'),
(18, 3, 'Country', 'SG - Singapore', '2023-02-02 05:34:00', '2023-02-02 05:34:00'),
(19, 3, 'Bank Address', 'Chulia Street, 63 OCBC Center Floor 10', '2023-02-02 05:34:26', '2023-02-02 05:34:26'),
(20, 4, 'Bank Account Name', 'THE NEXT HOP Co.,LTD', '2023-02-02 05:35:10', '2023-02-02 05:35:10'),
(21, 4, 'Bank Account No', '146-1-73628-5', '2023-02-02 05:35:24', '2023-02-02 05:35:24'),
(22, 4, 'Bank Name', 'Kasikorn Bank', '2023-02-02 05:35:37', '2023-02-02 05:35:37'),
(23, 4, 'SWIFT Code', 'KASITHBKXXX', '2023-02-02 05:35:50', '2023-02-02 05:35:50'),
(24, 4, 'Country', 'Thailand', '2023-02-02 05:36:00', '2023-02-02 05:36:00'),
(25, 4, 'Bank Address', 'Room No.G-34-36 Ground Floor, Siam Paragon Shopping Center Building, 991 Rama I Road Khwang Pathum Wan, Khet Pathum Wan, Bangkok 10330.', '2023-02-02 05:36:15', '2023-02-02 05:36:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_info_details`
--
ALTER TABLE `bank_info_details`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_info_details`
--
ALTER TABLE `bank_info_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
