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
-- Table structure for table `currencies`
--

CREATE TABLE `currencies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `Currency_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `UnitPrice` int(11) DEFAULT NULL,
  `symbol` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` longtext COLLATE utf8mb4_unicode_ci,
  `detail_print` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `Currency_name`, `UnitPrice`, `symbol`, `detail`, `detail_print`, `created_at`, `updated_at`) VALUES
(1, 'USD', 0, '$', '<h6 class=\"bank-info-title\">Bank Info ( For USD )</h6>\n                    <table class=\"bank-info-table\">\n                        <tbody>\n                            <tr>\n                                <td>Bank Account Name</td>\n                                <td>:</td>\n                                <td>Next Hop Co,.Ltd</td>\n                            <tr>\n                            <tr>\n                                <td>Bank Name</td>\n                                <td>:</td>\n                                <td>CO-OPERATIVE BANK LTD (CB Bank)</td>\n                            <tr>\n                            <tr>\n                                <td>SWIFT Code</td>\n                                <td>:</td>\n                                <td>CPOBMMMY</td>\n                            <tr>\n                            <tr>\n                                <td>Bank Account No: USD ($)</td>\n                                <td>:</td>\n                                <td>0045101200001872</td>\n                            <tr>\n                            <tr>\n                                <td>Branch Address</td>\n                                <td>:</td>\n                                <td>No.(191), Botahtaung Pagoda Street(Middle block),Pazundaung, Yangon, Myanmar.</td>\n                            <tr>\n                        </tbody>\n                    </table>', NULL, '2023-02-01 03:45:38', '2023-02-01 03:45:38'),
(2, 'MMK', 0, 'Ks', '<h6 class=\"bank-info-title\">MMK Bank Info</h6>\n                    <table class=\"bank-info-table\">\n                        <tbody>\n                            <tr>\n                                <td>Bank Account Name</td>\n                                <td>:</td>\n                                <td>Next Hop Co,.Ltd</td>\n                            <tr>\n                            <tr>\n                                <td>Bank Name</td>\n                                <td>:</td>\n                                <td>CO-OPERATIVE BANK LTD (CB Bank)</td>\n                            <tr>\n                            <tr>\n                                <td>Bank Account No: MMK (Ks)</td>\n                                <td>:</td>\n                                <td>0086600300001031</td>\n                            <tr>\n                            <tr>\n                                <td>Branch Address</td>\n                                <td>:</td>\n                                <td>Building(371), Room(302), Yazardrit Housing, Botahtaung Township, Yangon, Myanmar.</td>\n                            <tr>\n                        </tbody>\n                    </table>', NULL, '2023-02-01 03:45:38', '2023-02-21 15:28:21'),
(3, 'SGD', NULL, 'S$', NULL, NULL, '2023-02-02 04:05:58', '2023-02-02 04:05:58'),
(4, 'THB', NULL, '฿', NULL, NULL, '2023-02-02 04:06:18', '2023-02-02 04:06:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
