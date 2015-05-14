/*
 Navicat MySQL Data Transfer

 Source Server         : workspace
 Source Server Version : 50615
 Source Host           : localhost
 Source Database       : boshang

 Target Server Version : 50615
 File Encoding         : utf-8

 Date: 05/14/2015 13:22:32 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `activity`
-- ----------------------------
DROP TABLE IF EXISTS `activity`;
CREATE TABLE `activity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `time` int(5) NOT NULL COMMENT '持续时间',
  `click` int(5) unsigned NOT NULL DEFAULT '0' COMMENT '点击次数',
  `top` int(1) NOT NULL DEFAULT '0' COMMENT '0-置顶;1-不置顶',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='贵宾优享项目列表';

-- ----------------------------
--  Table structure for `activityOrder`
-- ----------------------------
DROP TABLE IF EXISTS `activityOrder`;
CREATE TABLE `activityOrder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `verify` int(1) NOT NULL DEFAULT '0',
  `sum` decimal(10,2) NOT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `method` varchar(20) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  KEY `uid` (`uid`),
  CONSTRAINT `order_activity` FOREIGN KEY (`aid`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_activity_user` FOREIGN KEY (`uid`) REFERENCES `frontUser` (`front_uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `activityPic`
-- ----------------------------
DROP TABLE IF EXISTS `activityPic`;
CREATE TABLE `activityPic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) unsigned DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `absolute_url` varchar(255) DEFAULT NULL,
  `isbanner` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`),
  CONSTRAINT `pic_activity` FOREIGN KEY (`aid`) REFERENCES `activity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `name` varchar(25) NOT NULL,
  `level` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-普通管理员；2-超级管理员',
  `remember_token` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  CONSTRAINT `admin_user` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `agreement`
-- ----------------------------
DROP TABLE IF EXISTS `agreement`;
CREATE TABLE `agreement` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `use` int(1) NOT NULL DEFAULT '1' COMMENT '0-停用，1-使用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户协议';

-- ----------------------------
--  Table structure for `charity`
-- ----------------------------
DROP TABLE IF EXISTS `charity`;
CREATE TABLE `charity` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL COMMENT '爱心捐赠标题',
  `content` text NOT NULL COMMENT '爱心捐赠内容',
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `time` int(5) DEFAULT NULL,
  `click` int(5) DEFAULT '0' COMMENT '点击次数',
  `top` int(1) NOT NULL DEFAULT '0' COMMENT '0-不置顶；1-置顶',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `charityOrder`
-- ----------------------------
DROP TABLE IF EXISTS `charityOrder`;
CREATE TABLE `charityOrder` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `sum` decimal(10,2) NOT NULL,
  `verify` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '0-后台未审核；1-审核通过；2-审核未通过',
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `method` varchar(20) DEFAULT NULL,
  `remark` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `cid` (`cid`),
  CONSTRAINT `charity_user` FOREIGN KEY (`uid`) REFERENCES `frontUser` (`front_uid`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_charity` FOREIGN KEY (`cid`) REFERENCES `charity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `charityPic`
-- ----------------------------
DROP TABLE IF EXISTS `charityPic`;
CREATE TABLE `charityPic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned DEFAULT NULL COMMENT '对应捐赠项目id',
  `url` varchar(255) NOT NULL,
  `absolute_url` varchar(255) DEFAULT NULL,
  `isbanner` int(1) NOT NULL DEFAULT '0' COMMENT '是否作为banner，0-不是；1-是',
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `isbanner` (`isbanner`) USING BTREE,
  CONSTRAINT `charity` FOREIGN KEY (`cid`) REFERENCES `charity` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `debt`
-- ----------------------------
DROP TABLE IF EXISTS `debt`;
CREATE TABLE `debt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '债券编号',
  `title` varchar(50) DEFAULT NULL COMMENT '债券标题',
  `type` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '债券种类',
  `content` varchar(255) DEFAULT NULL COMMENT '债券详情',
  `risk_keep` int(5) NOT NULL COMMENT '风险保障金',
  `net_value` decimal(6,2) NOT NULL COMMENT '净值',
  `interest` decimal(4,2) NOT NULL COMMENT '利息',
  `add_time` int(20) NOT NULL COMMENT '债券添加时间',
  `dates` int(5) NOT NULL DEFAULT '0' COMMENT '债券时长',
  `total` int(8) NOT NULL DEFAULT '0' COMMENT '债券总值',
  `move` int(1) NOT NULL DEFAULT '0' COMMENT '趋势，0-趋平，1-盈利，2-亏损',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '还款情况 0-未还 1-已还',
  `top` int(1) NOT NULL DEFAULT '0' COMMENT '基金是否置顶;0-不置顶；1-置顶',
  PRIMARY KEY (`id`),
  KEY `type` (`type`),
  CONSTRAINT `debt_type` FOREIGN KEY (`type`) REFERENCES `debtType` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `debtBuy`
-- ----------------------------
DROP TABLE IF EXISTS `debtBuy`;
CREATE TABLE `debtBuy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '债券编号',
  `did` int(10) unsigned NOT NULL COMMENT '债券id',
  `uid` int(10) NOT NULL COMMENT '购买用户id',
  `add_time` int(20) NOT NULL COMMENT '债券添加时间',
  `dates` int(5) NOT NULL COMMENT '债券时长',
  `total_buy` int(10) NOT NULL COMMENT '购买债券总金额',
  `buy_month` int(2) NOT NULL DEFAULT '1' COMMENT '购买月份',
  `buy_year` int(4) DEFAULT '2014' COMMENT '购买年份',
  PRIMARY KEY (`id`),
  KEY `did` (`did`),
  CONSTRAINT `debt` FOREIGN KEY (`did`) REFERENCES `debt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='债券购买总表';

-- ----------------------------
--  Table structure for `debtBuyList`
-- ----------------------------
DROP TABLE IF EXISTS `debtBuyList`;
CREATE TABLE `debtBuyList` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT COMMENT '债券修改记录编号',
  `bid` int(10) unsigned NOT NULL COMMENT '债券购买记录编号',
  `risk_keep` int(5) NOT NULL COMMENT '风险保障金',
  `net_value` decimal(6,2) NOT NULL COMMENT '净值',
  `interest` decimal(4,2) NOT NULL COMMENT '利息',
  `month` int(5) NOT NULL COMMENT '修改时间(月份)',
  `year` int(5) NOT NULL COMMENT '修改年份',
  `move` int(1) DEFAULT '0' COMMENT '趋势，0-趋平，1-盈利，2-亏损',
  PRIMARY KEY (`id`),
  KEY `bid` (`bid`),
  KEY `months` (`month`) USING BTREE,
  KEY `years` (`year`) USING BTREE,
  CONSTRAINT `buy_id` FOREIGN KEY (`bid`) REFERENCES `debtBuy` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='债券修改记录';

-- ----------------------------
--  Table structure for `debtOrder`
-- ----------------------------
DROP TABLE IF EXISTS `debtOrder`;
CREATE TABLE `debtOrder` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `verify` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '0-后台未审核 1-审核通过 2-审核未通过',
  `sum` decimal(10,2) DEFAULT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `did` (`did`),
  KEY `uid` (`uid`),
  CONSTRAINT `debt_order` FOREIGN KEY (`did`) REFERENCES `debt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `order_user` FOREIGN KEY (`uid`) REFERENCES `frontUser` (`front_uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `debtPic`
-- ----------------------------
DROP TABLE IF EXISTS `debtPic`;
CREATE TABLE `debtPic` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `did` int(10) unsigned DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `absolute_url` varchar(255) DEFAULT NULL,
  `type` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `did` (`did`),
  KEY `did_2` (`did`),
  KEY `did_3` (`did`),
  CONSTRAINT `debt_pic` FOREIGN KEY (`did`) REFERENCES `debt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `debtProtection`
-- ----------------------------
DROP TABLE IF EXISTS `debtProtection`;
CREATE TABLE `debtProtection` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `did` int(10) unsigned NOT NULL,
  `absolute_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `did` (`did`),
  KEY `did_2` (`did`),
  CONSTRAINT `pro_debt` FOREIGN KEY (`did`) REFERENCES `debt` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `debtType`
-- ----------------------------
DROP TABLE IF EXISTS `debtType`;
CREATE TABLE `debtType` (
  `id` int(1) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `choosable` int(1) NOT NULL DEFAULT '1' COMMENT '0-不可选；1-可选',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `frontUser`
-- ----------------------------
DROP TABLE IF EXISTS `frontUser`;
CREATE TABLE `frontUser` (
  `front_uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL,
  `user_name` varchar(20) DEFAULT NULL,
  `real_name` varchar(20) NOT NULL,
  `mobile` varchar(11) NOT NULL,
  `email` varchar(25) DEFAULT NULL COMMENT '用户邮箱',
  `remember_token` varchar(255) DEFAULT NULL COMMENT '用户保持登录token',
  `updated_at` varchar(25) DEFAULT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `gender` int(1) DEFAULT '0' COMMENT '0-男;1-女',
  `monthlyIncome` decimal(10,2) DEFAULT '0.00' COMMENT '用户月收入',
  `companyIndustry` varchar(50) DEFAULT NULL,
  `companyScale` varchar(50) DEFAULT NULL,
  `userJob` varchar(30) DEFAULT NULL,
  `userIntro` text,
  `aboutUser` text,
  `idCard` varchar(20) DEFAULT NULL COMMENT '身份证号',
  `action_admin` int(3) unsigned DEFAULT NULL COMMENT '操作管理员',
  PRIMARY KEY (`front_uid`),
  KEY `uid` (`uid`),
  KEY `action_admin` (`action_admin`),
  KEY `action_admin_2` (`action_admin`),
  CONSTRAINT `action_admin` FOREIGN KEY (`action_admin`) REFERENCES `admin` (`id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `user` FOREIGN KEY (`uid`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `introduce`
-- ----------------------------
DROP TABLE IF EXISTS `introduce`;
CREATE TABLE `introduce` (
  `id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `use` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-在使用，0-停用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='公司介绍';

-- ----------------------------
--  Table structure for `message`
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  CONSTRAINT `mgssage_user` FOREIGN KEY (`uid`) REFERENCES `frontUser` (`front_uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户反馈表';

-- ----------------------------
--  Table structure for `recharge`
-- ----------------------------
DROP TABLE IF EXISTS `recharge`;
CREATE TABLE `recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `sum` decimal(10,2) NOT NULL,
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `verify` int(1) unsigned DEFAULT '0' COMMENT '体现请求后台审核结果;0-后台未审核，1-审核通过，2-审核未通过',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  CONSTRAINT `recharge_user` FOREIGN KEY (`uid`) REFERENCES `frontUser` (`front_uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `user`
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) DEFAULT NULL,
  `last_login_ip` varchar(15) DEFAULT NULL,
  `last_login_time` int(11) DEFAULT NULL,
  `lock` int(1) NOT NULL DEFAULT '1' COMMENT '0-未锁定，1-用户被锁定',
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `modify` int(5) unsigned DEFAULT '0' COMMENT '信息修改次数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `withdrawDeposit`
-- ----------------------------
DROP TABLE IF EXISTS `withdrawDeposit`;
CREATE TABLE `withdrawDeposit` (
  `id` int(15) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL,
  `sum` decimal(10,2) NOT NULL COMMENT '提现金额',
  `created_at` varchar(25) DEFAULT NULL,
  `updated_at` varchar(25) DEFAULT NULL,
  `verify` int(1) unsigned NOT NULL DEFAULT '0' COMMENT '体现请求后台审核结果;0-后台未审核，1-审核通过，2-审核未通过',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  CONSTRAINT `withdraw_user` FOREIGN KEY (`uid`) REFERENCES `frontUser` (`front_uid`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='用户提现记录表';

SET FOREIGN_KEY_CHECKS = 1;
