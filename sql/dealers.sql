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
-- Table structure for table `dealers`
--

CREATE TABLE `dealers` (
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
-- Dumping data for table `dealers`
--

INSERT INTO `dealers` (`id`, `type`, `name`, `position`, `phone`, `phone_other`, `company`, `email`, `address`, `action`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ma Phyu Phyu', 'Manager', '095044688', '', 'iDEALINK', 'idealinkcomputer@gmail.com', 'No.(182), Seikkhan Thar St(Middle Block), Kyauktada Tsp, Yangon.\r\nPh : 01-376212, 01-372185,09-73026840, 0945609881,8882,8883', 0, NULL, NULL),
(2, 1, 'U Khun Oo', 'Managing Director', '095001096', '', 'Computer Technical Team (Trading) Co., Ltd', 'cttcomputer@gmail.com', '11/13, G Flr, 45th St., Lower Middle Block, Botahtaung tsp,  Yangon, Myanmar.\r\nPh : 01-200710, 01-294436', 1, NULL, NULL),
(3, 1, 'Kaung Phoo Mon /Ms', '', '09964440531', '', 'Loi Heng International ( Thein Han & Khine Myae Co.,Ltd )', 'kaungphoo@loiheng.com', ' 272, Seikkanthar Upper Street, Kyauktada Township.\r\n(01) 246 165 , (09) 96 444 0531\r\n(09) 96 444 0535 , (09) 96 444 0536', 1, NULL, NULL),
(4, 1, 'U Kyaw Kyaw Cho Latt', 'Project Manager', '09255111417', '09420113102', 'Shwe Taung Development Co.,Ltd.', 'kyawkclatt@shwetaungrealestate.com', 'Alone Tower, Lower Kyi Myint Tine Road.', 0, NULL, NULL),
(5, 1, 'U Kyaw Kyaw Wai', 'Managing Director', '09420301888', '09254019839', ' Shwe Yee Htate Tan', 'kyawkyawwai03@gmail.com', 'Bld 16, Room 12, Uwisara Housing Dagon Township, Yangon.', 1, NULL, NULL),
(6, 1, 'Kyaw Kyaw Cho Latt', 'Project Manager', '09255111417', '09420113102', 'Shwe Taung Development Co.,Ltd.', 'kyawkclatt@shwetaungrealestate.com', 'Alone Tower, Lower Kyi Myint Tine Road, Yangon, Myanmar', 1, NULL, NULL),
(7, 1, 'Hsan Naing', '', '09452122774', '', 'Green IT', 'sales@greenitmm.com', 'No.1 , 3rd Floor , Corner of Lower Kyi Myint Dine Road and\r\nU Lu Maung Street , Kyi Myit Dine Tsp , Yangon\r\nwww.greenitmm.com\r\nEmail : sales@greenitmm.com , info@greenitmm.com\r\nTel : 09 896237358 ,09 896237359', 1, NULL, NULL),
(8, 1, 'Ko Kyaw Htin', 'Enterprise', '09441660255', '', 'Synnex Myanmar Co.,Ltd', 'kyawhtin@synnexmyanmar.com', 'Synnex Myanmar Co.,Ltd', 1, NULL, NULL),
(9, 1, 'Myo Tun', 'General Manager', '09420014401', '', 'True IDC (Myanmar) Co.,Ltd', 'Myo.tun@ascendcorp.com', 'Building 17, Ground Floor, MICT Park.,\r\nHlaing University Campus, Hlaing Tsp, Yangon, Myanmar.', 1, NULL, NULL),
(10, 1, 'Tinzar Linnn (Ms)', 'Senior Manager', '09420706895', '', 'KMD Co.,Ltd', 'tinzarlin@kmdcomputer.com', 'No 174-182, Pansodan Road (Middle Block), Kyauktada Township, Yangon, Myanmar.', 1, NULL, NULL),
(11, 1, 'Ma Phyu Phyu', 'Manager', '095044688', '01376212', 'Idea Link IT & Mobile Center', 'idealinkcomputer@gmail.com', 'No.182, Seikkanthar St., (Middle Block), Kyauktada Township, Yangon.\r\nPh: 01372185, 01376212, 09-73026840, 09-785044688', 1, NULL, NULL),
(12, 1, 'Synnex Myanmar Company Limited', '', '091079430', '', 'Synnex Myanmar Company Limited', 'nanayeayethin@synnexmyanmar.com', 'No.89, West Shwe Gone Daing Road, Corner of Link Road , Bahan Township, \nOffice    : +95 97 3040604, +95 97 91079410, +95 97 91079420, +95 97 91079430', 1, NULL, NULL),
(13, 1, 'PRO SYSTEM & NETWORKING', '', '09954556550', '', 'PRO SYSTEM & NETWORKING COMPANY LIMITED', 'sales@proitmyanmar.com', 'No.159/B, Ground Floor, 47th Street Upper Block, Botahtaung Township., YANGON, 11161 MM', 1, NULL, NULL),
(14, 1, 'Mr. Wade Chan', 'Country Manager (Myanmar)', '09778669888', '', 'Sangfor Technologies (Hong Kong) Ltd.', 'wade.chan@sangfor.com', 'No.317-A, Ingyinmyain 4th Parkway, Thuwanna VIP-1, Thinggangyun Township, Yangon, Myanmar.', 1, NULL, NULL),
(15, 1, 'Ko Aung Ye Paing', '', '095182731', '', 'Noble IT Solutions Co., Ltd.', 'aungyepaing@nobleitsoln.com', 'No. Duplex/3, Thu Mingalar Housing , Thu Nan Dar (1) Street , Thingangyun Tsp,Yangon.', 1, NULL, NULL),
(16, 1, 'Ma Khaing Tinzar Kyaw', '', '09966331533', '', 'Pacific Tech Pte Ltd (Myanmar)', 'k.tinzarkyaw@pacifictech.com.sg', 'No. 3791 Jalan Bukit Merah #05-16/17/18 E-Centre @ Redhill Singapore 159471\r\nTel: 65-6916 1800 Fax: 65-6916 1829', 1, NULL, NULL),
(17, 1, 'Ma Lae Yin Myint', '', '09441114422', '', 'Dell Concept Store', 'dellconceptstore@citicom.com.mm', 'No.166, Seikkanthar Street (Middle Block), Kyauktada Tsp, Yangon, Myanmar.\r\nTel:09‐441114411, 09‐441114422', 1, NULL, NULL),
(18, 1, 'Ko Aung Zayar Tun', 'Sales Manager', '09250024848', '', 'AGB COMMUNICATION', 'aungzayartun@agbcommunication.com', 'Room 1903/1905/1906, 19th Floor, Office Tower 3, Time City, Kamaryut Township, Yangon, Myanmar.\r\nPh: +95 09 977878889', 1, NULL, NULL),
(19, 1, 'Mr.Yar Zar Phyo', 'CEO', '95033257', '', 'Next Hop Co.,Ltd', 'yzphyo@gmail.com', 'Next Hop Co.,Ltd', 1, NULL, NULL),
(20, 1, 'Nay Chi', 'BDM', '09256479058', '', '2L Industrial Co., Ltd', 'ethan.nay@sisott.com', 'No.53, Ward 5, Thar Yar Gone Shan Ywar St, Mayangone Township,Yangon,Myanmar.', 1, NULL, NULL),
(21, 1, 'NETSTAR TECHNOLOGY COMPANY LIMITED', '', '09795868140', '', 'NETSTAR TECHNOLOGY COMPANY LIMITED', '', 'No.177, Seikanthar Street, Kyauktada Township, Yangon.\r\nMobile: 0943041413, 09941247075', 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dealers`
--
ALTER TABLE `dealers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dealers`
--
ALTER TABLE `dealers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
