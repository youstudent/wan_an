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

 Date: 06/02/2017 17:50:33 PM
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
--  Records of `tbl_dynagrid`
-- ----------------------------
BEGIN;
INSERT INTO `tbl_dynagrid` VALUES ('Announcements1_1', null, null, '{\"page\":\"10\",\"theme\":\"panel-primary\",\"keys\":[\"2df35d79\",\"4a16cdfb\",\"25412442\",\"bc1f5a4f\",\"92daa30a\",\"7b6b4a62\",\"e4b2c9d5\"],\"filter\":null,\"sort\":\"\"}'), ('Bonus1_1', null, null, '{\"page\":\"10\",\"theme\":\"simple-default\",\"keys\":[\"2df35d79\",\"92daa30a\",\"ec489d1d\",\"e4b2c9d5\"],\"filter\":\"\",\"sort\":\"\"}'), ('Branner1_1', null, null, '{\"page\":\"10\",\"theme\":\"simple-default\",\"keys\":[\"2df35d79\",\"e04112b1\",\"bf94cac4\",\"4a16cdfb\",\"7b6b4a62\",\"e4b2c9d5\"],\"filter\":null,\"sort\":\"\"}'), ('Record1_1', null, null, '{\"page\":\"10\",\"theme\":\"panel-success\",\"keys\":[\"4718d360\",\"4f4fbae5\",\"6ba23a4c\",\"3ba32401\",\"ba6a198c\",\"ae8d75ba\",\"92daa30a\",\"6043e090\",\"4a16cdfb\",\"7b6b4a62\",\"00000000\"],\"filter\":\"\",\"sort\":\"\"}');
COMMIT;

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
--  Records of `wa_adminuser`
-- ----------------------------
BEGIN;
INSERT INTO `wa_adminuser` VALUES ('1', 'admin', 'JAbY85Q5ozahz1h2hddB-uy5MWfcU-Wy', '$2y$13$vknBz7miG4O.W.mlPBLFE.0vcKiqHvMcz1xKCoZTyTPRVfEBCvvHG', null, 'a@a.com', '10', '1495553266', '1495553266'), ('2', 'admin2', 'Th7mgiQ_6r6tOd-DP0-d11mFRLSNAmzr', '$2y$13$oH/JxoXyJMPwhhczkqqKGeHhHXbPHvZux6GFN6.UF0pKkP9djGS96', null, 'ab@a.com', '10', '1495553280', '1495553280'), ('3', 'admin3', 'zgNtPRVWWrq3LCB6u4yF8BBQ5Q2VmRjc', '$2y$13$rX.ETIDFydbLqhp6DCwVpeBo0nPKf7nXUVK7EFYRCK51u4WCvfdsS', null, 'abc@a.com', '10', '1495559484', '1495559484'), ('4', '11111', 'BC5T5sVJ22aQv9LjLQGdp3BXsqb2nSAC', '$2y$13$Y8hOuOeDhmIoMaATF92qr./0ymrDv/ThdPdXlGLfD4HR.Wwk.F5ni', null, '974967207@qq.com', '10', '1495855205', '1495855205');
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='公告管理';

