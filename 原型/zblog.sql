/*
Navicat MySQL Data Transfer

Source Server         : 本地
Source Server Version : 50540
Source Host           : localhost:3306
Source Database       : zblog

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2017-10-05 20:41:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for blog_article
-- ----------------------------
DROP TABLE IF EXISTS `blog_article`;
CREATE TABLE `blog_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT '标题',
  `content` text COMMENT '内容',
  `add_time` int(11) DEFAULT NULL,
  `add_date` datetime DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `update_date` datetime DEFAULT NULL,
  `click` int(11) NOT NULL DEFAULT '0',
  `cate_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for blog_cate
-- ----------------------------
DROP TABLE IF EXISTS `blog_cate`;
CREATE TABLE `blog_cate` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `order` tinyint(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for blog_comment
-- ----------------------------
DROP TABLE IF EXISTS `blog_comment`;
CREATE TABLE `blog_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) DEFAULT '0' COMMENT '上级评论id',
  `aid` int(11) NOT NULL COMMENT '文章id',
  `user_name` varchar(255) DEFAULT NULL,
  `content` varchar(255) NOT NULL COMMENT '评论',
  `add_time` datetime DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='评论表';

-- ----------------------------
-- Table structure for blog_user
-- ----------------------------
DROP TABLE IF EXISTS `blog_user`;
CREATE TABLE `blog_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) DEFAULT NULL,
  `passwd` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
