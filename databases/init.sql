/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50542
 Source Host           : localhost
 Source Database       : wan_an

 Target Server Type    : MySQL
 Target Server Version : 50542
 File Encoding         : utf-8

 Date: 06/20/2017 17:47:24 PM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='grid扩展的表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='grid扩展的表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='grid扩展的表';

-- ----------------------------
--  Table structure for `wa_adminuser`
-- ----------------------------
DROP TABLE IF EXISTS `wa_adminuser`;
CREATE TABLE `wa_adminuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_reset_token` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
--  Records of `wa_adminuser`
-- ----------------------------
BEGIN;
INSERT INTO `wa_adminuser` VALUES ('1', 'admin', 'JAbY85Q5ozahz1h2hddB-uy5MWfcU-Wy', '$2y$13$vknBz7miG4O.W.mlPBLFE.0vcKiqHvMcz1xKCoZTyTPRVfEBCvvHG', null, 'a@a.com', '10', '1495553266', '1495553266'), ('2', 'caiwu', 'xo6-_wHvXt1942ViPoAU8BgxeZZh-U0F', '$2y$13$ECsmoD9dFyMok2hDh3Fe3.FqnICWa7Auhr9Wkd9sRtrhF0Lo6eVHi', null, 'caiwu@wantu3.com', '10', '1497840756', '1497840756');
COMMIT;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公告管理';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='RBAC权限栏目';

-- ----------------------------
--  Records of `wa_auth_assignment`
-- ----------------------------
BEGIN;
INSERT INTO `wa_auth_assignment` VALUES ('RBAC管理', '1', '1495557448'), ('管理员', '1', '1495555351'), ('财务员', '2', '1497840793');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='RBAC权限栏目';

