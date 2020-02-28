-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 13, 2019 at 10:20 PM
-- Server version: 10.3.17-MariaDB-0+deb10u1
-- PHP Version: 7.3.11-1~deb10u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bulletinBoard`
--
CREATE DATABASE IF NOT EXISTS `bulletinBoard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `bulletinBoard`;

-- --------------------------------------------------------

--
-- Table structure for table `bulletin`
--

CREATE TABLE `bulletin` (
  `id` int(11) NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `type` int(11) NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bulletin`
--

INSERT INTO `bulletin` (`id`, `title`, `content`, `type`, `datetime`) VALUES
(64, '開放報名中「彩色平權之路─性別平權的甘苦談」性別教育工作坊', '1http://signup.nutn.edu.tw/2019/10808282345', 1, '2019-12-13 22:12:46'),
(66, '宣導未成年懷孕諮詢服務摺頁資源', '1http://www.aydinescort3.com/', 2, '2019-12-13 22:12:20'),
(67, '賀！本校通過「107年度公私立大專校院推動學生輔導工作評鑑（含實地訪視）」', '3賀！本校通過「107年度公私立大專校院推動學生輔導工作評鑑（含實地訪視）」\r\n\r\n公告單位：輔導中心\r\n\r\n公告日期：2019/05/13\r\n\r\n相關連結：http://www.heeact.edu.tw/mp.asp?mp=2\r\n\r\n公告內容：107年度公私立大專校院推動學生輔導工作評鑑（含實地訪視）結果業已公告於財團法人高等教育評鑑中心基金會網站。（路徑：學務特教司書審>書審結果）\r\n\r\nhttp://www.nutn.edu.tw/information_details.html?boardno=68563', 5, '2019-12-13 22:12:10'),
(68, '輔導中心活動報名需知', '2輔導中心活動報名需知.png', 1, '2019-12-13 22:12:26');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`) VALUES
('admin', 'Hashed Password.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bulletin`
--
ALTER TABLE `bulletin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bulletin`
--
ALTER TABLE `bulletin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

