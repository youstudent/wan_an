/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : wan_an

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-06-12 17:19:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for log_upload
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
-- Records of log_upload
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_dynagrid
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
-- Records of tbl_dynagrid
-- ----------------------------

-- ----------------------------
-- Table structure for tbl_dynagrid_dtl
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
-- Records of tbl_dynagrid_dtl
-- ----------------------------

-- ----------------------------
-- Table structure for wa_adminuser
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of wa_adminuser
-- ----------------------------
INSERT INTO `wa_adminuser` VALUES ('1', 'admin', 'JAbY85Q5ozahz1h2hddB-uy5MWfcU-Wy', '$2y$13$vknBz7miG4O.W.mlPBLFE.0vcKiqHvMcz1xKCoZTyTPRVfEBCvvHG', null, 'a@a.com', '10', '1495553266', '1495553266');

-- ----------------------------
-- Table structure for wa_announcements
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
-- Records of wa_announcements
-- ----------------------------

-- ----------------------------
-- Table structure for wa_auth_assignment
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
-- Records of wa_auth_assignment
-- ----------------------------
INSERT INTO `wa_auth_assignment` VALUES ('RBAC管理', '1', '1495557448');
INSERT INTO `wa_auth_assignment` VALUES ('管理员', '1', '1495555351');