-- ----------------------------
--  Records of `wa_auth_item`
-- ----------------------------
BEGIN;
INSERT INTO `wa_auth_item` VALUES ('/admin/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/revoke', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/default/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/default/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/refresh', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/activate', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/change-password', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/logout', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/request-password-reset', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/reset-password', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/signup', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/view', '2', null, null, null, '1495553461', '1495553461'), ('/announcements/*', '2', null, null, null, '1495766911', '1495766911'), ('/announcements/create', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/delete', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/index', '2', null, null, null, '1495766973', '1495766973'), ('/announcements/parsing', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/parsing-log', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/sample', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/update', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/view', '2', null, null, null, '1495766974', '1495766974'), ('/bonus/*', '2', null, null, null, '1496223270', '1496223270'), ('/bonus/index', '2', null, null, null, '1496223278', '1496223278'), ('/branner/*', '2', null, null, null, '1495777806', '1495777806'), ('/branner/create', '2', null, null, null, '1495777806', '1495777806'), ('/branner/delete', '2', null, null, null, '1495777806', '1495777806'), ('/branner/index', '2', null, null, null, '1495777805', '1495777805'), ('/branner/parsing', '2', null, null, null, '1495777806', '1495777806'), ('/branner/parsing-log', '2', null, null, null, '1495777806', '1495777806'), ('/branner/sample', '2', null, null, null, '1495777806', '1495777806'), ('/branner/update', '2', null, null, null, '1495777806', '1495777806'), ('/branner/view', '2', null, null, null, '1495777806', '1495777806'), ('/count/*', '2', null, null, null, '1496283400', '1496283400'), ('/count/index', '2', null, null, null, '1496283404', '1496283404'), ('/debug/*', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/*', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/db-explain', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/download-mail', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/index', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/toolbar', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/view', '2', null, null, null, '1495553461', '1495553461'), ('/deposit/*', '2', null, null, null, '1496371433', '1496371433'), ('/deposit/end', '2', null, null, null, '1496371518', '1496371518'), ('/deposit/increase', '2', null, null, null, '1496371602', '1496371602'), ('/deposit/open', '2', null, null, null, '1496371510', '1496371510'), ('/deposit/reduce', '2', null, null, null, '1496371634', '1496371634'), ('/dynagrid/*', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/settings/*', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/settings/get-config', '2', null, null, null, '1495553461', '1495553461'), ('/fruiter/*', '2', null, null, null, '1496215224', '1496215224'), ('/fruiter/index', '2', null, null, null, '1496215311', '1496215311'), ('/fruiter/update', '2', null, null, null, '1496215338', '1496215338'), ('/give/*', '2', null, null, null, '1496212929', '1496212929'), ('/give/index', '2', null, null, null, '1496213018', '1496213018'), ('/goods/*', '2', null, null, null, '1496284298', '1496284298'), ('/goods/create', '2', null, null, null, '1496284350', '1496284350'), ('/goods/delete', '2', null, null, null, '1496284350', '1496284350'), ('/goods/index', '2', null, null, null, '1496284350', '1496284350'), ('/goods/parsing', '2', null, null, null, '1496284350', '1496284350'), ('/goods/parsing-log', '2', null, null, null, '1496284350', '1496284350'), ('/goods/sample', '2', null, null, null, '1496284350', '1496284350'), ('/goods/update', '2', null, null, null, '1496284350', '1496284350'), ('/goods/view', '2', null, null, null, '1496284350', '1496284350'), ('/gridview/*', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/export/*', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/export/download', '2', null, null, null, '1495553461', '1495553461'), ('/member/*', '2', null, null, null, '1495595775', '1495595775'), ('/member/create', '2', null, null, null, '1495595775', '1495595775'), ('/member/delete', '2', null, null, null, '1495595775', '1495595775'), ('/member/index', '2', null, null, null, '1495595775', '1495595775'), ('/member/outline', '2', null, null, null, '1495696926', '1495696926'), ('/member/parsing', '2', null, null, null, '1495595775', '1495595775'), ('/member/parsing-log', '2', null, null, null, '1495595775', '1495595775'), ('/member/sample', '2', null, null, null, '1495595775', '1495595775'), ('/member/tree', '2', null, null, null, '1497343283', '1497343283'), ('/member/update', '2', null, null, null, '1495595775', '1495595775'), ('/member/view', '2', null, null, null, '1495595775', '1495595775'), ('/offline/index', '2', null, null, null, '1497947392', '1497947392'), ('/offline/update', '2', null, null, null, '1497947392', '1497947392'), ('/outline/*', '2', null, null, null, '1495763702', '1495763702'), ('/outline/index', '2', null, null, null, '1495763725', '1495763725'), ('/record/*', '2', null, null, null, '1495626463', '1495626463'), ('/record/delete', '2', null, null, null, '1495680404', '1495680404'), ('/record/index', '2', null, null, null, '1495680393', '1495680393'), ('/record/parsing', '2', null, null, null, '1495680414', '1495680414'), ('/record/parsing-log', '2', null, null, null, '1495680408', '1495680408'), ('/record/sample', '2', null, null, null, '1495680412', '1495680412'), ('/record/update', '2', null, null, null, '1495680401', '1495680401'), ('/tools/*', '2', null, null, null, '1497420759', '1497420759'), ('RBAC管理', '2', null, null, null, '1495557434', '1495557434'), ('管理员', '1', '具有后台管理员的角色', null, null, '1495553016', '1495767296'), ('财务员', '1', '负责审核提现申请的角色', null, null, '1495553314', '1495553314');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='RBAC权限栏目';

-- ----------------------------
--  Records of `wa_auth_item_child`
-- ----------------------------
BEGIN;
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/*'), ('RBAC管理', '/admin/assignment/*'), ('RBAC管理', '/admin/assignment/assign'), ('RBAC管理', '/admin/assignment/index'), ('RBAC管理', '/admin/assignment/revoke'), ('RBAC管理', '/admin/assignment/view'), ('RBAC管理', '/admin/default/*'), ('RBAC管理', '/admin/default/index'), ('RBAC管理', '/admin/menu/*'), ('RBAC管理', '/admin/menu/create'), ('RBAC管理', '/admin/menu/delete'), ('RBAC管理', '/admin/menu/index'), ('RBAC管理', '/admin/menu/update'), ('RBAC管理', '/admin/menu/view'), ('RBAC管理', '/admin/permission/*'), ('RBAC管理', '/admin/permission/assign'), ('RBAC管理', '/admin/permission/create'), ('RBAC管理', '/admin/permission/delete'), ('RBAC管理', '/admin/permission/index'), ('RBAC管理', '/admin/permission/remove'), ('RBAC管理', '/admin/permission/update'), ('RBAC管理', '/admin/permission/view'), ('RBAC管理', '/admin/role/*'), ('RBAC管理', '/admin/role/assign'), ('RBAC管理', '/admin/role/create'), ('RBAC管理', '/admin/role/delete'), ('RBAC管理', '/admin/role/index'), ('RBAC管理', '/admin/role/remove'), ('RBAC管理', '/admin/role/update'), ('RBAC管理', '/admin/role/view'), ('RBAC管理', '/admin/route/*'), ('RBAC管理', '/admin/route/assign'), ('RBAC管理', '/admin/route/create'), ('RBAC管理', '/admin/route/index'), ('RBAC管理', '/admin/route/refresh'), ('RBAC管理', '/admin/route/remove'), ('RBAC管理', '/admin/rule/*'), ('RBAC管理', '/admin/rule/create'), ('RBAC管理', '/admin/rule/delete'), ('RBAC管理', '/admin/rule/index'), ('RBAC管理', '/admin/rule/update'), ('RBAC管理', '/admin/rule/view'), ('RBAC管理', '/admin/user/*'), ('RBAC管理', '/admin/user/activate'), ('RBAC管理', '/admin/user/change-password'), ('RBAC管理', '/admin/user/delete'), ('RBAC管理', '/admin/user/index'), ('RBAC管理', '/admin/user/logout'), ('RBAC管理', '/admin/user/request-password-reset'), ('RBAC管理', '/admin/user/reset-password'), ('RBAC管理', '/admin/user/signup'), ('RBAC管理', '/admin/user/view'), ('管理员', '/announcements/*'), ('管理员', '/bonus/*'), ('管理员', '/bonus/index'), ('管理员', '/branner/*'), ('管理员', '/count/*'), ('管理员', '/count/index'), ('RBAC管理', '/debug/*'), ('RBAC管理', '/debug/default/*'), ('RBAC管理', '/debug/default/db-explain'), ('RBAC管理', '/debug/default/download-mail'), ('RBAC管理', '/debug/default/index'), ('RBAC管理', '/debug/default/toolbar'), ('RBAC管理', '/debug/default/view'), ('管理员', '/deposit/*'), ('管理员', '/deposit/end'), ('管理员', '/deposit/open'), ('RBAC管理', '/dynagrid/*'), ('RBAC管理', '/dynagrid/settings/*'), ('RBAC管理', '/dynagrid/settings/get-config'), ('管理员', '/fruiter/*'), ('管理员', '/fruiter/index'), ('管理员', '/give/*'), ('管理员', '/give/index'), ('管理员', '/goods/*'), ('RBAC管理', '/gridview/*'), ('RBAC管理', '/gridview/export/*'), ('RBAC管理', '/gridview/export/download'), ('管理员', '/member/*'), ('管理员', '/offline/index'), ('管理员', '/offline/update'), ('管理员', '/outline/*'), ('管理员', '/record/*'), ('财务员', '/record/*'), ('管理员', '/record/delete'), ('管理员', '/tools/*'), ('管理员', 'RBAC管理');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='RBAC权限规则表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资产流水表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='轮播图管理';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值和扣除记录表';

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
  PRIMARY KEY (`id`),
  KEY `index_member_id` (`member_id`),
  KEY `index_distirct` (`district`),
  KEY `index_seat` (`seat`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='座位区域表';

-- ----------------------------
--  Records of `wa_district`
-- ----------------------------
BEGIN;
INSERT INTO `wa_district` VALUES ('1', '1', '1', '1', '1497843433');
COMMIT;

-- ----------------------------
--  Table structure for `wa_district_change_log`
-- ----------------------------
DROP TABLE IF EXISTS `wa_district_change_log`;
CREATE TABLE `wa_district_change_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `old_member_id` int(11) NOT NULL COMMENT '会员id',
  `new_member_id` int(11) NOT NULL COMMENT '新区',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='交换区记录';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='果树管理表';

-- ----------------------------
--  Table structure for `wa_fruiter_img`
-- ----------------------------
DROP TABLE IF EXISTS `wa_fruiter_img`;
CREATE TABLE `wa_fruiter_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruiter_id` int(11) NOT NULL DEFAULT '0' COMMENT '果树ID',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='果树图片表';

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品管理';

-- ----------------------------
--  Table structure for `wa_goods_img`
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods_img`;
CREATE TABLE `wa_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '模型名',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品图片表';

-- ----------------------------
--  Table structure for `wa_member`
-- ----------------------------
DROP TABLE IF EXISTS `wa_member`;
CREATE TABLE `wa_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '会员自增ID',
  `vip_number` int(11) NOT NULL COMMENT '会员卡号',
  `username` char(5) NOT NULL COMMENT '用户名',
  `parent_id` int(10) NOT NULL DEFAULT '1' COMMENT '直推会员id',
  `register_member_id` int(11) NOT NULL COMMENT '注册会员的meber_id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户姓名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '会员密码',
  `mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '电话',
  `deposit_bank` varchar(255) NOT NULL DEFAULT '' COMMENT '开户行',
  `bank_account` varchar(255) NOT NULL DEFAULT '' COMMENT '银行账号',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `last_login_ip` varchar(36) DEFAULT NULL COMMENT '最后登录ip',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` int(10) NOT NULL DEFAULT '1' COMMENT '状态 0:被冻结 1:正常 2:已退网',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 注册时间 入网时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间 退网时间',
  `a_coin` int(11) NOT NULL COMMENT '金果数',
  `b_coin` int(11) NOT NULL COMMENT '金种子数',
  `child_num` int(11) NOT NULL COMMENT '直推数量',
  `out_status` int(10) NOT NULL DEFAULT '0' COMMENT '是否可以退网 0:否 1:是',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_unique_vip_number` (`vip_number`),
  UNIQUE KEY `index_unique_username` (`username`),
  KEY `index_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

-- ----------------------------
--  Records of `wa_member`
-- ----------------------------
BEGIN;
INSERT INTO `wa_member` VALUES ('1', '1', 'wantu', '0', '0', '万岸', '$2y$13$gu094onaVGc9v5Juiz6SD.Tcoxio8IANlYRZjgd7mlFEDjS1OtIVK', '13219890986', '成都银行', '', '', '192.168.2.118', '1497946697', '1', '1497343480', '0', '0', '0', '0', '0');
COMMIT;

-- ----------------------------
--  Table structure for `wa_member_district`
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_district`;
CREATE TABLE `wa_member_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `district` int(11) DEFAULT NULL COMMENT '区id',
  `is_extra` tinyint(1) DEFAULT '0' COMMENT '是否是本身39个会员形成的区；1是 0否',
  PRIMARY KEY (`id`),
  KEY `index_member_id` (`member_id`),
  KEY `index_district` (`district`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员直推区表';

-- ----------------------------
--  Table structure for `wa_member_node`
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_node`;
CREATE TABLE `wa_member_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT 'member_id',
  `above_member_id` int(11) NOT NULL COMMENT '上级的member_id',
  PRIMARY KEY (`id`),
  KEY `index_member_id` (`member_id`),
  KEY `index_abover_member_id` (`above_member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员上级的关系表';

-- ----------------------------
--  Table structure for `wa_member_status`
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_status`;
CREATE TABLE `wa_member_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '状态',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '状态名',
  `value` int(11) NOT NULL DEFAULT '1' COMMENT '状态值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户状态表';

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `wa_menu`
-- ----------------------------
BEGIN;
INSERT INTO `wa_menu` VALUES ('1', '权限管理', null, '/admin/user/index', null, 0x66610d0a, ''), ('2', '管理员列表', '1', '/admin/user/index', null, 0x3c6920636c6173733d22666122202f3e, ''), ('3', '分配', '1', '/admin/assignment/index', null, null, ''), ('4', '角色列表', '1', '/admin/role/index', null, null, 'aa'), ('5', '路由列表', '1', '/admin/route/index', null, null, null), ('6', '规则列表', '1', '/admin/rule/index', null, null, null), ('7', '菜单列表', '1', '/admin/menu/index', null, null, null), ('8', '会员管理', null, '/member/index', null, null, null), ('9', '财务管理', null, '/record/index', null, null, null), ('10', '退网记录', '8', '/outline/index', null, null, null), ('11', '会员信息', '8', '/member/index', null, null, null), ('12', '公告管理', null, '/announcements/index', null, null, null), ('13', '广告管理', null, '/branner/index', null, 0xe5898de58fb0e8bdaee692ade59bbee78987e79a84e7aea1e79086, null), ('14', '赠送记录', '8', '/give/index', null, null, null), ('15', '果树管理', null, '/fruiter/index', null, null, null), ('16', '统计中心', null, '/count/index', null, null, null), ('17', '商品管理', null, '/goods/index', null, null, null), ('18', '充值管理', null, '/deposit/index', null, null, null), ('19', '充值', '18', '/deposit/open', null, null, null), ('20', '扣除', '18', '/deposit/end', null, null, null), ('21', '充值记录', '18', '/deposit/index', null, null, null), ('22', '系谱图', '8', '/member/tree', '0', null, null), ('23', '关网中心', null, '/offline/index', null, null, null);
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
--  Table structure for `wa_offline`
-- ----------------------------
DROP TABLE IF EXISTS `wa_offline`;
CREATE TABLE `wa_offline` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `start` varchar(255) DEFAULT '0' COMMENT '关网开始时间',
  `end` varchar(255) DEFAULT '0' COMMENT '关网结束时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='网站关闭时间表';

-- ----------------------------
--  Records of `wa_offline`
-- ----------------------------
BEGIN;
INSERT INTO `wa_offline` VALUES ('1', '23时59分', '10时00分');
COMMIT;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单管理';

-- ----------------------------
--  Table structure for `wa_outline`
-- ----------------------------
DROP TABLE IF EXISTS `wa_outline`;
CREATE TABLE `wa_outline` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '退网记录自增ID',
  `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '退网会员ID',
  `username` char(6) NOT NULL COMMENT '用户名',
  `name` varchar(60) NOT NULL COMMENT '名字',
  `mobile` varchar(15) DEFAULT NULL COMMENT '手机号',
  `vip_number` int(11) NOT NULL COMMENT 'vip号',
  `status` int(10) NOT NULL DEFAULT '0' COMMENT '确认状态。1已确认',
  `created_at` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `ext_data` varchar(255) DEFAULT NULL COMMENT '扩展',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='退网表';

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='提现记录表';

-- ----------------------------
--  Table structure for `wa_share_log`
-- ----------------------------
DROP TABLE IF EXISTS `wa_share_log`;
CREATE TABLE `wa_share_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer_id` int(11) NOT NULL COMMENT '分享人member_id',
  `member_id` int(11) NOT NULL COMMENT '生成会员id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '生成时间',
  PRIMARY KEY (`id`),
  KEY `index_member_id` (`member_id`),
  KEY `index_referrer_id` (`referrer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='推荐会员记录表';

SET FOREIGN_KEY_CHECKS = 1;
