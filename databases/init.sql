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

 Date: 06/07/2017 09:38:00 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='公告管理';

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
--  Table structure for `wa_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `wa_bonus`;
CREATE TABLE `wa_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '奖金记录自增ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `coin_type` int(11) NOT NULL DEFAULT '1' COMMENT '获得类型  1:金果  2:金种子',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送 9:提现返回 10:注册扣除',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 获得时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `poundage` int(11) DEFAULT NULL COMMENT '手续费',
  `ext_data` varchar(255) DEFAULT NULL COMMENT '扩展',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='资产流水表';

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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='轮播图管理';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='充值和扣除记录表';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='座位区域表';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='果树管理表';

-- ----------------------------
--  Table structure for `wa_fruiter_img`
-- ----------------------------
DROP TABLE IF EXISTS `wa_fruiter_img`;
CREATE TABLE `wa_fruiter_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruiter_id` int(11) NOT NULL DEFAULT '0' COMMENT '果树ID',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='果树图片表';

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
  `give_coin` int(11) DEFAULT NULL COMMENT '赠送金额',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `wa_goods`
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods`;
CREATE TABLE `wa_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '商品名字',
  `img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `price` int(11) DEFAULT NULL COMMENT '商品价格',
  `describe` text COMMENT '商品描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='商品管理';

-- ----------------------------
--  Table structure for `wa_goods_img`
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods_img`;
CREATE TABLE `wa_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '模型名',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='商品图片表';

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
  `out_status` int(10) NOT NULL DEFAULT 0 COMMENT "是否可以退网 0:否 1:是",
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
  `price` int(11) DEFAULT NULL COMMENT '商品价格',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  `goods_id` int(11) NOT NULL COMMENT '商品id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='订单管理';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='退网表';

-- ----------------------------
--  Table structure for `wa_record`
-- ----------------------------
DROP TABLE IF EXISTS `wa_record`;
CREATE TABLE `wa_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `created_at` int(11) DEFAULT NULL COMMENT '申请时间',
  `coin` int(11) DEFAULT NULL COMMENT '申请金额',
  `updated_at` int(10) DEFAULT NULL COMMENT '处理时间',
  `status` int(3) DEFAULT NULL COMMENT '状态',
  `charge` int(11) DEFAULT NULL COMMENT '手续费',
  `total` int(11) DEFAULT NULL COMMENT '总额',
  `date` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='提现记录表';

