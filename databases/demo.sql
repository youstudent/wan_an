/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : localhost
 Source Database       : wan_an

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 05/24/2017 07:19:04 AM
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Records of `wa_adminuser`
-- ----------------------------
BEGIN;
INSERT INTO `wa_adminuser` VALUES ('1', 'admin', 'JAbY85Q5ozahz1h2hddB-uy5MWfcU-Wy', '$2y$13$0sxPc8o5ZmIIhABKcmjYQeowdIPeZJN73wrDAUVumYHHx9t3LXDAK', null, 'a@a.com', '10', '1495553266', '1495553266'), ('2', 'admin2', 'Th7mgiQ_6r6tOd-DP0-d11mFRLSNAmzr', '$2y$13$oH/JxoXyJMPwhhczkqqKGeHhHXbPHvZux6GFN6.UF0pKkP9djGS96', null, 'ab@a.com', '10', '1495553280', '1495553280'), ('3', 'admin3', 'zgNtPRVWWrq3LCB6u4yF8BBQ5Q2VmRjc', '$2y$13$rX.ETIDFydbLqhp6DCwVpeBo0nPKf7nXUVK7EFYRCK51u4WCvfdsS', null, 'abc@a.com', '10', '1495559484', '1495559484');
COMMIT;

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
--  Records of `wa_auth_assignment`
-- ----------------------------
BEGIN;
INSERT INTO `wa_auth_assignment` VALUES ('RBAC管理', '1', '1495557448'), ('管理员', '1', '1495555351');
COMMIT;

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
--  Records of `wa_auth_item`
-- ----------------------------
BEGIN;
INSERT INTO `wa_auth_item` VALUES ('/admin/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/revoke', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/default/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/default/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/refresh', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/activate', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/change-password', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/logout', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/request-password-reset', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/reset-password', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/signup', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/view', '2', null, null, null, '1495553461', '1495553461'), ('/debug/*', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/*', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/db-explain', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/download-mail', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/index', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/toolbar', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/view', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/*', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/settings/*', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/settings/get-config', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/*', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/export/*', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/export/download', '2', null, null, null, '1495553461', '1495553461'), ('RBAC管理', '2', null, null, null, '1495557434', '1495557434'), ('管理员', '1', '具有后台管理员的角色', null, null, '1495553016', '1495553016'), ('财务员', '1', '负责审核提现申请的角色', null, null, '1495553314', '1495553314');
COMMIT;

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
--  Records of `wa_auth_item_child`
-- ----------------------------
BEGIN;
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/*'), ('RBAC管理', '/admin/assignment/*'), ('RBAC管理', '/admin/assignment/assign'), ('RBAC管理', '/admin/assignment/index'), ('RBAC管理', '/admin/assignment/revoke'), ('RBAC管理', '/admin/assignment/view'), ('RBAC管理', '/admin/default/*'), ('RBAC管理', '/admin/default/index'), ('RBAC管理', '/admin/menu/*'), ('RBAC管理', '/admin/menu/create'), ('RBAC管理', '/admin/menu/delete'), ('RBAC管理', '/admin/menu/index'), ('RBAC管理', '/admin/menu/update'), ('RBAC管理', '/admin/menu/view'), ('RBAC管理', '/admin/permission/*'), ('RBAC管理', '/admin/permission/assign'), ('RBAC管理', '/admin/permission/create'), ('RBAC管理', '/admin/permission/delete'), ('RBAC管理', '/admin/permission/index'), ('RBAC管理', '/admin/permission/remove'), ('RBAC管理', '/admin/permission/update'), ('RBAC管理', '/admin/permission/view'), ('RBAC管理', '/admin/role/*'), ('RBAC管理', '/admin/role/assign'), ('RBAC管理', '/admin/role/create'), ('RBAC管理', '/admin/role/delete'), ('RBAC管理', '/admin/role/index'), ('RBAC管理', '/admin/role/remove'), ('RBAC管理', '/admin/role/update'), ('RBAC管理', '/admin/role/view'), ('RBAC管理', '/admin/route/*'), ('RBAC管理', '/admin/route/assign'), ('RBAC管理', '/admin/route/create'), ('RBAC管理', '/admin/route/index'), ('RBAC管理', '/admin/route/refresh'), ('RBAC管理', '/admin/route/remove'), ('RBAC管理', '/admin/rule/*'), ('RBAC管理', '/admin/rule/create'), ('RBAC管理', '/admin/rule/delete'), ('RBAC管理', '/admin/rule/index'), ('RBAC管理', '/admin/rule/update'), ('RBAC管理', '/admin/rule/view'), ('RBAC管理', '/admin/user/*'), ('RBAC管理', '/admin/user/activate'), ('RBAC管理', '/admin/user/change-password'), ('RBAC管理', '/admin/user/delete'), ('RBAC管理', '/admin/user/index'), ('RBAC管理', '/admin/user/logout'), ('RBAC管理', '/admin/user/request-password-reset'), ('RBAC管理', '/admin/user/reset-password'), ('RBAC管理', '/admin/user/signup'), ('RBAC管理', '/admin/user/view'), ('RBAC管理', '/debug/*'), ('RBAC管理', '/debug/default/*'), ('RBAC管理', '/debug/default/db-explain'), ('RBAC管理', '/debug/default/download-mail'), ('RBAC管理', '/debug/default/index'), ('RBAC管理', '/debug/default/toolbar'), ('RBAC管理', '/debug/default/view'), ('RBAC管理', '/dynagrid/*'), ('RBAC管理', '/dynagrid/settings/*'), ('RBAC管理', '/dynagrid/settings/get-config'), ('RBAC管理', '/gridview/*'), ('RBAC管理', '/gridview/export/*'), ('RBAC管理', '/gridview/export/download');
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `wa_menu`
-- ----------------------------
BEGIN;
INSERT INTO `wa_menu` VALUES ('1', '权限管理', null, '/admin/user/index', null, 0x66610d0a, ''), ('2', '管理员列表', '1', '/admin/user/index', null, 0x3c6920636c6173733d22666122202f3e, ''), ('3', '分配', '1', '/admin/assignment/index', null, null, ''), ('4', '角色列表', '1', '/admin/role/index', null, null, 'aa'), ('5', '路由列表', '1', '/admin/route/index', null, null, null), ('6', '规则列表', '1', '/admin/rule/index', null, null, null), ('7', '菜单列表', '1', '/admin/menu/index', null, null, null);
COMMIT;

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
--  Records of `wa_migration`
-- ----------------------------
BEGIN;
INSERT INTO `wa_migration` VALUES ('m000000_000000_base', '1495468510'), ('m130524_201442_init', '1495468512'), ('m140506_102106_rbac_init', '1495469173'), ('m140602_111327_create_menu_table', '1495471526'), ('m160312_050000_create_user', '1495471526');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;

-- ----------------------------
-- Table structure for wa_bonus
-- ----------------------------
DROP TABLE IF EXISTS `wa_bonus`;
CREATE TABLE `wa_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '奖金记录自增ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '金果获得类型 1:绩效2:分享3:额外分享4:提现',
  `coin_amount` int(10) NOT NULL DEFAULT '0' COMMENT '总收入',
  `coin_count` int(10) NOT NULL DEFAULT '0' COMMENT '出入流水账金额',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '账号状态 1:正常 0:禁用',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 奖金获得时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='金果流水表';

-- ----------------------------
-- Table structure for wa_deposit
-- ----------------------------
DROP TABLE IF EXISTS `wa_deposit`;
CREATE TABLE `wa_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '充值记录自增ID',
  `member_id` int(10) NOT NULL DEFAULT '1' COMMENT '充值会员id',
  `deposit_type` varchar(255) NOT NULL DEFAULT '' COMMENT '充值类型',
  `deposit_num` int(10) NOT NULL DEFAULT '0' COMMENT '充值金额',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间 奖金获得时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值记录表';

-- ----------------------------
-- Table structure for wa_member
-- ----------------------------
DROP TABLE IF EXISTS `wa_member`;
CREATE TABLE `wa_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员自增ID',
  `site` int(11) DEFAULT '0' COMMENT '座位号',
  `parent_id` int(10) NOT NULL DEFAULT '1' COMMENT '直推会员id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户姓名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '会员密码',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `deposit_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_account` varchar(255) NOT NULL DEFAULT '' COMMENT '银行账号',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `group_num` int(10) NOT NULL DEFAULT '0' COMMENT '区数量',
  `child_num` int(10) NOT NULL DEFAULT '0' COMMENT '直系挂靠的会员数',
  `a_coin` int(10) NOT NULL DEFAULT '0' COMMENT '金果数',
  `b_coin` int(10) NOT NULL DEFAULT '0' COMMENT '金种子数',
  `gross_income` int(10) NOT NULL DEFAULT '0' COMMENT '总收入',
  `gorss_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '总提成',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` int(10) DEFAULT '1' COMMENT '账号状态关联member_status',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 注册时间 入网时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

-- ----------------------------
-- Table structure for wa_member_status
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_status`;
CREATE TABLE `wa_member_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '状态',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '状态名',
  `value` int(11) NOT NULL DEFAULT '1' COMMENT '状态值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='用户状态表';

-- ----------------------------
-- Table structure for wa_outline
-- ----------------------------
DROP TABLE IF EXISTS `wa_outline`;
CREATE TABLE `wa_outline` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '退网记录自增ID',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '退网会员ID',
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '账号状态 1:正常 0:禁用',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退网表';

-- ----------------------------
-- Table structure for wa_announcements
-- ----------------------------
DROP TABLE IF EXISTS `wa_announcements`;
CREATE TABLE `wa_announcements` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL COMMENT '标题',
  `content` text NOT NULL,
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公告管理';

-- ----------------------------
-- Table structure for wa_branner
-- ----------------------------
DROP TABLE IF EXISTS `wa_branner`;
CREATE TABLE `wa_branner` (
  `id` int(11) NOT NULL,
  `name` varchar(30) DEFAULT NULL COMMENT '轮播图名称',
  `img` varchar(255) DEFAULT NULL COMMENT '图片',
  `content_img` text COMMENT '轮播图文本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='轮播图管理';

-- ----------------------------
-- Table structure for wa_fruiter
-- ----------------------------
DROP TABLE IF EXISTS `wa_fruiter`;
CREATE TABLE `wa_fruiter` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `order_sn` varchar(255) NOT NULL COMMENT '订单号',
  `fruiter_name` varchar(20) DEFAULT NULL COMMENT '果树名称',
  `updated_at` int(10) DEFAULT NULL,
  `fruiter_img` varchar(255) DEFAULT NULL COMMENT '果树图片',
  `created_at` int(10) NOT NULL COMMENT '补充时间',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='果树管理表';

-- Table structure for wa_goods
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods`;
CREATE TABLE `wa_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL COMMENT '商品名字',
  `img` varchar(255) DEFAULT NULL COMMENT '商品图片',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `describe` text COMMENT '商品描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品管理';


-- ----------------------------
-- Table structure for wa_order
-- ----------------------------
DROP TABLE IF EXISTS `wa_order`;
CREATE TABLE `wa_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_sn` varchar(50) DEFAULT NULL COMMENT '订单号',
  `member_id` int(11) DEFAULT NULL COMMENT '购买会员id',
  `name` varchar(30) DEFAULT NULL COMMENT '商品名字',
  `price` decimal(10,2) DEFAULT NULL COMMENT '商品价格',
  `status` int(11) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单管理';

-- ----------------------------
-- Table structure for wa_record
-- ----------------------------
DROP TABLE IF EXISTS `wa_record`;
CREATE TABLE `wa_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `created_at` int(11) DEFAULT NULL COMMENT '申请时间',
  `coin` decimal(20,0) DEFAULT NULL COMMENT '申请金额',
  `updated_at` int(10) DEFAULT NULL COMMENT '处理时间',
  `status` int(3) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现记录表';
>>>>>>> 修改
