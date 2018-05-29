/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50721
Source Host           : localhost:3306
Source Database       : oasys

Target Server Type    : MYSQL
Target Server Version : 50721
File Encoding         : 65001

Date: 2018-05-29 15:11:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `core_ip`
-- ----------------------------
DROP TABLE IF EXISTS `core_ip`;
CREATE TABLE `core_ip` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ip_addr` varchar(39) COLLATE utf8_bin NOT NULL COMMENT 'IP地址',
  `ip_ban` tinyint(1) NOT NULL COMMENT 'IP封锁状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `ip_addr` (`ip_addr`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of core_ip
-- ----------------------------
INSERT INTO `core_ip` VALUES ('1', '::1', '0');
INSERT INTO `core_ip` VALUES ('2', '127.0.0.1', '0');

-- ----------------------------
-- Table structure for `core_log`
-- ----------------------------
DROP TABLE IF EXISTS `core_log`;
CREATE TABLE `core_log` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `log_date` datetime NOT NULL COMMENT '创建时间',
  `log_ip` bigint(20) unsigned NOT NULL COMMENT '宿主IP ID',
  `log_message` text COLLATE utf8_bin NOT NULL COMMENT '描述消息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `log_ip` (`log_ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of core_log
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_configs`
-- ----------------------------
DROP TABLE IF EXISTS `oa_configs`;
CREATE TABLE `oa_configs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_name` varchar(300) COLLATE utf8_bin NOT NULL,
  `config_value` text COLLATE utf8_bin NOT NULL,
  `config_default` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `config_name` (`config_name`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oa_configs
-- ----------------------------
INSERT INTO `oa_configs` VALUES ('1', 'WEB_TITLE', 0x4F41E58A9EE585ACE7B3BBE7BB9F, 0x4F41E58A9EE585ACE7B3BBE7BB9F);
INSERT INTO `oa_configs` VALUES ('2', 'USER_TIMEOUT', 0x393030, 0x393030);
INSERT INTO `oa_configs` VALUES ('3', 'UPLOADFILE_SIZE_MIN', 0x31, 0x31);
INSERT INTO `oa_configs` VALUES ('4', 'UPLOADFILE_SIZE_MAX', 0x313533363030, 0x313533363030);
INSERT INTO `oa_configs` VALUES ('5', 'UPLOADFILE_ON', 0x31, 0x31);
INSERT INTO `oa_configs` VALUES ('6', 'UPLOADFILE_INHIBIT_TYPE', 0x6578652C6261742C7068702C68746D6C2C68746D2C7368616C6C, 0x6578652C6261742C7068702C68746D6C2C68746D2C7368616C6C);
INSERT INTO `oa_configs` VALUES ('7', 'WEB_URL', 0x687474703A2F2F6F612E636F6D, 0x687474703A2F2F6C6F63616C686F7374);
INSERT INTO `oa_configs` VALUES ('8', 'PERFORMANCE_SCALE', 0x31, 0x31);
INSERT INTO `oa_configs` VALUES ('9', 'WEB_ON', 0x31, 0x31);
INSERT INTO `oa_configs` VALUES ('10', 'BACKUP_AUTO_ON', 0x31, 0x31);
INSERT INTO `oa_configs` VALUES ('11', 'BACKUP_LAST_DATE', 0x3230313330343233, 0x3230313330343233);
INSERT INTO `oa_configs` VALUES ('12', 'BACKUP_AUTO_CYCLE', 0x3130, 0x3130);
INSERT INTO `oa_configs` VALUES ('13', 'BACKUP_DIR', 0x636F6E74656E742F6261636B7570, 0x636F6E74656E742F6261636B7570);

-- ----------------------------
-- Table structure for `oa_posts`
-- ----------------------------
DROP TABLE IF EXISTS `oa_posts`;
CREATE TABLE `oa_posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `post_title` varchar(300) COLLATE utf8_bin DEFAULT NULL COMMENT '标题',
  `post_content` longtext COLLATE utf8_bin COMMENT '内容',
  `post_date` datetime NOT NULL COMMENT '创建时间',
  `post_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '修改时间',
  `post_ip` bigint(20) unsigned DEFAULT NULL COMMENT 'IP ID',
  `post_type` varchar(300) COLLATE utf8_bin NOT NULL COMMENT '内容类型标识',
  `post_order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `post_parent` bigint(20) unsigned NOT NULL COMMENT '上一级ID',
  `post_user` bigint(20) unsigned DEFAULT NULL COMMENT '用户ID',
  `post_password` varchar(41) COLLATE utf8_bin DEFAULT NULL COMMENT '访问密码或内容匹配值',
  `post_url` varchar(500) COLLATE utf8_bin DEFAULT NULL COMMENT '多媒体文件路径',
  `post_status` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'public' COMMENT '发布状态',
  `post_meta` varchar(300) COLLATE utf8_bin DEFAULT NULL COMMENT '头信息',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `post_ip` (`post_ip`),
  KEY `post_parent` (`post_parent`),
  KEY `post_user` (`post_user`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Table structure for `oa_user`
-- ----------------------------
DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `user_username` varchar(20) COLLATE utf8_bin NOT NULL COMMENT '用户名',
  `user_password` char(41) COLLATE utf8_bin NOT NULL COMMENT '密码',
  `user_email` varchar(255) COLLATE utf8_bin NOT NULL COMMENT '邮箱',
  `user_name` varchar(60) COLLATE utf8_bin NOT NULL COMMENT '昵称',
  `user_group` bigint(20) unsigned NOT NULL COMMENT '用户组',
  `user_date` datetime NOT NULL COMMENT '创建时间',
  `user_login_date` datetime NOT NULL COMMENT '上一次登录时间',
  `user_ip` bigint(20) unsigned NOT NULL COMMENT '登录IP ID',
  `user_session` char(32) COLLATE utf8_bin NOT NULL COMMENT '登录会话值',
  `user_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `user_remember` tinyint(1) NOT NULL DEFAULT '0' COMMENT '记住我',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `user_username` (`user_username`),
  KEY `user_group` (`user_group`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `oa_user` VALUES ('1', 'admin', '7c4a8d09ca3762af61e59520943dc26494f8941b', 'admin@admin.com', '管理员', '1', '2013-03-20 11:15:57', '2018-05-29 14:53:52', '2', 'i42i6qkr6u3qcsj22sga3g23sk', '1', '0');

-- ----------------------------
-- Table structure for `oa_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `oa_user_group`;
CREATE TABLE `oa_user_group` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `group_name` varchar(30) COLLATE utf8_bin NOT NULL COMMENT '名称',
  `group_power` text COLLATE utf8_bin NOT NULL COMMENT '权限',
  `group_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `group_name` (`group_name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of oa_user_group
-- ----------------------------
INSERT INTO `oa_user_group` VALUES ('1', '管理员组', 0x61646D696E, '1');
