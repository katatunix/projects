-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2015 at 01:30 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gereport`
--

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE IF NOT EXISTS `member` (
`id` int(11) NOT NULL,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'gameloft',
  `group` int(11) NOT NULL DEFAULT '2'
) ENGINE=MyISAM AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`id`, `username`, `password`, `group`) VALUES
(1, 'nghia.buivan', 'gameloft', 0),
(5, 'phat.phamhong', 'gameloft', 1),
(4, 'dung.nguyenthanh', 'gameloft', 1),
(6, 'vinh.dangdoduc', 'gameloft', 1),
(7, 'tuyen.lenhat', 'gameloft', 1),
(8, 'hai.nguyentai', 'gameloft', 1),
(9, 'dat.tangchi', 'gameloft', 1),
(10, 'hung.nguyenvo', 'gameloft', 1),
(11, 'thanh.buikhanh', 'gameloft', 1),
(12, 'thong.hoangtrung', 'gameloft', 1),
(13, 'hue.buiminh', 'gameloft', 2),
(14, 'tung.nguyenthanh5', 'gameloft', 2),
(15, 'khoa.nguyenanh2', 'gameloft', 2),
(16, 'minh.lamthoi', 'gameloft', 2),
(17, 'dung.dotrung', 'gameloft', 2),
(18, 'an.chiemduy', 'gameloft', 2),
(19, 'dan.honvi', 'gameloft', 2),
(20, 'long.duongxuan', 'gameloft', 2),
(21, 'trinh.lehong', 'gameloft', 2),
(22, 'trieu.nguyenlam', 'gameloft', 2),
(23, 'anh.dinhnhuvu', 'gameloft', 2),
(24, 'nhan.nguyenminh', 'gameloft', 2),
(25, 'an.vuongngocduy', 'gameloft', 2),
(26, 'hoa.dinhquoc', 'gameloft', 2),
(27, 'hai.nguyenhuynhthanh', 'gameloft', 2),
(28, 'dat.nguyenthanh3', 'gameloft', 2),
(29, 'phu.phamthanh', 'gameloft', 2),
(30, 'hong.levothanh', 'gameloft', 2),
(31, 'loc.phamxuan', 'gameloft', 2),
(32, 'long.nguyenthanh4', 'gameloft', 2),
(33, 'toan.tranchi', 'gameloft', 2),
(34, 'vi.nguyentu', 'gameloft', 2),
(35, 'quang.leminh', 'gameloft', 2),
(36, 'bao.nguyenthanhphuong', 'gameloft', 2),
(37, 'luan.votrong', 'gameloft', 2),
(38, 'chuong.lehoang', 'gameloft', 2),
(39, 'thanh.lyngoc', 'gameloft', 0);

-- --------------------------------------------------------

--
-- Table structure for table `memberproject`
--

CREATE TABLE IF NOT EXISTS `memberproject` (
  `memberId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `memberproject`
--

INSERT INTO `memberproject` (`memberId`, `projectId`) VALUES
(1, 1),
(4, 3),
(5, 2),
(6, 1),
(7, 4),
(8, 5),
(9, 4),
(10, 1),
(11, 3),
(12, 2),
(13, 3),
(14, 3),
(15, 3),
(16, 3),
(17, 2),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 2),
(32, 2),
(33, 2),
(34, 2),
(35, 5),
(36, 5),
(37, 5),
(38, 5);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
`id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id`, `name`) VALUES
(1, 'Asphalt 8'),
(2, 'Modern Combat 5'),
(3, 'UNO & Friends'),
(4, 'Order & Chaos'),
(5, 'Dungeon Hunter 5');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE IF NOT EXISTS `report` (
`id` int(11) NOT NULL,
  `memberId` int(11) NOT NULL,
  `projectId` int(11) NOT NULL,
  `dateFor` date NOT NULL,
  `datetimeAdd` datetime NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`id`, `memberId`, `projectId`, `dateFor`, `datetimeAdd`, `content`) VALUES
(4, 1, 1, '2015-02-13', '2015-02-13 13:19:52', 'Bug fixed: 1234, 5678 (example)\r\nBug fixed: 1234, 5678 (example)\r\nBug fixed: 1234, 5678 (example)');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `member`
--
ALTER TABLE `member`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `memberproject`
--
ALTER TABLE `memberproject`
 ADD PRIMARY KEY (`memberId`,`projectId`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
