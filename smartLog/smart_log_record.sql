-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-02-05 07:29:46
-- 服务器版本： 5.7.17
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_store_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `smart_log_record`
--

CREATE TABLE IF NOT EXISTS `smart_log_record` (
  `id` int(10) NOT NULL COMMENT '主键',
  `runningId` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '本次php脚本运行的唯一编号',
  `logType` int(10) NOT NULL COMMENT '日志类型',
  `data` text COLLATE utf8_bin NOT NULL COMMENT '日志内容',
  `time` int(10) NOT NULL COMMENT '日志记录时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='smartLog日志';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `smart_log_record`
--
ALTER TABLE `smart_log_record`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `smart_log_record`
--
ALTER TABLE `smart_log_record`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;