CREATE TABLE `wa_member_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `district` int(11) DEFAULT NULL COMMENT '区id',
  `is_ extra` tinyint(1) DEFAULT '0' COMMENT '是否是本身39个会员形成的区；1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员直推区表';


#插入数据
insert into `wa_adminuser` ( `username`, `auth_key`, `password_hash`, `password_reset_token`, `email`, `status`, `created_at`, `updated_at`) values ( 'admin', 'JAbY85Q5ozahz1h2hddB-uy5MWfcU-Wy', '$2y$13$vknBz7miG4O.W.mlPBLFE.0vcKiqHvMcz1xKCoZTyTPRVfEBCvvHG', null, 'a@a.com', '10', '1495553266', '1495553266');



insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/assignment/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/assignment/assign', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/assignment/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/assignment/revoke', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/assignment/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/default/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/default/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/menu/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/menu/create', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/menu/delete', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/menu/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/menu/update', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/menu/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/assign', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/create', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/delete', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/remove', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/update', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/permission/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/assign', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/create', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/delete', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/remove', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/update', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/role/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/route/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/route/assign', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/route/create', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/route/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/route/refresh', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/route/remove', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/rule/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/rule/create', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/rule/delete', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/rule/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/rule/update', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/rule/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/activate', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/change-password', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/delete', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/logout', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/request-password-reset', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/reset-password', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/signup', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/admin/user/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/*', '2', null, null, null, '1495766911', '1495766911');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/create', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/delete', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/index', '2', null, null, null, '1495766973', '1495766973');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/parsing', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/parsing-log', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/sample', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/update', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/announcements/view', '2', null, null, null, '1495766974', '1495766974');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/bonus/*', '2', null, null, null, '1496223270', '1496223270');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/bonus/index', '2', null, null, null, '1496223278', '1496223278');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/*', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/create', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/delete', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/index', '2', null, null, null, '1495777805', '1495777805');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/parsing', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/parsing-log', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/sample', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/update', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/branner/view', '2', null, null, null, '1495777806', '1495777806');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/count/*', '2', null, null, null, '1496283400', '1496283400');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/count/index', '2', null, null, null, '1496283404', '1496283404');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/default/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/default/db-explain', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/default/download-mail', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/default/index', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/default/toolbar', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/debug/default/view', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/deposit/*', '2', null, null, null, '1496371433', '1496371433');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/deposit/end', '2', null, null, null, '1496371518', '1496371518');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/deposit/increase', '2', null, null, null, '1496371602', '1496371602');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/deposit/open', '2', null, null, null, '1496371510', '1496371510');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/deposit/reduce', '2', null, null, null, '1496371634', '1496371634');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/dynagrid/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/dynagrid/settings/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/dynagrid/settings/get-config', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/fruiter/*', '2', null, null, null, '1496215224', '1496215224');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/fruiter/index', '2', null, null, null, '1496215311', '1496215311');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/fruiter/update', '2', null, null, null, '1496215338', '1496215338');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/give/*', '2', null, null, null, '1496212929', '1496212929');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/give/index', '2', null, null, null, '1496213018', '1496213018');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/*', '2', null, null, null, '1496284298', '1496284298');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/create', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/delete', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/index', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/parsing', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/parsing-log', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/sample', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/update', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/goods/view', '2', null, null, null, '1496284350', '1496284350');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/gridview/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/gridview/export/*', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/gridview/export/download', '2', null, null, null, '1495553461', '1495553461');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/*', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/create', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/delete', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/index', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/outline', '2', null, null, null, '1495696926', '1495696926');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/parsing', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/parsing-log', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/sample', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/update', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/member/view', '2', null, null, null, '1495595775', '1495595775');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/outline/*', '2', null, null, null, '1495763702', '1495763702');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/outline/index', '2', null, null, null, '1495763725', '1495763725');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/*', '2', null, null, null, '1495626463', '1495626463');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/delete', '2', null, null, null, '1495680404', '1495680404');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/index', '2', null, null, null, '1495680393', '1495680393');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/parsing', '2', null, null, null, '1495680414', '1495680414');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/parsing-log', '2', null, null, null, '1495680408', '1495680408');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/sample', '2', null, null, null, '1495680412', '1495680412');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '/record/update', '2', null, null, null, '1495680401', '1495680401');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( 'RBAC管理', '2', null, null, null, '1495557434', '1495557434');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '管理员', '1', '具有后台管理员的角色', null, null, '1495553016', '1495767296');
insert into `wa_auth_item` ( `name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`) values ( '财务员', '1', '负责审核提现申请的角色', null, null, '1495553314', '1495553314');

insert into `wa_auth_assignment` ( `item_name`, `user_id`, `created_at`) values ( 'RBAC管理', '1', '1495557448');
insert into `wa_auth_assignment` ( `item_name`, `user_id`, `created_at`) values ( '管理员', '1', '1495555351');


insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/assignment/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/assignment/assign');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/assignment/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/assignment/revoke');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/assignment/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/default/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/default/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/menu/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/menu/create');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/menu/delete');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/menu/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/menu/update');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/menu/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/assign');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/create');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/delete');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/remove');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/update');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/permission/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/assign');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/create');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/delete');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/remove');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/update');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/role/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/route/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/route/assign');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/route/create');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/route/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/route/refresh');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/route/remove');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/rule/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/rule/create');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/rule/delete');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/rule/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/rule/update');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/rule/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/activate');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/change-password');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/delete');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/logout');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/request-password-reset');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/reset-password');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/signup');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/admin/user/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/announcements/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/bonus/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/bonus/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/branner/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/count/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/count/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/default/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/default/db-explain');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/default/download-mail');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/default/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/default/toolbar');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/debug/default/view');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/deposit/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/deposit/end');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/deposit/open');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/dynagrid/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/dynagrid/settings/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/dynagrid/settings/get-config');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/fruiter/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/fruiter/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/give/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/give/index');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/goods/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/gridview/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/gridview/export/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( 'RBAC管理', '/gridview/export/download');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/member/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/outline/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/record/*');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', '/record/delete');
insert into `wa_auth_item_child` ( `parent`, `child`) values ( '管理员', 'RBAC管理');

insert into `wa_district` ( `member_id`, `district`, `seat`, `created_at`) values ( '1', '1', '1', '1496630244');

insert into `wa_member` ( `parent_id`, `name`, `password`, `mobile`, `deposit_bank`, `bank_account`, `address`, `last_login_time`, `status`, `created_at`, `updated_at`, `vip_number`, `a_coin`, `b_coin`, `child_num`) values ( '1', 'member1', '$2y$13$gu094onaVGc9v5Juiz6SD.Tcoxio8IANlYRZjgd7mlFEDjS1OtIVK', '13219890986', '成都银行', '62284848822113464', '环球中心', '1496629517', '1', '0', '1496629900', '1', '99994949', '2147479647', '0');

insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '权限管理', null, '/admin/user/index', null, 0x66610d0a, '');
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '管理员列表', '1', '/admin/user/index', null, 0x3c6920636c6173733d22666122202f3e, '');
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '分配', '1', '/admin/assignment/index', null, null, '');
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '角色列表', '1', '/admin/role/index', null, null, 'aa');
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '路由列表', '1', '/admin/route/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '规则列表', '1', '/admin/rule/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '菜单列表', '1', '/admin/menu/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '会员管理', null, '/member/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '财务管理', null, '/record/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '退网记录', '8', '/outline/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '会员信息', '8', '/member/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '公告管理', null, '/announcements/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '广告管理', null, '/branner/index', null, 0xe5898de58fb0e8bdaee692ade59bbee78987e79a84e7aea1e79086, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '赠送记录', '8', '/give/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '果树管理', null, '/fruiter/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '统计中心', null, '/count/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '商品管理', null, '/goods/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '充值管理', null, '/deposit/index', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '充值', '18', '/deposit/open', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '扣除', '18', '/deposit/end', null, null, null);
insert into `wan_an`.`wa_menu` ( `name`, `parent`, `route`, `order`, `data`, `icon`) values ( '充值记录', '18', '/deposit/index', null, null, null);

SET FOREIGN_KEY_CHECKS = 1;