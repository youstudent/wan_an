/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.2.181
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : 192.168.2.181
 Source Database       : wan_an

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : utf-8

 Date: 06/06/2017 09:09:47 AM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `log_upload`
-- ----------------------------
DROP TABLE IF EXISTS `log_upload`;
CREATE TABLE `log_upload` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `title` varchar(128) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `fileori` varchar(255) DEFAULT NULL,
  `params` longblob,
  `values` longblob,
  `warning` longblob,
  `keys` text,
  `type` tinyint(1) DEFAULT NULL,
  `userCreate` int(11) DEFAULT NULL,
  `userUpdate` int(11) DEFAULT NULL,
  `updateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `createDate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `tbl_dynagrid`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_dynagrid`;
CREATE TABLE `tbl_dynagrid` (
  `id` varchar(100) NOT NULL COMMENT 'Unique dynagrid setting identifier',
  `filter_id` varchar(100) DEFAULT NULL COMMENT 'Filter setting identifier',
  `sort_id` varchar(100) DEFAULT NULL COMMENT 'Sort setting identifier',
  `data` varchar(5000) DEFAULT NULL COMMENT 'Json encoded data for the dynagrid configuration',
  PRIMARY KEY (`id`),
  KEY `tbl_dynagrid_FK1` (`filter_id`),
  KEY `tbl_dynagrid_FK2` (`sort_id`),
  CONSTRAINT `tbl_dynagrid_FK1` FOREIGN KEY (`filter_id`) REFERENCES `tbl_dynagrid_dtl` (`id`),
  CONSTRAINT `tbl_dynagrid_FK2` FOREIGN KEY (`sort_id`) REFERENCES `tbl_dynagrid_dtl` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `tbl_dynagrid_dtl`
-- ----------------------------
DROP TABLE IF EXISTS `tbl_dynagrid_dtl`;
CREATE TABLE `tbl_dynagrid_dtl` (
  `id` varchar(100) NOT NULL COMMENT 'Unique dynagrid detail setting identifier',
  `category` varchar(10) NOT NULL COMMENT 'Dynagrid detail setting category "filter" or "sort"',
  `name` varchar(150) NOT NULL COMMENT 'Name to identify the dynagrid detail setting',
  `data` varchar(5000) DEFAULT NULL COMMENT 'Json encoded data for the dynagrid detail configuration',
  `dynagrid_id` varchar(100) NOT NULL COMMENT 'Related dynagrid identifier',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tbl_dynagrid_dtl_UK1` (`name`,`category`,`dynagrid_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `wa_adminuser`
-- ----------------------------
DROP TABLE IF EXISTS `wa_adminuser`;
CREATE TABLE `wa_adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `wa_announcements`
-- ----------------------------
DROP TABLE IF EXISTS `wa_announcements`;
CREATE TABLE `wa_announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL COMMENT '标题',
  `content` text NOT NULL,
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `status` int(10) DEFAULT NULL COMMENT '状态',
  `author` varchar(20) DEFAULT NULL COMMENT '发布人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COMMENT='公告管理';

-- ----------------------------
--  Table structure for `wa_auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `wa_auth_assignment`;
CREATE TABLE `wa_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `wa_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `wa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `wa_auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `wa_auth_item`;
CREATE TABLE `wa_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `wa_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `wa_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `wa_auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `wa_auth_item_child`;
CREATE TABLE `wa_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `wa_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `wa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `wa_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `wa_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `wa_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `wa_auth_rule`;
CREATE TABLE `wa_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Table structure for `wa_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `wa_bonus`;
CREATE TABLE `wa_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '奖金记录自增ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `coin_type` int(11) NOT NULL DEFAULT '1' COMMENT '获得类型  1:金果  2:金种子',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 获得时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `poundage` int(11) DEFAULT NULL COMMENT '手续费',
  `ext_data` varchar(255) DEFAULT NULL COMMENT '扩展',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=112 DEFAULT CHARSET=utf8 COMMENT='资产流水表';

-- ----------------------------
--  Table structure for `wa_branner`
-- ----------------------------
DROP TABLE IF EXISTS `wa_branner`;
CREATE TABLE `wa_branner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '轮播图名称',
  `content` text COMMENT '图片',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `img` varchar(255) DEFAULT NULL,
  `http` varchar(255) DEFAULT NULL COMMENT '域名',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COMMENT='轮播图管理';

-- ----------------------------
--  Table structure for `wa_deposit`
-- ----------------------------
DROP TABLE IF EXISTS `wa_deposit`;
CREATE TABLE `wa_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '充值记录自增ID',
  `member_id` int(11) NOT NULL DEFAULT '1' COMMENT '充值会员id',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '币种 1:金果 2:金种子',
  `operation` int(11) NOT NULL DEFAULT '1' COMMENT '操作类型 1:充值 2:扣除',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 奖金获得时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8 COMMENT='充值和扣除记录表';

-- ----------------------------
--  Table structure for `wa_district`
-- ----------------------------
DROP TABLE IF EXISTS `wa_district`;
CREATE TABLE `wa_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `district` int(11) NOT NULL COMMENT '区域id',
  `seat` int(11) DEFAULT NULL COMMENT '座位id',
  `created_at` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COMMENT='座位区域表';

-- ----------------------------
--  Table structure for `wa_fruiter`
-- ----------------------------
DROP TABLE IF EXISTS `wa_fruiter`;
CREATE TABLE `wa_fruiter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '果树自增id',
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `order_sn` varchar(255) NOT NULL COMMENT '订单号',
  `fruiter_name` varchar(20) DEFAULT NULL COMMENT '果树名称',
  `updated_at` int(10) DEFAULT NULL,
  `fruiter_img` varchar(255) DEFAULT NULL COMMENT '果树图片',
  `created_at` int(10) NOT NULL COMMENT '补充时间',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态 1:已补充 0:未补充',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='果树管理表';

-- ----------------------------
--  Table structure for `wa_fruiter_img`
-- ----------------------------
DROP TABLE IF EXISTS `wa_fruiter_img`;
CREATE TABLE `wa_fruiter_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruiter_id` int(11) NOT NULL DEFAULT '0' COMMENT '果树ID',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=utf8mb4 COMMENT='果树图片表';

-- ----------------------------
--  Table structure for `wa_give`
-- ----------------------------
DROP TABLE IF EXISTS `wa_give`;
CREATE TABLE `wa_give` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `give_member_id` int(11) DEFAULT NULL COMMENT '赠送会员id',
  `type` int(11) DEFAULT NULL COMMENT '赠送类别  1:金果   2:金种子',
  `created_at` int(14) DEFAULT NULL COMMENT '赠送时间',
  `give_coin` decimal(20,0) DEFAULT NULL COMMENT '赠送金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1496370665 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `wa_goods`
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods`;
CREATE TABLE `wa_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '商品名字',
  `img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `describe` text COMMENT '商品描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='商品管理';

-- ----------------------------
--  Table structure for `wa_goods_img`
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods_img`;
CREATE TABLE `wa_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '模型名',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COMMENT='商品图片表';

-- ----------------------------
--  Table structure for `wa_member`
-- ----------------------------
DROP TABLE IF EXISTS `wa_member`;
CREATE TABLE `wa_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员自增ID',
  `parent_id` int(10) NOT NULL DEFAULT '1' COMMENT '直推会员id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户姓名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '会员密码',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `deposit_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_account` varchar(255) NOT NULL DEFAULT '' COMMENT '银行账号',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '状态 0:被冻结 1:正常 2:已退网',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 注册时间 入网时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间 退网时间',
  `vip_number` int(11) NOT NULL COMMENT '会员卡号',
  `a_coin` int(11) NOT NULL COMMENT '金果数',
  `b_coin` int(11) NOT NULL COMMENT '金种子数',
  `child_num` int(11) NOT NULL COMMENT '直推数量',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

-- ----------------------------
--  Table structure for `wa_member_status`
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_status`;
CREATE TABLE `wa_member_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '状态',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '状态名',
  `value` int(11) NOT NULL DEFAULT '1' COMMENT '状态值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户状态表';

-- ----------------------------
--  Table structure for `wa_menu`
-- ----------------------------
DROP TABLE IF EXISTS `wa_menu`;
CREATE TABLE `wa_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` blob,
  `icon` varchar(32) DEFAULT NULL COMMENT '菜单icon',
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `wa_menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `wa_menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `wa_migration`
-- ----------------------------
DROP TABLE IF EXISTS `wa_migration`;
CREATE TABLE `wa_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `wa_order`
-- ----------------------------
DROP TABLE IF EXISTS `wa_order`;
CREATE TABLE `wa_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单号',
  `member_id` int(11) DEFAULT NULL COMMENT '购买会员id',
  `name` varchar(30) DEFAULT NULL COMMENT '商品名字',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='订单管理';

-- ----------------------------
--  Table structure for `wa_outline`
-- ----------------------------
DROP TABLE IF EXISTS `wa_outline`;
CREATE TABLE `wa_outline` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '退网记录自增ID',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '退网会员ID',
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '账号状态 1:正常 0:禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='退网表';

-- ----------------------------
--  Table structure for `wa_record`
-- ----------------------------
DROP TABLE IF EXISTS `wa_record`;
CREATE TABLE `wa_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `created_at` int(11) DEFAULT NULL COMMENT '申请时间',
  `coin` decimal(20,0) DEFAULT NULL COMMENT '申请金额',
  `updated_at` int(10) DEFAULT NULL COMMENT '处理时间',
  `status` int(3) DEFAULT NULL COMMENT '状态',
  `charge` decimal(20,0) DEFAULT NULL COMMENT '手续费',
  `total` decimal(20,0) DEFAULT NULL COMMENT '总额',
  `date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='提现记录表';

-- ----------------------------
--  Table structure for `wa_user`
-- ----------------------------
DROP TABLE IF EXISTS `wa_user`;
CREATE TABLE `wa_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
