-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 10, 2015 at 10:40 AM
-- Server version: 5.6.19-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `urbandecay`
--

-- --------------------------------------------------------

--
-- Table structure for table `same_getlog`
--

CREATE TABLE IF NOT EXISTS `same_getlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `msgtype` varchar(20) DEFAULT NULL,
  `event` varchar(20) DEFAULT NULL,
  `eventkey` varchar(50) DEFAULT NULL,
  `createtim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `timeint` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `timeint` (`timeint`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `same_qrcode`
--

CREATE TABLE IF NOT EXISTS `same_qrcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bak` varchar(50) NOT NULL,
  `scene_id` int(11) NOT NULL,
  `ticket` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `image` varchar(255) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='二维码' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `same_store`
--

CREATE TABLE IF NOT EXISTS `same_store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `telphone` varchar(50) NOT NULL,
  `open` varchar(255) NOT NULL,
  `lat` double NOT NULL COMMENT '经度',
  `lng` double NOT NULL COMMENT '纬度',
  `picUrl` varchar(255) NOT NULL,
  `mapUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lat` (`lat`,`lng`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='门店信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `same_sys_menu`
--

CREATE TABLE IF NOT EXISTS `same_sys_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `url` varchar(100) NOT NULL,
  `sort` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统菜单' AUTO_INCREMENT=59 ;

--
-- Dumping data for table `same_sys_menu`
--

INSERT INTO `same_sys_menu` (`id`, `pid`, `name`, `url`, `sort`, `uid`, `uname`, `createtime`) VALUES
(1, '', '后台管理系统', '', 1, 0, '', '2013-04-25 17:53:43'),
(58, '54', '门店管理', 'wmenu/store', 20, 1, 'admin', '2015-02-04 08:18:16'),
(52, '0', '系统管理', '', 1000, 0, '', '2014-07-03 00:54:57'),
(53, '52', '系统菜单管理', 'menu', 1000, 0, '', '2014-07-03 00:55:32'),
(54, '1', '微信管理', '', 20, 1, 'trioadmin', '2014-07-03 00:57:27'),
(55, '54', '微信菜单列表', 'wmenu/index', 5, 1, 'trioadmin', '2014-07-03 00:58:31'),
(56, '54', '生成微信菜单', 'wmenu/create', 10, 1, 'trioadmin', '2014-07-03 00:58:50'),
(57, '54', '微信事件列表', 'wmenu/event', 15, 1, 'trioadmin', '2014-07-03 01:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `same_sys_permissions`
--

CREATE TABLE IF NOT EXISTS `same_sys_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rname` varchar(20) NOT NULL,
  `role` text NOT NULL,
  `uid` int(11) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限控制表' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `same_sys_permissions`
--

INSERT INTO `same_sys_permissions` (`id`, `rname`, `role`, `uid`, `uname`, `createtime`) VALUES
(1, '普通用户', '0,14,15,1,12', 0, '', '2013-04-24 10:49:38'),
(2, '管理员', '0,14,15,1,11,13,12,2', 0, '', '2013-04-24 17:03:21'),
(3, '超级管理员', '0,1,31,32,48', 1, 'test', '2013-04-25 09:08:29');

-- --------------------------------------------------------

--
-- Table structure for table `same_sys_user`
--

CREATE TABLE IF NOT EXISTS `same_sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `rname` varchar(10) NOT NULL COMMENT '真实名字',
  `did` int(11) NOT NULL COMMENT '部门id',
  `dname` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL COMMENT '权限id',
  `pname` varchar(50) NOT NULL,
  `status` enum('激活','锁定') NOT NULL DEFAULT '激活' COMMENT '用户状态，1激活，0锁定',
  `ouid` int(11) NOT NULL,
  `ouname` varchar(20) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uname` (`uname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系统用户表' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `same_sys_user`
--

INSERT INTO `same_sys_user` (`id`, `uname`, `password`, `rname`, `did`, `dname`, `pid`, `pname`, `status`, `ouid`, `ouname`, `createtime`) VALUES
(1, 'admin', '0b4e7a0e5fe84ad35fb5f95b9ceeac79', 'admin', 2, '管理部', 3, '超级管理员', '激活', 1, 'test', '2015-06-21 03:18:10');

-- --------------------------------------------------------

--
-- Table structure for table `same_sys_user_login_log`
--

CREATE TABLE IF NOT EXISTS `same_sys_user_login_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uname` varchar(20) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台管理用户登录日志' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `same_sys_user_login_log`
--

INSERT INTO `same_sys_user_login_log` (`id`, `uid`, `uname`, `ip`, `createtime`) VALUES
(1, 1, 'admin', '101.81.28.152', '2015-07-10 02:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `same_wmenu`
--

CREATE TABLE IF NOT EXISTS `same_wmenu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `event` varchar(20) NOT NULL,
  `eventkey` varchar(50) NOT NULL,
  `eventurl` varchar(255) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信菜单' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `same_wmenu_event`
--

CREATE TABLE IF NOT EXISTS `same_wmenu_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `event` varchar(20) NOT NULL,
  `msgtype` varchar(20) NOT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `mohu` int(11) NOT NULL,
  `content` longtext NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `picUrl` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `scenelog`
--

CREATE TABLE IF NOT EXISTS `scenelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timeint` int(11) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL COMMENT '1为关注，2为扫描',
  `ticket` varchar(255) NOT NULL,
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='扫描二维码' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
