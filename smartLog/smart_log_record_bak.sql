-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 120.132.20.205
-- Generation Time: 2018-07-19 15:59:53
-- 服务器版本： 5.5.58-log
-- PHP Version: 5.6.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `smart_store_log`
--

-- --------------------------------------------------------

--
-- 表的结构 `smart_log_record_bak`
--

CREATE TABLE `smart_log_record_bak` (
  `id` int(10) NOT NULL COMMENT '主键',
  `runningId` varchar(200) COLLATE utf8_bin NOT NULL COMMENT '本次php脚本运行的唯一编号',
  `controllerId` varchar(200) COLLATE utf8_bin NOT NULL COMMENT 'controllerId',
  `actionId` varchar(200) COLLATE utf8_bin NOT NULL COMMENT 'actionId',
  `logType` int(10) NOT NULL COMMENT '日志类型',
  `data` text COLLATE utf8_bin NOT NULL COMMENT '日志内容',
  `time` int(10) NOT NULL COMMENT '日志记录时间戳'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='smartLog日志副表';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `smart_log_record_bak`
--
ALTER TABLE `smart_log_record_bak`
  ADD PRIMARY KEY (`id`),
  ADD KEY `runningId` (`runningId`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `smart_log_record_bak`
--
ALTER TABLE `smart_log_record_bak`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主键';
COMMIT;