-- ----------------------------
-- Table structure for wa_auth_item
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
-- Records of wa_auth_item
-- ----------------------------
INSERT INTO `wa_auth_item` VALUES ('/admin/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/activate', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/change-password', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/delete', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/logout', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/request-password-reset', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/reset-password', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/signup', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/admin/user/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/announcements/*', '2', null, null, null, '1495766911', '1495766911');
INSERT INTO `wa_auth_item` VALUES ('/announcements/create', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/announcements/delete', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/announcements/index', '2', null, null, null, '1495766973', '1495766973');
INSERT INTO `wa_auth_item` VALUES ('/announcements/parsing', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/announcements/parsing-log', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/announcements/sample', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/announcements/update', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/announcements/view', '2', null, null, null, '1495766974', '1495766974');
INSERT INTO `wa_auth_item` VALUES ('/bonus/*', '2', null, null, null, '1496223270', '1496223270');
INSERT INTO `wa_auth_item` VALUES ('/bonus/index', '2', null, null, null, '1496223278', '1496223278');
INSERT INTO `wa_auth_item` VALUES ('/branner/*', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/create', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/delete', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/index', '2', null, null, null, '1495777805', '1495777805');
INSERT INTO `wa_auth_item` VALUES ('/branner/parsing', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/parsing-log', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/sample', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/update', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/branner/view', '2', null, null, null, '1495777806', '1495777806');
INSERT INTO `wa_auth_item` VALUES ('/count/*', '2', null, null, null, '1496283400', '1496283400');
INSERT INTO `wa_auth_item` VALUES ('/count/index', '2', null, null, null, '1496283404', '1496283404');
INSERT INTO `wa_auth_item` VALUES ('/debug/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/debug/default/db-explain', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/debug/default/toolbar', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/deposit/*', '2', null, null, null, '1496371433', '1496371433');
INSERT INTO `wa_auth_item` VALUES ('/deposit/end', '2', null, null, null, '1496371518', '1496371518');
INSERT INTO `wa_auth_item` VALUES ('/deposit/increase', '2', null, null, null, '1496371602', '1496371602');
INSERT INTO `wa_auth_item` VALUES ('/deposit/open', '2', null, null, null, '1496371510', '1496371510');
INSERT INTO `wa_auth_item` VALUES ('/deposit/reduce', '2', null, null, null, '1496371634', '1496371634');
INSERT INTO `wa_auth_item` VALUES ('/dynagrid/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/dynagrid/settings/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/dynagrid/settings/get-config', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/fruiter/*', '2', null, null, null, '1496215224', '1496215224');
INSERT INTO `wa_auth_item` VALUES ('/fruiter/index', '2', null, null, null, '1496215311', '1496215311');
INSERT INTO `wa_auth_item` VALUES ('/fruiter/update', '2', null, null, null, '1496215338', '1496215338');
INSERT INTO `wa_auth_item` VALUES ('/give/*', '2', null, null, null, '1496212929', '1496212929');
INSERT INTO `wa_auth_item` VALUES ('/give/index', '2', null, null, null, '1496213018', '1496213018');
INSERT INTO `wa_auth_item` VALUES ('/goods/*', '2', null, null, null, '1496284298', '1496284298');
INSERT INTO `wa_auth_item` VALUES ('/goods/create', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/delete', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/index', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/parsing', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/parsing-log', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/sample', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/update', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/goods/view', '2', null, null, null, '1496284350', '1496284350');
INSERT INTO `wa_auth_item` VALUES ('/gridview/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/gridview/export/*', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/gridview/export/download', '2', null, null, null, '1495553461', '1495553461');
INSERT INTO `wa_auth_item` VALUES ('/member/*', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/create', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/delete', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/index', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/outline', '2', null, null, null, '1495696926', '1495696926');
INSERT INTO `wa_auth_item` VALUES ('/member/parsing', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/parsing-log', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/sample', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/update', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/member/view', '2', null, null, null, '1495595775', '1495595775');
INSERT INTO `wa_auth_item` VALUES ('/outline/*', '2', null, null, null, '1495763702', '1495763702');
INSERT INTO `wa_auth_item` VALUES ('/outline/index', '2', null, null, null, '1495763725', '1495763725');
INSERT INTO `wa_auth_item` VALUES ('/record/*', '2', null, null, null, '1495626463', '1495626463');
INSERT INTO `wa_auth_item` VALUES ('/record/delete', '2', null, null, null, '1495680404', '1495680404');
INSERT INTO `wa_auth_item` VALUES ('/record/index', '2', null, null, null, '1495680393', '1495680393');
INSERT INTO `wa_auth_item` VALUES ('/record/parsing', '2', null, null, null, '1495680414', '1495680414');
INSERT INTO `wa_auth_item` VALUES ('/record/parsing-log', '2', null, null, null, '1495680408', '1495680408');
INSERT INTO `wa_auth_item` VALUES ('/record/sample', '2', null, null, null, '1495680412', '1495680412');
INSERT INTO `wa_auth_item` VALUES ('/record/update', '2', null, null, null, '1495680401', '1495680401');
INSERT INTO `wa_auth_item` VALUES ('RBAC管理', '2', null, null, null, '1495557434', '1495557434');
INSERT INTO `wa_auth_item` VALUES ('管理员', '1', '具有后台管理员的角色', null, null, '1495553016', '1495767296');
INSERT INTO `wa_auth_item` VALUES ('财务员', '1', '负责审核提现申请的角色', null, null, '1495553314', '1495553314');

-- ----------------------------
-- Table structure for wa_auth_item_child
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
-- Records of wa_auth_item_child
-- ----------------------------
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/assignment/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/assignment/assign');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/assignment/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/assignment/revoke');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/assignment/view');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/default/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/default/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/menu/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/menu/create');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/menu/delete');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/menu/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/menu/update');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/menu/view');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/assign');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/create');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/delete');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/remove');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/update');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/permission/view');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/assign');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/create');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/delete');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/remove');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/update');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/role/view');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/route/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/route/assign');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/route/create');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/route/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/route/refresh');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/route/remove');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/rule/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/rule/create');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/rule/delete');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/rule/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/rule/update');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/rule/view');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/activate');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/change-password');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/delete');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/logout');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/request-password-reset');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/reset-password');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/signup');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/admin/user/view');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/announcements/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/bonus/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/bonus/index');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/branner/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/count/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/count/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/default/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/default/db-explain');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/default/download-mail');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/default/index');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/default/toolbar');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/debug/default/view');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/deposit/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/deposit/end');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/deposit/open');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/dynagrid/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/dynagrid/settings/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/dynagrid/settings/get-config');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/fruiter/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/fruiter/index');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/give/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/give/index');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/goods/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/gridview/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/gridview/export/*');
INSERT INTO `wa_auth_item_child` VALUES ('RBAC管理', '/gridview/export/download');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/member/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/outline/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/record/*');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', '/record/delete');
INSERT INTO `wa_auth_item_child` VALUES ('管理员', 'RBAC管理');

-- ----------------------------
-- Table structure for wa_auth_rule
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
-- Records of wa_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for wa_bonus
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
) ENGINE=InnoDB AUTO_INCREMENT=176 DEFAULT CHARSET=utf8 COMMENT='资产流水表';

-- ----------------------------
-- Records of wa_bonus
-- ----------------------------
INSERT INTO `wa_bonus` VALUES ('1', '1', '5', '1', '5', '1497258492', '1497258492', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('2', '1', '2', '10', '900', '1497258492', '1497258492', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('3', '1', '1', '2', '300', '1497258493', '1497258493', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('4', '1', '5', '1', '5', '1497258495', '1497258495', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('5', '1', '2', '10', '900', '1497258495', '1497258495', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('6', '1', '1', '2', '300', '1497258496', '1497258496', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('7', '1', '5', '1', '5', '1497258497', '1497258497', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('8', '1', '2', '10', '900', '1497258497', '1497258497', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('9', '1', '1', '2', '300', '1497258498', '1497258498', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('10', '1', '5', '1', '5', '1497258499', '1497258499', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('11', '1', '2', '10', '900', '1497258499', '1497258499', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('12', '2', '1', '1', '150', '1497258499', '1497258499', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('13', '1', '1', '1', '150', '1497258499', '1497258499', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('14', '1', '5', '1', '5', '1497258500', '1497258500', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('15', '1', '2', '10', '900', '1497258500', '1497258500', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('16', '3', '1', '1', '150', '1497258501', '1497258501', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('17', '1', '1', '1', '150', '1497258501', '1497258501', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('18', '1', '5', '1', '5', '1497258502', '1497258502', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('19', '1', '2', '10', '900', '1497258502', '1497258502', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('20', '4', '1', '1', '150', '1497258503', '1497258503', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('21', '1', '1', '1', '150', '1497258503', '1497258503', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('22', '1', '5', '1', '5', '1497258504', '1497258504', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('23', '1', '2', '10', '900', '1497258504', '1497258504', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('24', '2', '1', '1', '150', '1497258504', '1497258504', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('25', '1', '1', '1', '150', '1497258504', '1497258504', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('26', '1', '5', '1', '5', '1497258505', '1497258505', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('27', '1', '2', '10', '900', '1497258505', '1497258505', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('28', '3', '1', '1', '150', '1497258506', '1497258506', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('29', '1', '1', '1', '150', '1497258506', '1497258506', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('30', '1', '5', '1', '5', '1497258507', '1497258507', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('31', '1', '2', '10', '900', '1497258507', '1497258507', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('32', '4', '1', '1', '150', '1497258507', '1497258507', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('33', '1', '1', '1', '150', '1497258507', '1497258507', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('34', '1', '5', '1', '5', '1497258508', '1497258508', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('35', '1', '2', '10', '900', '1497258508', '1497258508', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('36', '2', '1', '1', '150', '1497258509', '1497258509', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('37', '1', '1', '1', '150', '1497258509', '1497258509', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('38', '1', '5', '1', '5', '1497258510', '1497258510', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('39', '1', '2', '10', '900', '1497258510', '1497258510', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('40', '3', '1', '1', '150', '1497258511', '1497258511', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('41', '1', '1', '1', '150', '1497258511', '1497258511', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('42', '1', '5', '1', '5', '1497258511', '1497258511', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('43', '1', '2', '10', '900', '1497258511', '1497258511', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('44', '4', '1', '1', '150', '1497258512', '1497258512', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('45', '1', '1', '1', '150', '1497258512', '1497258512', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('46', '1', '5', '1', '5', '1497258513', '1497258513', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('47', '1', '2', '10', '900', '1497258513', '1497258513', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('48', '5', '1', '1', '150', '1497258514', '1497258514', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('49', '2', '1', '1', '150', '1497258514', '1497258514', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('50', '1', '1', '1', '150', '1497258514', '1497258514', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('51', '1', '5', '1', '5', '1497258514', '1497258514', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('52', '1', '2', '10', '900', '1497258514', '1497258514', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('53', '6', '1', '1', '150', '1497258515', '1497258515', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('54', '3', '1', '1', '150', '1497258515', '1497258515', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('55', '1', '1', '1', '150', '1497258515', '1497258515', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('56', '1', '5', '1', '5', '1497258516', '1497258516', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('57', '1', '2', '10', '900', '1497258516', '1497258516', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('58', '7', '1', '1', '150', '1497258517', '1497258517', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('59', '4', '1', '1', '150', '1497258517', '1497258517', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('60', '1', '1', '1', '150', '1497258517', '1497258517', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('61', '1', '5', '1', '5', '1497258517', '1497258517', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('62', '1', '2', '10', '900', '1497258517', '1497258517', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('63', '8', '1', '1', '150', '1497258518', '1497258518', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('64', '2', '1', '1', '150', '1497258518', '1497258518', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('65', '1', '1', '1', '150', '1497258518', '1497258518', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('66', '1', '5', '1', '5', '1497258519', '1497258519', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('67', '1', '2', '10', '900', '1497258519', '1497258519', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('68', '9', '1', '1', '150', '1497258520', '1497258520', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('69', '3', '1', '1', '150', '1497258520', '1497258520', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('70', '1', '1', '1', '150', '1497258520', '1497258520', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('71', '1', '5', '1', '5', '1497258521', '1497258521', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('72', '1', '2', '10', '900', '1497258521', '1497258521', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('73', '10', '1', '1', '150', '1497258521', '1497258521', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('74', '4', '1', '1', '150', '1497258521', '1497258521', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('75', '1', '1', '1', '150', '1497258521', '1497258521', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('76', '1', '5', '1', '5', '1497258522', '1497258522', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('77', '1', '2', '10', '900', '1497258522', '1497258522', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('78', '11', '1', '1', '150', '1497258523', '1497258523', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('79', '2', '1', '1', '150', '1497258523', '1497258523', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('80', '1', '1', '1', '150', '1497258523', '1497258523', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('81', '1', '5', '1', '5', '1497258524', '1497258524', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('82', '1', '2', '10', '900', '1497258524', '1497258524', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('83', '12', '1', '1', '150', '1497258525', '1497258525', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('84', '3', '1', '1', '150', '1497258525', '1497258525', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('85', '1', '1', '1', '150', '1497258525', '1497258525', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('86', '1', '5', '1', '5', '1497258526', '1497258526', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('87', '1', '2', '10', '900', '1497258526', '1497258526', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('88', '13', '1', '1', '150', '1497258527', '1497258527', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('89', '4', '1', '1', '150', '1497258527', '1497258527', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('90', '1', '1', '1', '150', '1497258527', '1497258527', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('91', '1', '5', '1', '5', '1497258527', '1497258527', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('92', '1', '2', '10', '900', '1497258527', '1497258527', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('93', '5', '1', '1', '150', '1497258528', '1497258528', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('94', '2', '1', '1', '150', '1497258528', '1497258528', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('95', '1', '1', '1', '150', '1497258528', '1497258528', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('96', '1', '5', '1', '5', '1497258529', '1497258529', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('97', '1', '2', '10', '900', '1497258529', '1497258529', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('98', '6', '1', '1', '150', '1497258530', '1497258530', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('99', '3', '1', '1', '150', '1497258530', '1497258530', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('100', '1', '1', '1', '150', '1497258530', '1497258530', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('101', '1', '5', '1', '5', '1497258531', '1497258531', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('102', '1', '2', '10', '900', '1497258531', '1497258531', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('103', '7', '1', '1', '150', '1497258531', '1497258531', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('104', '4', '1', '1', '150', '1497258531', '1497258531', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('105', '1', '1', '1', '150', '1497258532', '1497258532', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('106', '1', '5', '1', '5', '1497258532', '1497258532', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('107', '1', '2', '10', '900', '1497258532', '1497258532', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('108', '8', '1', '1', '150', '1497258533', '1497258533', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('109', '2', '1', '1', '150', '1497258533', '1497258533', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('110', '1', '1', '1', '150', '1497258533', '1497258533', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('111', '1', '5', '1', '5', '1497258534', '1497258534', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('112', '1', '2', '10', '900', '1497258534', '1497258534', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('113', '9', '1', '1', '150', '1497258535', '1497258535', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('114', '3', '1', '1', '150', '1497258535', '1497258535', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('115', '1', '1', '1', '150', '1497258535', '1497258535', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('116', '1', '5', '1', '5', '1497258535', '1497258535', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('117', '1', '2', '10', '900', '1497258535', '1497258535', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('118', '10', '1', '1', '150', '1497258536', '1497258536', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('119', '4', '1', '1', '150', '1497258536', '1497258536', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('120', '1', '1', '1', '150', '1497258536', '1497258536', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('121', '1', '5', '1', '5', '1497258537', '1497258537', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('122', '1', '2', '10', '900', '1497258537', '1497258537', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('123', '11', '1', '1', '150', '1497258538', '1497258538', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('124', '2', '1', '1', '150', '1497258538', '1497258538', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('125', '1', '1', '1', '150', '1497258538', '1497258538', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('126', '1', '5', '1', '5', '1497258539', '1497258539', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('127', '1', '2', '10', '900', '1497258539', '1497258539', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('128', '12', '1', '1', '150', '1497258539', '1497258539', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('129', '3', '1', '1', '150', '1497258539', '1497258539', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('130', '1', '1', '1', '150', '1497258539', '1497258539', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('131', '1', '5', '1', '5', '1497258540', '1497258540', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('132', '1', '2', '10', '900', '1497258540', '1497258540', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('133', '13', '1', '1', '150', '1497258541', '1497258541', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('134', '4', '1', '1', '150', '1497258541', '1497258541', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('135', '1', '1', '1', '150', '1497258541', '1497258541', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('136', '1', '5', '1', '5', '1497258542', '1497258542', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('137', '1', '2', '10', '900', '1497258542', '1497258542', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('138', '5', '1', '1', '150', '1497258543', '1497258543', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('139', '2', '1', '1', '150', '1497258543', '1497258543', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('140', '1', '1', '1', '150', '1497258543', '1497258543', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('141', '1', '5', '1', '5', '1497258543', '1497258543', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('142', '1', '2', '10', '900', '1497258543', '1497258543', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('143', '6', '1', '1', '150', '1497258544', '1497258544', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('144', '3', '1', '1', '150', '1497258544', '1497258544', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('145', '1', '1', '1', '150', '1497258544', '1497258544', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('146', '1', '5', '1', '5', '1497258545', '1497258545', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('147', '1', '2', '10', '900', '1497258545', '1497258545', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('148', '7', '1', '1', '150', '1497258546', '1497258546', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('149', '4', '1', '1', '150', '1497258546', '1497258546', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('150', '1', '1', '1', '150', '1497258546', '1497258546', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('151', '1', '5', '1', '5', '1497258547', '1497258547', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('152', '1', '2', '10', '900', '1497258547', '1497258547', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('153', '8', '1', '1', '150', '1497258547', '1497258547', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('154', '2', '1', '1', '150', '1497258547', '1497258547', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('155', '1', '1', '1', '150', '1497258548', '1497258548', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('156', '1', '5', '1', '5', '1497258548', '1497258548', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('157', '1', '2', '10', '900', '1497258548', '1497258548', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('158', '9', '1', '1', '150', '1497258549', '1497258549', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('159', '3', '1', '1', '150', '1497258549', '1497258549', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('160', '1', '1', '1', '150', '1497258549', '1497258549', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('161', '1', '5', '1', '5', '1497258550', '1497258550', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('162', '1', '2', '10', '900', '1497258550', '1497258550', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('163', '10', '1', '1', '150', '1497258551', '1497258551', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('164', '4', '1', '1', '150', '1497258551', '1497258551', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('165', '1', '1', '1', '150', '1497258551', '1497258551', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('166', '1', '5', '1', '5', '1497258551', '1497258551', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('167', '1', '2', '10', '900', '1497258552', '1497258552', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('168', '11', '1', '1', '150', '1497258552', '1497258552', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('169', '2', '1', '1', '150', '1497258552', '1497258552', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('170', '1', '1', '1', '150', '1497258552', '1497258552', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('171', '1', '5', '1', '5', '1497258553', '1497258553', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('172', '1', '2', '10', '900', '1497258553', '1497258553', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('173', '12', '1', '1', '150', '1497258554', '1497258554', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('174', '3', '1', '1', '150', '1497258554', '1497258554', '0', '[]');
INSERT INTO `wa_bonus` VALUES ('175', '1', '1', '1', '150', '1497258554', '1497258554', '0', '[]');

-- ----------------------------
-- Table structure for wa_branner
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
-- Records of wa_branner
-- ----------------------------

-- ----------------------------
-- Table structure for wa_deposit
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
-- Records of wa_deposit
-- ----------------------------

-- ----------------------------
-- Table structure for wa_district
-- ----------------------------
DROP TABLE IF EXISTS `wa_district`;
CREATE TABLE `wa_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL COMMENT '会员id',
  `district` int(11) NOT NULL COMMENT '区域id',
  `seat` int(11) DEFAULT NULL COMMENT '座位id',
  `created_at` int(11) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COMMENT='座位区域表';

-- ----------------------------
-- Records of wa_district
-- ----------------------------
INSERT INTO `wa_district` VALUES ('1', '1', '1', '1', '1496630244');
INSERT INTO `wa_district` VALUES ('2', '2', '2', '1', '1497258493');
INSERT INTO `wa_district` VALUES ('3', '2', '1', '2', '1497258493');
INSERT INTO `wa_district` VALUES ('4', '3', '3', '1', '1497258496');
INSERT INTO `wa_district` VALUES ('5', '3', '1', '3', '1497258496');
INSERT INTO `wa_district` VALUES ('6', '4', '4', '1', '1497258498');
INSERT INTO `wa_district` VALUES ('7', '4', '1', '4', '1497258498');
INSERT INTO `wa_district` VALUES ('8', '5', '5', '1', '1497258499');
INSERT INTO `wa_district` VALUES ('9', '5', '2', '2', '1497258499');
INSERT INTO `wa_district` VALUES ('10', '5', '1', '5', '1497258499');
INSERT INTO `wa_district` VALUES ('11', '6', '6', '1', '1497258501');
INSERT INTO `wa_district` VALUES ('12', '6', '3', '2', '1497258501');
INSERT INTO `wa_district` VALUES ('13', '6', '1', '6', '1497258501');
INSERT INTO `wa_district` VALUES ('14', '7', '7', '1', '1497258503');
INSERT INTO `wa_district` VALUES ('15', '7', '4', '2', '1497258503');
INSERT INTO `wa_district` VALUES ('16', '7', '1', '7', '1497258503');
INSERT INTO `wa_district` VALUES ('17', '8', '8', '1', '1497258504');
INSERT INTO `wa_district` VALUES ('18', '8', '2', '3', '1497258504');
INSERT INTO `wa_district` VALUES ('19', '8', '1', '8', '1497258504');
INSERT INTO `wa_district` VALUES ('20', '9', '9', '1', '1497258506');
INSERT INTO `wa_district` VALUES ('21', '9', '3', '3', '1497258506');
INSERT INTO `wa_district` VALUES ('22', '9', '1', '9', '1497258506');
INSERT INTO `wa_district` VALUES ('23', '10', '10', '1', '1497258507');
INSERT INTO `wa_district` VALUES ('24', '10', '4', '3', '1497258507');
INSERT INTO `wa_district` VALUES ('25', '10', '1', '10', '1497258507');
INSERT INTO `wa_district` VALUES ('26', '11', '11', '1', '1497258509');
INSERT INTO `wa_district` VALUES ('27', '11', '2', '4', '1497258509');
INSERT INTO `wa_district` VALUES ('28', '11', '1', '11', '1497258509');
INSERT INTO `wa_district` VALUES ('29', '12', '12', '1', '1497258511');
INSERT INTO `wa_district` VALUES ('30', '12', '3', '4', '1497258511');
INSERT INTO `wa_district` VALUES ('31', '12', '1', '12', '1497258511');
INSERT INTO `wa_district` VALUES ('32', '13', '13', '1', '1497258512');
INSERT INTO `wa_district` VALUES ('33', '13', '4', '4', '1497258512');
INSERT INTO `wa_district` VALUES ('34', '13', '1', '13', '1497258512');
INSERT INTO `wa_district` VALUES ('35', '14', '14', '1', '1497258513');
INSERT INTO `wa_district` VALUES ('36', '14', '5', '2', '1497258513');
INSERT INTO `wa_district` VALUES ('37', '14', '2', '5', '1497258514');
INSERT INTO `wa_district` VALUES ('38', '14', '1', '14', '1497258514');
INSERT INTO `wa_district` VALUES ('39', '15', '15', '1', '1497258515');
INSERT INTO `wa_district` VALUES ('40', '15', '6', '2', '1497258515');
INSERT INTO `wa_district` VALUES ('41', '15', '3', '5', '1497258515');
INSERT INTO `wa_district` VALUES ('42', '15', '1', '15', '1497258515');
INSERT INTO `wa_district` VALUES ('43', '16', '16', '1', '1497258516');
INSERT INTO `wa_district` VALUES ('44', '16', '7', '2', '1497258517');
INSERT INTO `wa_district` VALUES ('45', '16', '4', '5', '1497258517');
INSERT INTO `wa_district` VALUES ('46', '16', '1', '16', '1497258517');
INSERT INTO `wa_district` VALUES ('47', '17', '17', '1', '1497258518');
INSERT INTO `wa_district` VALUES ('48', '17', '8', '2', '1497258518');
INSERT INTO `wa_district` VALUES ('49', '17', '2', '6', '1497258518');
INSERT INTO `wa_district` VALUES ('50', '17', '1', '17', '1497258518');
INSERT INTO `wa_district` VALUES ('51', '18', '18', '1', '1497258520');
INSERT INTO `wa_district` VALUES ('52', '18', '9', '2', '1497258520');
INSERT INTO `wa_district` VALUES ('53', '18', '3', '6', '1497258520');
INSERT INTO `wa_district` VALUES ('54', '18', '1', '18', '1497258520');
INSERT INTO `wa_district` VALUES ('55', '19', '19', '1', '1497258521');
INSERT INTO `wa_district` VALUES ('56', '19', '10', '2', '1497258521');
INSERT INTO `wa_district` VALUES ('57', '19', '4', '6', '1497258521');
INSERT INTO `wa_district` VALUES ('58', '19', '1', '19', '1497258521');
INSERT INTO `wa_district` VALUES ('59', '20', '20', '1', '1497258523');
INSERT INTO `wa_district` VALUES ('60', '20', '11', '2', '1497258523');
INSERT INTO `wa_district` VALUES ('61', '20', '2', '7', '1497258523');
INSERT INTO `wa_district` VALUES ('62', '20', '1', '20', '1497258523');
INSERT INTO `wa_district` VALUES ('63', '21', '21', '1', '1497258525');
INSERT INTO `wa_district` VALUES ('64', '21', '12', '2', '1497258525');
INSERT INTO `wa_district` VALUES ('65', '21', '3', '7', '1497258525');
INSERT INTO `wa_district` VALUES ('66', '21', '1', '21', '1497258525');
INSERT INTO `wa_district` VALUES ('67', '22', '22', '1', '1497258527');
INSERT INTO `wa_district` VALUES ('68', '22', '13', '2', '1497258527');
INSERT INTO `wa_district` VALUES ('69', '22', '4', '7', '1497258527');
INSERT INTO `wa_district` VALUES ('70', '22', '1', '22', '1497258527');
INSERT INTO `wa_district` VALUES ('71', '23', '23', '1', '1497258528');
INSERT INTO `wa_district` VALUES ('72', '23', '5', '3', '1497258528');
INSERT INTO `wa_district` VALUES ('73', '23', '2', '8', '1497258528');
INSERT INTO `wa_district` VALUES ('74', '23', '1', '23', '1497258528');
INSERT INTO `wa_district` VALUES ('75', '24', '24', '1', '1497258530');
INSERT INTO `wa_district` VALUES ('76', '24', '6', '3', '1497258530');
INSERT INTO `wa_district` VALUES ('77', '24', '3', '8', '1497258530');
INSERT INTO `wa_district` VALUES ('78', '24', '1', '24', '1497258530');
INSERT INTO `wa_district` VALUES ('79', '25', '25', '1', '1497258531');
INSERT INTO `wa_district` VALUES ('80', '25', '7', '3', '1497258531');
INSERT INTO `wa_district` VALUES ('81', '25', '4', '8', '1497258531');
INSERT INTO `wa_district` VALUES ('82', '25', '1', '25', '1497258532');
INSERT INTO `wa_district` VALUES ('83', '26', '26', '1', '1497258533');
INSERT INTO `wa_district` VALUES ('84', '26', '8', '3', '1497258533');
INSERT INTO `wa_district` VALUES ('85', '26', '2', '9', '1497258533');
INSERT INTO `wa_district` VALUES ('86', '26', '1', '26', '1497258533');
INSERT INTO `wa_district` VALUES ('87', '27', '27', '1', '1497258535');
INSERT INTO `wa_district` VALUES ('88', '27', '9', '3', '1497258535');
INSERT INTO `wa_district` VALUES ('89', '27', '3', '9', '1497258535');
INSERT INTO `wa_district` VALUES ('90', '27', '1', '27', '1497258535');
INSERT INTO `wa_district` VALUES ('91', '28', '28', '1', '1497258536');
INSERT INTO `wa_district` VALUES ('92', '28', '10', '3', '1497258536');
INSERT INTO `wa_district` VALUES ('93', '28', '4', '9', '1497258536');
INSERT INTO `wa_district` VALUES ('94', '28', '1', '28', '1497258536');
INSERT INTO `wa_district` VALUES ('95', '29', '29', '1', '1497258538');
INSERT INTO `wa_district` VALUES ('96', '29', '11', '3', '1497258538');
INSERT INTO `wa_district` VALUES ('97', '29', '2', '10', '1497258538');
INSERT INTO `wa_district` VALUES ('98', '29', '1', '29', '1497258538');
INSERT INTO `wa_district` VALUES ('99', '30', '30', '1', '1497258539');
INSERT INTO `wa_district` VALUES ('100', '30', '12', '3', '1497258539');
INSERT INTO `wa_district` VALUES ('101', '30', '3', '10', '1497258539');
INSERT INTO `wa_district` VALUES ('102', '30', '1', '30', '1497258539');
INSERT INTO `wa_district` VALUES ('103', '31', '31', '1', '1497258541');
INSERT INTO `wa_district` VALUES ('104', '31', '13', '3', '1497258541');
INSERT INTO `wa_district` VALUES ('105', '31', '4', '10', '1497258541');
INSERT INTO `wa_district` VALUES ('106', '31', '1', '31', '1497258541');
INSERT INTO `wa_district` VALUES ('107', '32', '32', '1', '1497258543');
INSERT INTO `wa_district` VALUES ('108', '32', '5', '4', '1497258543');
INSERT INTO `wa_district` VALUES ('109', '32', '2', '11', '1497258543');
INSERT INTO `wa_district` VALUES ('110', '32', '1', '32', '1497258543');
INSERT INTO `wa_district` VALUES ('111', '33', '33', '1', '1497258544');
INSERT INTO `wa_district` VALUES ('112', '33', '6', '4', '1497258544');
INSERT INTO `wa_district` VALUES ('113', '33', '3', '11', '1497258544');
INSERT INTO `wa_district` VALUES ('114', '33', '1', '33', '1497258544');
INSERT INTO `wa_district` VALUES ('115', '34', '34', '1', '1497258546');
INSERT INTO `wa_district` VALUES ('116', '34', '7', '4', '1497258546');
INSERT INTO `wa_district` VALUES ('117', '34', '4', '11', '1497258546');
INSERT INTO `wa_district` VALUES ('118', '34', '1', '34', '1497258546');
INSERT INTO `wa_district` VALUES ('119', '35', '35', '1', '1497258547');
INSERT INTO `wa_district` VALUES ('120', '35', '8', '4', '1497258547');
INSERT INTO `wa_district` VALUES ('121', '35', '2', '12', '1497258547');
INSERT INTO `wa_district` VALUES ('122', '35', '1', '35', '1497258548');
INSERT INTO `wa_district` VALUES ('123', '36', '36', '1', '1497258549');
INSERT INTO `wa_district` VALUES ('124', '36', '9', '4', '1497258549');
INSERT INTO `wa_district` VALUES ('125', '36', '3', '12', '1497258549');
INSERT INTO `wa_district` VALUES ('126', '36', '1', '36', '1497258549');
INSERT INTO `wa_district` VALUES ('127', '37', '37', '1', '1497258551');
INSERT INTO `wa_district` VALUES ('128', '37', '10', '4', '1497258551');
INSERT INTO `wa_district` VALUES ('129', '37', '4', '12', '1497258551');
INSERT INTO `wa_district` VALUES ('130', '37', '1', '37', '1497258551');
INSERT INTO `wa_district` VALUES ('131', '38', '38', '1', '1497258552');
INSERT INTO `wa_district` VALUES ('132', '38', '11', '4', '1497258552');
INSERT INTO `wa_district` VALUES ('133', '38', '2', '13', '1497258552');
INSERT INTO `wa_district` VALUES ('134', '38', '1', '38', '1497258552');
INSERT INTO `wa_district` VALUES ('135', '39', '39', '1', '1497258554');
INSERT INTO `wa_district` VALUES ('136', '39', '12', '4', '1497258554');
INSERT INTO `wa_district` VALUES ('137', '39', '3', '13', '1497258554');
INSERT INTO `wa_district` VALUES ('138', '39', '1', '39', '1497258554');

-- ----------------------------
-- Table structure for wa_fruiter
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
-- Records of wa_fruiter
-- ----------------------------

-- ----------------------------
-- Table structure for wa_fruiter_img
-- ----------------------------
DROP TABLE IF EXISTS `wa_fruiter_img`;
CREATE TABLE `wa_fruiter_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fruiter_id` int(11) NOT NULL DEFAULT '0' COMMENT '果树ID',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='果树图片表';

-- ----------------------------
-- Records of wa_fruiter_img
-- ----------------------------

-- ----------------------------
-- Table structure for wa_give
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
-- Records of wa_give
-- ----------------------------

-- ----------------------------
-- Table structure for wa_goods
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
-- Records of wa_goods
-- ----------------------------

-- ----------------------------
-- Table structure for wa_goods_img
-- ----------------------------
DROP TABLE IF EXISTS `wa_goods_img`;
CREATE TABLE `wa_goods_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '模型名',
  `img_path` varchar(255) NOT NULL COMMENT '存放路径',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品图片表';

-- ----------------------------
-- Records of wa_goods_img
-- ----------------------------

-- ----------------------------
-- Table structure for wa_member
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
  `out_status` int(10) NOT NULL DEFAULT '0' COMMENT '是否可以退网 0:否 1:是',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 COMMENT='会员信息表';

-- ----------------------------
-- Records of wa_member
-- ----------------------------
INSERT INTO `wa_member` VALUES ('1', '0', 'member1', '$2y$13$gu094onaVGc9v5Juiz6SD.Tcoxio8IANlYRZjgd7mlFEDjS1OtIVK', '13219890986', '成都银行', '62284848822113464', '环球中心', '1496629517', '1', '0', '1496629900', '1', '99994949', '2147479647', '38', '0');
INSERT INTO `wa_member` VALUES ('2', '1', '小强', '$2y$13$rnh5SXBS/IYzPXfZs2H/j.O38MnC2BY6zKDOJSPF6lLkLF3MPFj9.', '13812345678', '', '', '', '0', '1', '1497258492', '1497258492', '2', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('3', '1', '小强', '$2y$13$ZrOHfW.x9U5qbeogqateIu3cIwJurioadF1XFB0QeTAfYly7A73k.', '13812345678', '', '', '', '0', '1', '1497258495', '1497258495', '3', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('4', '1', '小强', '$2y$13$LBHTidx4z74GT5riP63fsu.eK1FcwXh/wOO4t59B5T1jbfVrZWcsK', '13812345678', '', '', '', '0', '1', '1497258497', '1497258497', '4', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('5', '1', '小强', '$2y$13$3ZAMkAjSULKmYG.RHEqcJu/wO41hs7smI3VVkv9S3aJRWvB0gTqAS', '13812345678', '', '', '', '0', '1', '1497258499', '1497258499', '5', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('6', '1', '小强', '$2y$13$..SeuS.YiAoc9M9xs01ZwuMRBrhgJAKprdHSiDlKikT6h8RTi5V02', '13812345678', '', '', '', '0', '1', '1497258500', '1497258500', '6', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('7', '1', '小强', '$2y$13$AldZmA8CrSRA4gDTdVaXxeTQ95fdgI8AMzY3cfA6ptErsTuxArjX.', '13812345678', '', '', '', '0', '1', '1497258502', '1497258502', '7', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('8', '1', '小强', '$2y$13$4MJ40f71uVVSw6Q7N2qwRuixkt3J5e5QJQVliJTyP2jbLOCHKPw4a', '13812345678', '', '', '', '0', '1', '1497258504', '1497258504', '8', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('9', '1', '小强', '$2y$13$xkRGa3nVClil7faqnTdl8OWoX.fHFz.YrOQ5VWawbmis9tiO6MdSK', '13812345678', '', '', '', '0', '1', '1497258505', '1497258505', '9', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('10', '1', '小强', '$2y$13$cGtzE2bXmO4r/nTb5uUav./9wgo6Ab/ClZD4XIZ4dWrxojdtAabqC', '13812345678', '', '', '', '0', '1', '1497258507', '1497258507', '10', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('11', '1', '小强', '$2y$13$ekoAsgCF1grBu2Ybd//G7u2orP1gTXfius/EREY41vr.9Kx9qLlzG', '13812345678', '', '', '', '0', '1', '1497258508', '1497258508', '11', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('12', '1', '小强', '$2y$13$Jo5hcm9NPdYJOuAU7pxu7e3OP4lJIJMQv92aDZNDkKdIvxR4dsV0a', '13812345678', '', '', '', '0', '1', '1497258510', '1497258510', '12', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('13', '1', '小强', '$2y$13$RPpidtt1YgWMbewkOzftJ.RHr47c/BAAhckyZQGJa9xFg9lCABJI.', '13812345678', '', '', '', '0', '1', '1497258511', '1497258511', '13', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('14', '1', '小强', '$2y$13$WHsuyY7c99hMVqMm5Z1eUe2icbLQM57xXlpx9wVqlIsStoFWU7bjq', '13812345678', '', '', '', '0', '1', '1497258513', '1497258513', '14', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('15', '1', '小强', '$2y$13$vdOKY4U5gtdyUbaCkWmcy.8AUrwKUg0PCdJC918Y.GV5f.vF7ZqqG', '13812345678', '', '', '', '0', '1', '1497258514', '1497258514', '15', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('16', '1', '小强', '$2y$13$tgKT6kigZGi0rbIKE8ujSevayseyLYpNzv5jjDkOwH/LOr4vs2l8e', '13812345678', '', '', '', '0', '1', '1497258516', '1497258516', '16', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('17', '1', '小强', '$2y$13$NbeRSupc1haXJg9uUL2PyeN7yzRfLuyD9nkaVj865ulX9I4qugyJu', '13812345678', '', '', '', '0', '1', '1497258517', '1497258517', '17', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('18', '1', '小强', '$2y$13$DV.2wTovdZF5qnk9o3uTlOxx.sL9EzdP11mtzSj4KBA9QQtjBoMeS', '13812345678', '', '', '', '0', '1', '1497258519', '1497258519', '18', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('19', '1', '小强', '$2y$13$IPtw04EzhBoRUklyNTv5SerUNgguklns5vlGncNpKd5DYsl08L4uG', '13812345678', '', '', '', '0', '1', '1497258521', '1497258521', '19', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('20', '1', '小强', '$2y$13$5mExifToVgHLj.CIzUWR5eIzb2ysZxsusexP0j87r7BaBZtsb/22S', '13812345678', '', '', '', '0', '1', '1497258522', '1497258522', '20', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('21', '1', '小强', '$2y$13$SXsxhyNvG9qWp7qh.ylbSOQSrcs8BG5LRoMJvTczVijvoAm8fA3UK', '13812345678', '', '', '', '0', '1', '1497258524', '1497258524', '21', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('22', '1', '小强', '$2y$13$IDArSQU0unhRjMYlVCznquxcxLBUai01MsdRWTkVww8WYkeZm.TYG', '13812345678', '', '', '', '0', '1', '1497258526', '1497258526', '22', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('23', '1', '小强', '$2y$13$d4qUxDjZcjzgneSAkVGQ9.CBc5WOdcnlRqe4wxSz5U65lof26tGfq', '13812345678', '', '', '', '0', '1', '1497258527', '1497258527', '23', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('24', '1', '小强', '$2y$13$HWDrUOc3XRbd.s9gxptl9.biwCSnAmB70WXJIVDEsly.pYF7/3Bc.', '13812345678', '', '', '', '0', '1', '1497258529', '1497258529', '24', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('25', '1', '小强', '$2y$13$L.ozHhNFN2E3g8dGuqufBePYcA6uMsYp/x9xf1EDHw/OCWeo9i8.W', '13812345678', '', '', '', '0', '1', '1497258531', '1497258531', '25', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('26', '1', '小强', '$2y$13$IVy3v55lDZHmO6wEwO.sPemn5Z6pysyY/vKl6GKrHFq2cMrUd.l3S', '13812345678', '', '', '', '0', '1', '1497258532', '1497258532', '26', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('27', '1', '小强', '$2y$13$g2yvvd7K/yAEIMWltXAMAeub59QPdedAyfwfYH7cFYUF/VX.O1o4S', '13812345678', '', '', '', '0', '1', '1497258534', '1497258534', '27', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('28', '1', '小强', '$2y$13$6rbFa/a4rjty.h.2gCVwAO9vzZUxSuwrdxAsTsjtjqKQabf7rF0aO', '13812345678', '', '', '', '0', '1', '1497258535', '1497258535', '28', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('29', '1', '小强', '$2y$13$YYPML2tBkKqfEgPd/etLXe.sHLdWsNFHoIlikizJyTmJP5PDHitNy', '13812345678', '', '', '', '0', '1', '1497258537', '1497258537', '29', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('30', '1', '小强', '$2y$13$bbT6eplPy.OHSBNNu0RCVugu1EM5RWLZPaFN8MdslA.v8GlPezs8i', '13812345678', '', '', '', '0', '1', '1497258539', '1497258539', '30', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('31', '1', '小强', '$2y$13$K3AtkYzUj3mCV/ko8bEXS.1gzEcRD0LPsaZsAi2xcbXlwT9eUrq5G', '13812345678', '', '', '', '0', '1', '1497258540', '1497258540', '31', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('32', '1', '小强', '$2y$13$19PEVToNfNkvaxc.VmfAXOPVLwVUnl8OHLPowboHt/UG0gSt6G5Iy', '13812345678', '', '', '', '0', '1', '1497258542', '1497258542', '32', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('33', '1', '小强', '$2y$13$bk6fHrOpw.gy4nCfcyrFF.4ZDC1xOVbkl/8qLolLdBW0U3ziEipMq', '13812345678', '', '', '', '0', '1', '1497258544', '1497258544', '33', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('34', '1', '小强', '$2y$13$98j3qBXIZz.V/LOUGW2jeO5rbaylgSIXwnIwxVT1OZjm5wuNR/yfy', '13812345678', '', '', '', '0', '1', '1497258545', '1497258545', '34', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('35', '1', '小强', '$2y$13$44wMjapJLVPoqkepEijhHOzUcbjm3nu0PnjZ6nptRMKp0jFigUCDe', '13812345678', '', '', '', '0', '1', '1497258547', '1497258547', '35', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('36', '1', '小强', '$2y$13$fKIOjjK22VNjmBc1EgrPfuTIanQQ.yPNIgWrb9.bbswVEWIVZwaKm', '13812345678', '', '', '', '0', '1', '1497258548', '1497258548', '36', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('37', '1', '小强', '$2y$13$StRJtnpzZtnUyvsuNuT8kecgBrDTtSGxKynPPX8ncskfldHOXbVmi', '13812345678', '', '', '', '0', '1', '1497258550', '1497258550', '37', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('38', '1', '小强', '$2y$13$yHT8EMeQkbZ2vIOt0Gk8YOXveYHkKb3LOIzBd9/6i0B1DcoPAgflS', '13812345678', '', '', '', '0', '1', '1497258552', '1497258552', '38', '0', '0', '0', '0');
INSERT INTO `wa_member` VALUES ('39', '1', '小强', '$2y$13$Mc1Z07JsX/XMhSl.XCK6W.DkPbBthxYSYW9rfkvIvqEHP6YjEIIde', '13812345678', '', '', '', '0', '1', '1497258553', '1497258553', '39', '0', '0', '0', '0');

-- ----------------------------
-- Table structure for wa_member_district
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_district`;
CREATE TABLE `wa_member_district` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `district` int(11) DEFAULT NULL COMMENT '区id',
  `is_ extra` tinyint(1) DEFAULT '0' COMMENT '是否是本身39个会员形成的区；1是 0否',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员直推区表';

-- ----------------------------
-- Records of wa_member_district
-- ----------------------------

-- ----------------------------
-- Table structure for wa_member_status
-- ----------------------------
DROP TABLE IF EXISTS `wa_member_status`;
CREATE TABLE `wa_member_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '状态',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '状态名',
  `value` int(11) NOT NULL DEFAULT '1' COMMENT '状态值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户状态表';

-- ----------------------------
-- Records of wa_member_status
-- ----------------------------

-- ----------------------------
-- Table structure for wa_menu
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of wa_menu
-- ----------------------------
INSERT INTO `wa_menu` VALUES ('1', '权限管理', null, '/admin/user/index', null, 0x66610D0A, '');
INSERT INTO `wa_menu` VALUES ('2', '管理员列表', '1', '/admin/user/index', null, 0x3C6920636C6173733D22666122202F3E, '');
INSERT INTO `wa_menu` VALUES ('3', '分配', '1', '/admin/assignment/index', null, null, '');
INSERT INTO `wa_menu` VALUES ('4', '角色列表', '1', '/admin/role/index', null, null, 'aa');
INSERT INTO `wa_menu` VALUES ('5', '路由列表', '1', '/admin/route/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('6', '规则列表', '1', '/admin/rule/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('7', '菜单列表', '1', '/admin/menu/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('8', '会员管理', null, '/member/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('9', '财务管理', null, '/record/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('10', '退网记录', '8', '/outline/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('11', '会员信息', '8', '/member/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('12', '公告管理', null, '/announcements/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('13', '广告管理', null, '/branner/index', null, 0xE5898DE58FB0E8BDAEE692ADE59BBEE78987E79A84E7AEA1E79086, null);
INSERT INTO `wa_menu` VALUES ('14', '赠送记录', '8', '/give/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('15', '果树管理', null, '/fruiter/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('16', '统计中心', null, '/count/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('17', '商品管理', null, '/goods/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('18', '充值管理', null, '/deposit/index', null, null, null);
INSERT INTO `wa_menu` VALUES ('19', '充值', '18', '/deposit/open', null, null, null);
INSERT INTO `wa_menu` VALUES ('20', '扣除', '18', '/deposit/end', null, null, null);
INSERT INTO `wa_menu` VALUES ('21', '充值记录', '18', '/deposit/index', null, null, null);

-- ----------------------------
-- Table structure for wa_migration
-- ----------------------------
DROP TABLE IF EXISTS `wa_migration`;
CREATE TABLE `wa_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of wa_migration
-- ----------------------------

-- ----------------------------
-- Table structure for wa_order
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
-- Records of wa_order
-- ----------------------------

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
-- Records of wa_outline
-- ----------------------------

-- ----------------------------
-- Table structure for wa_record
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
-- Records of wa_record
-- ----------------------------

-- ----------------------------
-- Table structure for wa_share_log
-- ----------------------------
DROP TABLE IF EXISTS `wa_share_log`;
CREATE TABLE `wa_share_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `referrer_id` int(11) NOT NULL COMMENT '分享人member_id',
  `member_id` int(11) NOT NULL COMMENT '生成会员id',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '生成时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COMMENT='推荐会员记录表';

-- ----------------------------
-- Records of wa_share_log
-- ----------------------------
INSERT INTO `wa_share_log` VALUES ('22', '1', '2', '1497258493');
INSERT INTO `wa_share_log` VALUES ('23', '1', '3', '1497258496');
INSERT INTO `wa_share_log` VALUES ('24', '1', '4', '1497258498');
INSERT INTO `wa_share_log` VALUES ('25', '1', '5', '1497258499');
INSERT INTO `wa_share_log` VALUES ('26', '1', '6', '1497258501');
INSERT INTO `wa_share_log` VALUES ('27', '1', '7', '1497258503');
INSERT INTO `wa_share_log` VALUES ('28', '1', '8', '1497258504');
INSERT INTO `wa_share_log` VALUES ('29', '1', '9', '1497258506');
INSERT INTO `wa_share_log` VALUES ('30', '1', '10', '1497258507');
INSERT INTO `wa_share_log` VALUES ('31', '1', '11', '1497258509');
INSERT INTO `wa_share_log` VALUES ('32', '1', '12', '1497258510');
INSERT INTO `wa_share_log` VALUES ('33', '1', '13', '1497258512');
INSERT INTO `wa_share_log` VALUES ('34', '1', '14', '1497258513');
INSERT INTO `wa_share_log` VALUES ('35', '1', '15', '1497258515');
INSERT INTO `wa_share_log` VALUES ('36', '1', '16', '1497258516');
INSERT INTO `wa_share_log` VALUES ('37', '1', '17', '1497258518');
INSERT INTO `wa_share_log` VALUES ('38', '1', '18', '1497258520');
INSERT INTO `wa_share_log` VALUES ('39', '1', '19', '1497258521');
INSERT INTO `wa_share_log` VALUES ('40', '1', '20', '1497258523');
INSERT INTO `wa_share_log` VALUES ('41', '1', '21', '1497258525');
INSERT INTO `wa_share_log` VALUES ('42', '1', '22', '1497258527');
INSERT INTO `wa_share_log` VALUES ('43', '1', '23', '1497258528');
INSERT INTO `wa_share_log` VALUES ('44', '1', '24', '1497258530');
INSERT INTO `wa_share_log` VALUES ('45', '1', '25', '1497258531');
INSERT INTO `wa_share_log` VALUES ('46', '1', '26', '1497258533');
INSERT INTO `wa_share_log` VALUES ('47', '1', '27', '1497258535');
INSERT INTO `wa_share_log` VALUES ('48', '1', '28', '1497258536');
INSERT INTO `wa_share_log` VALUES ('49', '1', '29', '1497258538');
INSERT INTO `wa_share_log` VALUES ('50', '1', '30', '1497258539');
INSERT INTO `wa_share_log` VALUES ('51', '1', '31', '1497258541');
INSERT INTO `wa_share_log` VALUES ('52', '1', '32', '1497258542');
INSERT INTO `wa_share_log` VALUES ('53', '1', '33', '1497258544');
INSERT INTO `wa_share_log` VALUES ('54', '1', '34', '1497258546');
INSERT INTO `wa_share_log` VALUES ('55', '1', '35', '1497258547');
INSERT INTO `wa_share_log` VALUES ('56', '1', '36', '1497258549');
INSERT INTO `wa_share_log` VALUES ('57', '1', '37', '1497258550');
INSERT INTO `wa_share_log` VALUES ('58', '1', '38', '1497258552');
INSERT INTO `wa_share_log` VALUES ('59', '1', '39', '1497258554');
