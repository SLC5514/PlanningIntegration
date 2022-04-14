/*
Navicat MySQL Data Transfer

Source Server         : 开发环境-184
Source Server Version : 50519
Source Host           : 192.168.3.184:3306
Source Database       : fashion

Target Server Type    : MYSQL
Target Server Version : 50519
File Encoding         : 65001

Date: 2017-07-31 11:25:00
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_puzzle`
-- ----------------------------
DROP TABLE IF EXISTS `t_puzzle`;
CREATE TABLE `t_puzzle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '拼图ID',
  `sPuPicUrl` varchar(800) NOT NULL DEFAULT '' COMMENT '拼图图片地址',
  `sPuAreaDetails` varchar(8000) NOT NULL DEFAULT '' COMMENT '拼图模板区块详细,结构:\r\narray(\r\n    array( \r\n       ID =>product_perform_1\r\n        X => 0\r\n        Y => 0\r\n        W  => 100\r\n        H  => 100\r\n     )\r\n   ......\r\n)',
  `iStatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 -1删除，1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼图表';

-- ----------------------------
-- Records of t_puzzle
-- ----------------------------

-- ----------------------------
-- Table structure for `t_puzzle_local_photos`
-- ----------------------------
DROP TABLE IF EXISTS `t_puzzle_local_photos`;
CREATE TABLE `t_puzzle_local_photos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `sLocalPhoto` varchar(1000) NOT NULL DEFAULT '' COMMENT '本地上传图片地址',
  `iStatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 -1删除，1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='拼图本地图上传表';

-- ----------------------------
-- Records of t_puzzle_local_photos
-- ----------------------------

-- ----------------------------
-- Table structure for `t_puzzle_templates`
-- ----------------------------
DROP TABLE IF EXISTS `t_puzzle_templates`;
CREATE TABLE `t_puzzle_templates` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模板ID',
  `sTemplateThumbPhoto` varchar(800) NOT NULL DEFAULT '' COMMENT '模板图路径',
  `sTemplateAreaDetails` varchar(8000) NOT NULL DEFAULT '' COMMENT '模板区块详细,结构:\r\nArray\r\n(\r\n    [0] => Array\r\n        (\r\n            [x] => 0\r\n            [y] => 0\r\n            [width] => 100\r\n            [height] => 100\r\n        )\r\n   ......\r\n)',
  `sSite` varchar(20) NOT NULL DEFAULT '1' COMMENT '模板使用站点：1-服装趋势，2-箱包，3-鞋子，4-首饰，5-家纺，多个‘,’隔开',
  `iStatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '模板状态 -1删除，1正常',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='模板表';

-- ----------------------------
-- Records of t_puzzle_templates
-- ----------------------------
INSERT INTO `t_puzzle_templates` VALUES ('1', '/upload/puzzle/local/20170731/M1.jpg', '[{\"x\":10,\"y\":10,\"width\":475,\"height\":685},{\"x\":495,\"y\":10,\"width\":495,\"height\":435},{\"x\":495,\"y\":455,\"width\":235,\"height\":250},{\"x\":735,\"y\":455,\"width\":260,\"height\":250}]', '1', '1');
INSERT INTO `t_puzzle_templates` VALUES ('2', '/upload/puzzle/local/20170731/M2.jpg', '{\"id\":2,\"thumb\":\"M2.jpg\",\"grids\":[{\"x\":10,\"y\":10,\"width\":420,\"height\":336},{\"x\":440,\"y\":10,\"width\":230,\"height\":336},{\"x\":10,\"y\":355,\"width\":230,\"height\":336},{\"x\":250,\"y\":355,\"width\":420,\"height\":336}]}', '1', '1');

-- ----------------------------
-- Table structure for `t_trend_subreport_puzzle`
-- ----------------------------
DROP TABLE IF EXISTS `t_trend_subreport_puzzle`;
CREATE TABLE `t_trend_subreport_puzzle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `iPageId` int(11) NOT NULL DEFAULT '0' COMMENT '页id',
  `sPuzzleId` varchar(100) NOT NULL DEFAULT '' COMMENT '模板拼图ID',
  `sContent` longtext COMMENT '拼图模板json数据',
  `iStatus` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 -1删除，1正常',
  PRIMARY KEY (`id`),
  KEY `idx_sPuzzleId` (`sPuzzleId`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='模板拼图信息表';

-- ----------------------------
-- Records of t_trend_subreport_puzzle
-- ----------------------------