-- ----------------------------
--  Records of `wa_announcements`
-- ----------------------------
BEGIN;
INSERT INTO `wa_announcements` VALUES ('11', '士大夫士大夫', '<p>士大夫士大夫<br/></p>', '1495771153', '1', null), ('12', '人与人', '<p>水电费</p>', '1495772833', '1', null), ('13', '发发发', '<p>来了<img src=\"/image/20170526/1495778914172901.png\" title=\"1495778914172901.png\" alt=\"user_head_img.png\"/></p>', '1495778941', '1', ' 说得对'), ('14', '这是', '<p>这是是惊天要康师傅</p>', '1495860663', '1', '万岸管理员'), ('15', '11', '<p>111<br/></p>', '1496308743', '1', '11');
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
INSERT INTO `wa_auth_item` VALUES ('/admin/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/revoke', '2', null, null, null, '1495553461', '1495553461'), ('/admin/assignment/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/default/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/default/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/menu/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/permission/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/role/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/assign', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/refresh', '2', null, null, null, '1495553461', '1495553461'), ('/admin/route/remove', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/create', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/update', '2', null, null, null, '1495553461', '1495553461'), ('/admin/rule/view', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/*', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/activate', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/change-password', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/delete', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/index', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/logout', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/request-password-reset', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/reset-password', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/signup', '2', null, null, null, '1495553461', '1495553461'), ('/admin/user/view', '2', null, null, null, '1495553461', '1495553461'), ('/announcements/*', '2', null, null, null, '1495766911', '1495766911'), ('/announcements/create', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/delete', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/index', '2', null, null, null, '1495766973', '1495766973'), ('/announcements/parsing', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/parsing-log', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/sample', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/update', '2', null, null, null, '1495766974', '1495766974'), ('/announcements/view', '2', null, null, null, '1495766974', '1495766974'), ('/bonus/*', '2', null, null, null, '1496223270', '1496223270'), ('/bonus/index', '2', null, null, null, '1496223278', '1496223278'), ('/branner/*', '2', null, null, null, '1495777806', '1495777806'), ('/branner/create', '2', null, null, null, '1495777806', '1495777806'), ('/branner/delete', '2', null, null, null, '1495777806', '1495777806'), ('/branner/index', '2', null, null, null, '1495777805', '1495777805'), ('/branner/parsing', '2', null, null, null, '1495777806', '1495777806'), ('/branner/parsing-log', '2', null, null, null, '1495777806', '1495777806'), ('/branner/sample', '2', null, null, null, '1495777806', '1495777806'), ('/branner/update', '2', null, null, null, '1495777806', '1495777806'), ('/branner/view', '2', null, null, null, '1495777806', '1495777806'), ('/count/*', '2', null, null, null, '1496283400', '1496283400'), ('/count/index', '2', null, null, null, '1496283404', '1496283404'), ('/debug/*', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/*', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/db-explain', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/download-mail', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/index', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/toolbar', '2', null, null, null, '1495553461', '1495553461'), ('/debug/default/view', '2', null, null, null, '1495553461', '1495553461'), ('/deposit/*', '2', null, null, null, '1496371433', '1496371433'), ('/deposit/end', '2', null, null, null, '1496371518', '1496371518'), ('/deposit/increase', '2', null, null, null, '1496371602', '1496371602'), ('/deposit/open', '2', null, null, null, '1496371510', '1496371510'), ('/deposit/reduce', '2', null, null, null, '1496371634', '1496371634'), ('/dynagrid/*', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/settings/*', '2', null, null, null, '1495553461', '1495553461'), ('/dynagrid/settings/get-config', '2', null, null, null, '1495553461', '1495553461'), ('/fruiter/*', '2', null, null, null, '1496215224', '1496215224'), ('/fruiter/index', '2', null, null, null, '1496215311', '1496215311'), ('/fruiter/update', '2', null, null, null, '1496215338', '1496215338'), ('/give/*', '2', null, null, null, '1496212929', '1496212929'), ('/give/index', '2', null, null, null, '1496213018', '1496213018'), ('/goods/*', '2', null, null, null, '1496284298', '1496284298'), ('/goods/create', '2', null, null, null, '1496284350', '1496284350'), ('/goods/delete', '2', null, null, null, '1496284350', '1496284350'), ('/goods/index', '2', null, null, null, '1496284350', '1496284350'), ('/goods/parsing', '2', null, null, null, '1496284350', '1496284350'), ('/goods/parsing-log', '2', null, null, null, '1496284350', '1496284350'), ('/goods/sample', '2', null, null, null, '1496284350', '1496284350'), ('/goods/update', '2', null, null, null, '1496284350', '1496284350'), ('/goods/view', '2', null, null, null, '1496284350', '1496284350'), ('/gridview/*', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/export/*', '2', null, null, null, '1495553461', '1495553461'), ('/gridview/export/download', '2', null, null, null, '1495553461', '1495553461'), ('/member/*', '2', null, null, null, '1495595775', '1495595775'), ('/member/create', '2', null, null, null, '1495595775', '1495595775'), ('/member/delete', '2', null, null, null, '1495595775', '1495595775'), ('/member/index', '2', null, null, null, '1495595775', '1495595775'), ('/member/outline', '2', null, null, null, '1495696926', '1495696926'), ('/member/parsing', '2', null, null, null, '1495595775', '1495595775'), ('/member/parsing-log', '2', null, null, null, '1495595775', '1495595775'), ('/member/sample', '2', null, null, null, '1495595775', '1495595775'), ('/member/update', '2', null, null, null, '1495595775', '1495595775'), ('/member/view', '2', null, null, null, '1495595775', '1495595775'), ('/outline/*', '2', null, null, null, '1495763702', '1495763702'), ('/outline/index', '2', null, null, null, '1495763725', '1495763725'), ('/record/*', '2', null, null, null, '1495626463', '1495626463'), ('/record/delete', '2', null, null, null, '1495680404', '1495680404'), ('/record/index', '2', null, null, null, '1495680393', '1495680393'), ('/record/parsing', '2', null, null, null, '1495680414', '1495680414'), ('/record/parsing-log', '2', null, null, null, '1495680408', '1495680408'), ('/record/sample', '2', null, null, null, '1495680412', '1495680412'), ('/record/update', '2', null, null, null, '1495680401', '1495680401'), ('RBAC管理', '2', null, null, null, '1495557434', '1495557434'), ('管理员', '1', '具有后台管理员的角色', null, null, '1495553016', '1495767296'), ('财务员', '1', '负责审核提现申请的角色', null, null, '1495553314', '1495553314');
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
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/*'), ('RBAC管理', '/admin/assignment/*'), ('RBAC管理', '/admin/assignment/assign'), ('RBAC管理', '/admin/assignment/index'), ('RBAC管理', '/admin/assignment/revoke'), ('RBAC管理', '/admin/assignment/view'), ('RBAC管理', '/admin/default/*'), ('RBAC管理', '/admin/default/index'), ('RBAC管理', '/admin/menu/*'), ('RBAC管理', '/admin/menu/create'), ('RBAC管理', '/admin/menu/delete'), ('RBAC管理', '/admin/menu/index'), ('RBAC管理', '/admin/menu/update'), ('RBAC管理', '/admin/menu/view'), ('RBAC管理', '/admin/permission/*'), ('RBAC管理', '/admin/permission/assign'), ('RBAC管理', '/admin/permission/create'), ('RBAC管理', '/admin/permission/delete'), ('RBAC管理', '/admin/permission/index'), ('RBAC管理', '/admin/permission/remove'), ('RBAC管理', '/admin/permission/update'), ('RBAC管理', '/admin/permission/view'), ('RBAC管理', '/admin/role/*'), ('RBAC管理', '/admin/role/assign'), ('RBAC管理', '/admin/role/create'), ('RBAC管理', '/admin/role/delete'), ('RBAC管理', '/admin/role/index'), ('RBAC管理', '/admin/role/remove'), ('RBAC管理', '/admin/role/update'), ('RBAC管理', '/admin/role/view'), ('RBAC管理', '/admin/route/*'), ('RBAC管理', '/admin/route/assign'), ('RBAC管理', '/admin/route/create'), ('RBAC管理', '/admin/route/index'), ('RBAC管理', '/admin/route/refresh'), ('RBAC管理', '/admin/route/remove'), ('RBAC管理', '/admin/rule/*'), ('RBAC管理', '/admin/rule/create'), ('RBAC管理', '/admin/rule/delete'), ('RBAC管理', '/admin/rule/index'), ('RBAC管理', '/admin/rule/update'), ('RBAC管理', '/admin/rule/view'), ('RBAC管理', '/admin/user/*'), ('RBAC管理', '/admin/user/activate'), ('RBAC管理', '/admin/user/change-password'), ('RBAC管理', '/admin/user/delete'), ('RBAC管理', '/admin/user/index'), ('RBAC管理', '/admin/user/logout'), ('RBAC管理', '/admin/user/request-password-reset'), ('RBAC管理', '/admin/user/reset-password'), ('RBAC管理', '/admin/user/signup'), ('RBAC管理', '/admin/user/view'), ('管理员', '/announcements/*'), ('管理员', '/bonus/*'), ('管理员', '/bonus/index'), ('管理员', '/branner/*'), ('管理员', '/count/*'), ('管理员', '/count/index'), ('RBAC管理', '/debug/*'), ('RBAC管理', '/debug/default/*'), ('RBAC管理', '/debug/default/db-explain'), ('RBAC管理', '/debug/default/download-mail'), ('RBAC管理', '/debug/default/index'), ('RBAC管理', '/debug/default/toolbar'), ('RBAC管理', '/debug/default/view'), ('管理员', '/deposit/*'), ('管理员', '/deposit/end'), ('管理员', '/deposit/open'), ('RBAC管理', '/dynagrid/*'), ('RBAC管理', '/dynagrid/settings/*'), ('RBAC管理', '/dynagrid/settings/get-config'), ('管理员', '/fruiter/*'), ('管理员', '/fruiter/index'), ('管理员', '/give/*'), ('管理员', '/give/index'), ('管理员', '/goods/*'), ('RBAC管理', '/gridview/*'), ('RBAC管理', '/gridview/export/*'), ('RBAC管理', '/gridview/export/download'), ('管理员', '/member/*'), ('管理员', '/outline/*'), ('管理员', '/record/*'), ('管理员', '/record/delete'), ('管理员', 'RBAC管理');
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
--  Table structure for `wa_bonus`
-- ----------------------------
DROP TABLE IF EXISTS `wa_bonus`;
CREATE TABLE `wa_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '奖金记录自增ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `coin_type` int(11) NOT NULL DEFAULT '1' COMMENT '获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送   9:产生5元奖励    11:注册会员人数   ',
  `type` int(11) NOT NULL DEFAULT '0' COMMENT '获得类型 1:绩效 2:分享 3:额外分享 4:提现 5:注册奖金 6:充值 7:扣除 8:赠送',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 获得时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `poundage` int(11) DEFAULT NULL COMMENT '手续费',
  `ext_data` varchar(255) DEFAULT NULL COMMENT '扩展',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COMMENT='资产流水表';

-- ----------------------------
--  Records of `wa_bonus`
-- ----------------------------
BEGIN;
INSERT INTO `wa_bonus` VALUES ('1', '2', '1', '4', '90', '1496389811', '0', '10', null), ('2', '2', '1', '4', '90', '1496389831', '0', '10', null), ('3', '2', '1', '1', '90', '1496389831', '0', null, null), ('4', '2', '1', '2', '90', '1496389831', '0', null, null), ('5', '2', '1', '3', '90', '1496389831', '0', null, null), ('6', '2', '1', '5', '90', '1496389831', '0', null, null), ('8', '2', '1', '8', '100', '1496392943', '0', null, 'a:5:{s:9:\"member_id\";i:2;s:14:\"give_member_id\";s:1:\"4\";s:9:\"give_coin\";s:3:\"100\";s:9:\"coin_type\";s:1:\"1\";s:4:\"type\";i:8;}'), ('9', '2', '2', '8', '5', '1496393086', '0', null, 'a:5:{s:9:\"member_id\";i:2;s:14:\"give_member_id\";s:1:\"4\";s:9:\"give_coin\";s:1:\"5\";s:9:\"coin_type\";s:1:\"2\";s:4:\"type\";i:8;}'), ('10', '2', '2', '8', '5', '1496393123', '0', null, 'a:5:{s:9:\"member_id\";i:2;s:14:\"give_member_id\";s:1:\"3\";s:9:\"give_coin\";s:1:\"5\";s:9:\"coin_type\";s:1:\"2\";s:4:\"type\";i:8;}'), ('11', '2', '1', '5', '180', '1496395649', '0', null, null);
COMMIT;

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
--  Records of `wa_branner`
-- ----------------------------
BEGIN;
INSERT INTO `wa_branner` VALUES ('12', '4', '<p>4</p>', '1', 'uploads/20170527/592938b377311.jpg', 'http://www.wananadmin.cn'), ('19', '爽58285', '<p>舒服舒服沙发斯蒂芬胜多负是否</p>', '1', 'uploads/20170527/59293c1a9f350.jpg', 'http://www.wananadmin.cn'), ('20', '发发发', '<p>这是新的</p>', '1', 'uploads/20170527/592942cd3ca6d.jpg', 'http://www.wananadmin.cn'), ('21', '来了', '<p>是否</p>', '1', 'uploads/20170527/592943b6cc51b.jpg', 'http://www.wananadmin.cn'), ('22', '新的', '<p>今天的奖励</p>', '1', 'uploads/20170531/592e91ef28959.jpg', 'http://www.wananadmin.cn'), ('23', '份饭', '<p>爽肤水</p>', '0', 'uploads/20170601/59300fa99bc7c.jpg', 'http://www.wananadmin.cn');
COMMIT;

-- ----------------------------
--  Table structure for `wa_deposit`
-- ----------------------------
<<<<<<< HEAD
DROP TABLE IF EXISTS `wa_bonus`;
CREATE TABLE `wa_bonus` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '奖金记录自增ID',
  `member_id` int(11) NOT NULL COMMENT '会员ID',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '金果获得类型 1:绩效2:分享3:额外分享4:提现5:注册奖金',
  `coin_amount` int(11) NOT NULL DEFAULT '0' COMMENT '总收入',
  `coin_count` int(11) NOT NULL DEFAULT '0' COMMENT '出入流水账金额',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '流水状态 1:正常 0:禁用',
=======
DROP TABLE IF EXISTS `wa_deposit`;
CREATE TABLE `wa_deposit` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '充值记录自增ID',
  `member_id` int(11) NOT NULL DEFAULT '1' COMMENT '充值会员id',
  `type` int(11) NOT NULL DEFAULT '1' COMMENT '币种 1:金种子 2:金果',
  `operation` int(11) NOT NULL DEFAULT '1' COMMENT '操作类型 1:充值 2:扣除',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '金额',
>>>>>>> 98f2752f864d8aedf1bfcfc891a4db62adf146a3
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 奖金获得时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8 COMMENT='充值和扣除记录表';

-- ----------------------------
--  Records of `wa_deposit`
-- ----------------------------
BEGIN;
INSERT INTO `wa_deposit` VALUES ('34', '3', '1', '1', '1', '0', '0'), ('35', '1', '1', '1', '0', '0', '0'), ('36', '3', '1', '1', '1', '0', '0'), ('37', '1', '1', '1', '0', '0', '0'), ('38', '1', '1', '1', '0', '0', '0'), ('39', '1', '1', '1', '0', '0', '0'), ('40', '3', '2', '1', '1', '0', '0'), ('41', '3', '1', '2', '1', '0', '0'), ('42', '3', '1', '2', '1', '0', '0'), ('43', '3', '1', '2', '1', '0', '0'), ('44', '3', '1', '2', '1', '0', '0'), ('45', '3', '1', '1', '11', '0', '0'), ('46', '3', '1', '2', '1', '0', '0'), ('47', '3', '2', '2', '1', '0', '0');
COMMIT;

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
<<<<<<< HEAD
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='金果流水表';
=======
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='座位区域表';
>>>>>>> 98f2752f864d8aedf1bfcfc891a4db62adf146a3

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
--  Records of `wa_fruiter`
-- ----------------------------
BEGIN;
INSERT INTO `wa_fruiter` VALUES ('1', '2', '111', '苹果树', null, '', '1400000000', '1');
COMMIT;

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
--  Records of `wa_fruiter_img`
-- ----------------------------
BEGIN;
INSERT INTO `wa_fruiter_img` VALUES ('58', '1', '/public/upload/fruiter_imgs/2017-06-01/283f09647247b7f207726a53d104196ecb68fbfa.jpg');
COMMIT;

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
) ENGINE=MyISAM AUTO_INCREMENT=1496370660 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `wa_give`
-- ----------------------------
BEGIN;
INSERT INTO `wa_give` VALUES ('1', '2', '3', '1', '1496370650', '10000'), ('2', '2', '4', '2', '1496370650', '111100'), ('3', '2', '3', '1', '1496370650', '10000'), ('4', '2', '3', '1', '1496370650', '44'), ('1496370651', '2', '2', null, '1496387944', '12341231'), ('6', '2', '3', '1', '1496370650', '55'), ('7', '2', '3', '2', '1496370650', '55'), ('8', '2', '3', '2', '1496370650', '7777'), ('9', '2', '3', '2', '1496370650', '7777'), ('1496370652', '2', '3', null, '1496387953', '12341231'), ('1496370653', '2', '4', null, '1496392613', '1231231'), ('1496370654', '2', '3', null, '1496392837', '5000'), ('1496370655', '2', '4', '1', '1496392943', '100'), ('1496370656', '2', '4', null, '1496392982', '222222'), ('1496370657', '2', '4', '2', '1496393086', '5'), ('1496370658', '2', '3', '2', '1496393123', '5'), ('1496370659', '2', '4', null, '1496395670', '800');
COMMIT;

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
--  Records of `wa_goods`
-- ----------------------------
BEGIN;
INSERT INTO `wa_goods` VALUES ('1', '222222', null, '11.00', '222222');
COMMIT;

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
--  Records of `wa_goods_img`
-- ----------------------------
BEGIN;
INSERT INTO `wa_goods_img` VALUES ('1', '1', '/public/upload/goods_imgs/2017-06-01/d266587cb2c6a47c421a8abb2b88139e26a053da.jpg'), ('2', '1', '/public/upload/goods_imgs/2017-06-01/d469fb71c8527fa90f03f1e058c243f3e8723e64.jpg'), ('3', '2', '/public/upload/goods_imgs/2017-06-01/c0bcc0cc4d9b8915c369e44b73d73930380c8b8c.jpg'), ('4', '2', '/public/upload/goods_imgs/2017-06-01/4c357e9dfde7c05df327d854989b48caaf439ae0.jpg'), ('5', '0', '/public/upload/goods_imgs/2017-06-01/276a8ebc1c8f8842785a5bce5478bc685fea9666.jpg'), ('6', '0', '/public/upload/goods_imgs/2017-06-01/4e65757cbf8faaa0c41eb3bf11b91cca71afe5ca.jpg'), ('7', '0', '/public/upload/goods_imgs/2017-06-01/485719b363484305a1b413587a4548119f5ca535.jpg');
COMMIT;

-- ----------------------------
--  Table structure for `wa_member`
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
  `gross_bonus` int(10) NOT NULL DEFAULT '0' COMMENT '总提成',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `status` int(10) DEFAULT '1' COMMENT '状态 0:被冻结 1:正常 2:已退网',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间 注册时间 入网时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间 退网时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='会员信息表';
<<<<<<< HEAD
=======

-- ----------------------------
--  Records of `wa_member`
-- ----------------------------
BEGIN;
INSERT INTO `wa_member` VALUES ('2', '0', '1', 'member1', '$2y$13$QgKZ43ECSZJhxJ0voS8zl.2.0RbbIOX/1yrYWDj6nUj7qjmzJ/3nq', '13219890986', '成都市建设银行', '6228480028171687587', '四川成都', '1', '20', '99800', '999999345', '11111111', '1111', '0', '1', '0', '0'), ('3', '0', '2', '服不服', '$2y$13$9COAzx0eDwacesZCPf.Qpeug14rxQyGzHU3SIqlEytdAVb/z0gqE2', '119', '成都成都银行', '622848', '成都市武侯区', '1', '20', '10', '15', '222222', '2222', '1400000000', '1', '1400000000', '1496210435'), ('4', '0', '2', '不服', '$2y$13$etdGeJImtdLiTcFlJa36juNL6pL/e3/RHy1KtXn2B9XSOCecmwjOG', '110', '成都工商银行', '123456', '成都市武侯区', '11', '12', '110', '15', '22222222', '222', '0', '1', '0', '0');
COMMIT;
>>>>>>> 98f2752f864d8aedf1bfcfc891a4db62adf146a3

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
--  Records of `wa_member_status`
-- ----------------------------
BEGIN;
INSERT INTO `wa_member_status` VALUES ('1', '正常', '1'), ('2', '冻结', '0'), ('3', '已退网', '2');
COMMIT;

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
--  Records of `wa_menu`
-- ----------------------------
BEGIN;
INSERT INTO `wa_menu` VALUES ('1', '权限管理', null, '/admin/user/index', null, 0x66610d0a, ''), ('2', '管理员列表', '1', '/admin/user/index', null, 0x3c6920636c6173733d22666122202f3e, ''), ('3', '分配', '1', '/admin/assignment/index', null, null, ''), ('4', '角色列表', '1', '/admin/role/index', null, null, 'aa'), ('5', '路由列表', '1', '/admin/route/index', null, null, null), ('6', '规则列表', '1', '/admin/rule/index', null, null, null), ('7', '菜单列表', '1', '/admin/menu/index', null, null, null), ('8', '会员管理', null, '/member/index', null, null, null), ('9', '财务管理', null, '/record/index', null, null, null), ('10', '退网记录', '8', '/outline/index', null, null, null), ('11', '会员信息', '8', '/member/index', null, null, null), ('12', '公告管理', null, '/announcements/index', null, null, null), ('13', '广告管理', null, '/branner/index', null, 0xe5898de58fb0e8bdaee692ade59bbee78987e79a84e7aea1e79086, null), ('14', '赠送记录', '8', '/give/index', null, null, null), ('15', '果树管理', null, '/fruiter/index', null, null, null), ('16', '统计中心', null, '/count/index', null, null, null), ('17', '商品管理', null, '/goods/index', null, null, null), ('18', '充值管理', null, '/deposit/open', null, null, null), ('19', '充值', '18', '/deposit/open', null, null, null), ('20', '扣除', '18', '/deposit/end', null, null, null);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='订单管理';

-- ----------------------------
--  Records of `wa_order`
-- ----------------------------
BEGIN;
INSERT INTO `wa_order` VALUES ('1', '123', '4', '栗子数', '111.00', '1', '1');
COMMIT;

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
--  Records of `wa_outline`
-- ----------------------------
BEGIN;
INSERT INTO `wa_outline` VALUES ('1', '4', '1', '1400000000', '1400000000'), ('2', '4', '1', '1400000000', '1400000000'), ('3', '4', '1', '1400000000', '1400000000'), ('4', '4', '1', '1400000000', '1400000000'), ('5', '4', '1', '1400000000', '1400000000'), ('6', '4', '1', '1400000000', '1400000000'), ('7', '4', '1', '1400000000', '1400000000'), ('8', '4', '1', '1400000000', '1400000000'), ('9', '4', '1', '1400000000', '1400000000'), ('10', '4', '1', '1400000000', '1400000000'), ('11', '4', '1', '1400000000', '1400000000');
COMMIT;

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
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='提现记录表';

-- ----------------------------
--  Records of `wa_record`
-- ----------------------------
BEGIN;
INSERT INTO `wa_record` VALUES ('8', '2', '1496318186', '180', '1496389803', '2', '20', '200', '2017-06-03'), ('9', '2', '1496318186', '180', null, '1', '20', '200', '2017-06-03'), ('10', '2', '1496318186', '180', null, '2', '20', '200', '2017-06-03'), ('11', '2', '1496319051', '90', '1496389811', '1', '10', '100', '2017-06-01'), ('12', '2', '1496319231', '90', '1496389831', '1', '10', '100', '2017-06-01'), ('14', '2', '1496369454', '90', null, '0', '10', '100', '2017-06-02'), ('15', '2', '1496370111', '90', null, '0', '10', '100', '2017-06-02'), ('16', '2', '1496370111', '90', null, '0', '10', '100', '2017-06-02'), ('17', '2', '1496370111', '90', null, '0', '10', '100', '2017-06-02'), ('18', '2', '1496370126', '900', null, '0', '100', '1000', '2017-06-02'), ('19', '2', '1496370138', '900', null, '0', '100', '1000', '2017-06-02'), ('20', '2', '1496370141', '900', null, '0', '100', '1000', '2017-06-02'), ('21', '2', '1496370141', '900', null, '0', '100', '1000', '2017-06-02'), ('22', '2', '1496370149', '90', null, '0', '10', '100', '2017-06-02'), ('23', '2', '1496370150', '90', null, '0', '10', '100', '2017-06-02'), ('24', '2', '1496370151', '90', null, '0', '10', '100', '2017-06-02'), ('25', '2', '1496370188', '90', null, '0', '10', '100', '2017-06-02'), ('26', '2', '1496370276', '90', null, '0', '10', '100', '2017-06-02'), ('27', '2', '1496370650', '90', null, '0', '10', '100', '2017-06-02'), ('28', '2', '1496370664', '90', null, '0', '10', '100', '2017-06-02'), ('29', '2', '1496370666', '90', null, '0', '10', '100', '2017-06-02'), ('30', '2', '1496371329', '90', null, '0', '10', '100', '2017-06-02'), ('31', '2', '1496371347', '810', null, '0', '90', '900', '2017-06-02'), ('32', '2', '1496371374', '8100', null, '0', '900', '9000', '2017-06-02'), ('33', '2', '1496371412', '810', null, '0', '90', '900', '2017-06-02'), ('34', '2', '1496371492', '3600', null, '0', '400', '4000', '2017-06-02'), ('35', '2', '1496371525', '90', null, '0', '10', '100', '2017-06-02'), ('36', '2', '1496371538', '810', null, '0', '90', '900', '2017-06-02'), ('37', '2', '1496371740', '1800', null, '0', '200', '2000', '2017-06-02'), ('38', '2', '1496372064', '90', null, '0', '10', '100', '2017-06-02'), ('39', '2', '1496373683', '90', null, '0', '10', '100', '2017-06-02'), ('40', '2', '1496373705', '180', null, '0', '20', '200', '2017-06-02'), ('41', '2', '1496374768', '180', null, '0', '20', '200', '2017-06-02'), ('42', '2', '1496374835', '180', null, '0', '20', '200', '2017-06-02'), ('43', '2', '1496374847', '90', null, '0', '10', '100', '2017-06-02'), ('44', '2', '1496387100', '900', null, '0', '100', '1000', '2017-06-02'), ('45', '2', '1496395649', '180', null, '0', '20', '200', '2017-06-02');
COMMIT;

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

-- ----------------------------
--  Records of `wa_user`
-- ----------------------------
BEGIN;
INSERT INTO `wa_user` VALUES ('1', 'admin', 'JAbY85Q5ozahz1h2hddB-uy5MWfcU-Wy', '$2y$13$vknBz7miG4O.W.mlPBLFE.0vcKiqHvMcz1xKCoZTyTPRVfEBCvvHG', null, '', '10', '0', '0');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
