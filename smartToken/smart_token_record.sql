-- phpMyAdmin SQL Dump
-- version 4.3.13.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-02-04 23:45:31
-- 服务器版本： 5.5.58-log
-- PHP Version: 5.6.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `smart_store`
--

-- --------------------------------------------------------

--
-- 表的结构 `smart_token_record`
--

CREATE TABLE IF NOT EXISTS `smart_token_record` (
  `id` int(10) NOT NULL COMMENT '主键',
  `type` int(10) NOT NULL COMMENT '令牌所对应的资源类型',
  `token` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '令牌',
  `data` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '保留域数据',
  `createTime` int(10) NOT NULL COMMENT '创建令牌时间戳',
  `isTimeOut` int(10) NOT NULL COMMENT '是否超时(0=未超时/1=超时)'
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='令牌';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `smart_token_record`
--
ALTER TABLE `smart_token_record`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `smart_token_record`
--
ALTER TABLE `smart_token_record`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',AUTO_INCREMENT=53;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
