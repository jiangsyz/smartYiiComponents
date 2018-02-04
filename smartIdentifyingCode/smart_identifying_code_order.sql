-- phpMyAdmin SQL Dump
-- version 4.3.13.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2018-02-04 23:41:52
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
-- 表的结构 `smart_identifying_code_order`
--

CREATE TABLE IF NOT EXISTS `smart_identifying_code_order` (
  `id` int(10) NOT NULL COMMENT '主键',
  `identifyingCode` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '验证码',
  `type` int(10) NOT NULL COMMENT '验证码订单类型',
  `data` varchar(500) COLLATE utf8_bin NOT NULL COMMENT '保留域数据',
  `createTime` int(10) NOT NULL COMMENT '创建时间戳',
  `state` int(10) NOT NULL COMMENT '0=待验证/1=验证成功'
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='验证码校验';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `smart_identifying_code_order`
--
ALTER TABLE `smart_identifying_code_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `smart_identifying_code_order`
--
ALTER TABLE `smart_identifying_code_order`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键',AUTO_INCREMENT=77;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
