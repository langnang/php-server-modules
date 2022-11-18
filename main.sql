/*
 Navicat Premium Data Transfer
 
 Source Server         : www.db4free.net_3306
 Source Server Type    : MySQL
 Source Server Version : 80031
 Source Host           : www.db4free.net:3306
 Source Schema         : langnang
 
 Target Server Type    : MySQL
 Target Server Version : 80031
 File Encoding         : 65001
 
 Date: 18/11/2022 15:13:53
 */
SET
  NAMES utf8mb4;

SET
  FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `coid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `cid` INT UNSIGNED NULL DEFAULT 0,
  `created` INT UNSIGNED NULL DEFAULT 0,
  `author` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `authorId` INT UNSIGNED NULL DEFAULT 0,
    `ownerId` INT UNSIGNED NULL DEFAULT 0,
    `mail` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `url` VARCHAR(255) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `ip` VARCHAR(64) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `agent` VARCHAR(511) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `text` text CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
    `type` VARCHAR(16) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'comment',
    `status` VARCHAR(16) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'approved',
    `parent` INT UNSIGNED NULL DEFAULT 0,
    PRIMARY KEY (`coid`) USING BTREE,
    INDEX `cid`(`cid`) USING BTREE,
    INDEX `created`(`created`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for contents
-- ----------------------------
DROP TABLE IF EXISTS `contents`;

CREATE TABLE `contents` (
  `cid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `slug` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `created` INT UNSIGNED NULL DEFAULT 0,
    `modified` INT UNSIGNED NULL DEFAULT 0,
    `text` longtext CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
    `order` INT UNSIGNED NULL DEFAULT 0,
    `authorId` INT UNSIGNED NULL DEFAULT 0,
    `template` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `type` VARCHAR(16) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'post',
    `status` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'publish',
    `password` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `commentsNum` INT UNSIGNED NULL DEFAULT 0,
    `allowComment` CHAR(1) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0',
    `allowPing` CHAR(1) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0',
    `allowFeed` CHAR(1) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '0',
    `parent` INT UNSIGNED NULL DEFAULT 0,
    PRIMARY KEY (`cid`) USING BTREE,
    UNIQUE INDEX `slug`(`slug`) USING BTREE,
    INDEX `created`(`created`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for fields
-- ----------------------------
DROP TABLE IF EXISTS `fields`;

CREATE TABLE `fields` (
  `cid` INT UNSIGNED NOT NULL,
  `name` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `type` VARCHAR(8) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'str',
    `str_value` text CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
    `int_value` INT NULL DEFAULT 0,
    `float_value` FLOAT NULL DEFAULT 0,
    PRIMARY KEY (`cid`, `name`) USING BTREE,
    INDEX `int_value`(`int_value`) USING BTREE,
    INDEX `float_value`(`float_value`) USING BTREE
) ENGINE = InnoDB CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for logs
-- ----------------------------
DROP TABLE IF EXISTS `logs`;

CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `channel` VARCHAR(255) CHARACTER
  SET
    utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
    `level` INT NULL DEFAULT NULL,
    `message` longtext CHARACTER
  SET
    utf8mb3 COLLATE utf8mb3_general_ci NULL,
    `time` INT UNSIGNED NULL DEFAULT NULL,
    `var` text CHARACTER
  SET
    utf8mb3 COLLATE utf8mb3_general_ci NULL,
    `value` text CHARACTER
  SET
    utf8mb3 COLLATE utf8mb3_general_ci NULL,
    `uuid` text CHARACTER
  SET
    utf8mb3 COLLATE utf8mb3_general_ci NULL,
    `timestamp` text CHARACTER
  SET
    utf8mb3 COLLATE utf8mb3_general_ci NULL,
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `channel`(`channel`) USING BTREE,
    INDEX `level`(`level`) USING BTREE,
    INDEX `time`(`time`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 218 CHARACTER
SET
  = utf8mb3 COLLATE = utf8mb3_general_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for metas
-- ----------------------------
DROP TABLE IF EXISTS `metas`;

CREATE TABLE `metas` (
  `mid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `slug` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `type` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `description` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `count` INT UNSIGNED NULL DEFAULT 0,
    `order` INT UNSIGNED NULL DEFAULT 0,
    `parent` INT UNSIGNED NULL DEFAULT 0,
    PRIMARY KEY (`mid`) USING BTREE,
    INDEX `slug`(`slug`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for options
-- ----------------------------
DROP TABLE IF EXISTS `options`;

CREATE TABLE `options` (
  `name` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
    `user` INT UNSIGNED NOT NULL DEFAULT 0,
    `value` text CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
    PRIMARY KEY (`name`, `user`) USING BTREE
) ENGINE = InnoDB CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for relationships
-- ----------------------------
DROP TABLE IF EXISTS `relationships`;

CREATE TABLE `relationships` (
  `cid` INT UNSIGNED NOT NULL,
  `mid` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`cid`, `mid`) USING BTREE
) ENGINE = InnoDB CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `uid` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `password` VARCHAR(64) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `mail` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `url` VARCHAR(150) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `screenName` VARCHAR(32) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    `created` INT UNSIGNED NULL DEFAULT 0,
    `activated` INT UNSIGNED NULL DEFAULT 0,
    `logged` INT UNSIGNED NULL DEFAULT 0,
    `group` VARCHAR(16) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'visitor',
    `authCode` VARCHAR(64) CHARACTER
  SET
    utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
    PRIMARY KEY (`uid`) USING BTREE,
    UNIQUE INDEX `name`(`name`) USING BTREE,
    UNIQUE INDEX `mail`(`mail`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- View structure for mock_contents
-- ----------------------------
DROP VIEW IF EXISTS `mock_contents`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `mock_contents` AS
SELECT
  `contents`.`cid` AS `cid`,
  `contents`.`title` AS `title`,
  `contents`.`slug` AS `slug`,
  `contents`.`created` AS `created`,
  `contents`.`modified` AS `modified`,
  `contents`.`text` AS `text`,
  `contents`.`order` AS `order`,
  `contents`.`authorId` AS `authorId`,
  `contents`.`template` AS `template`,
  `contents`.`type` AS `type`,
  `contents`.`status` AS `status`,
  `contents`.`password` AS `password`,
  `contents`.`commentsNum` AS `commentsNum`,
  `contents`.`allowComment` AS `allowComment`,
  `contents`.`allowPing` AS `allowPing`,
  `contents`.`allowFeed` AS `allowFeed`,
  `contents`.`parent` AS `parent`
FROM
  `contents`
WHERE
  (`contents`.`type` = 'mock');

-- ----------------------------
-- View structure for public_api_contents
-- ----------------------------
DROP VIEW IF EXISTS `public_api_contents`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `public_api_contents` AS
SELECT
  `contents`.`cid` AS `cid`,
  `contents`.`title` AS `title`,
  `contents`.`slug` AS `slug`,
  `contents`.`created` AS `created`,
  `contents`.`modified` AS `modified`,
  `contents`.`text` AS `text`,
  replace(
    json_extract(`contents`.`text`, '$.method'),
    '"',
    ''
  ) AS `method`,
  replace(json_extract(`contents`.`text`, '$.url'), '"', '') AS `url`,
  replace(
    json_extract(`contents`.`text`, '$.headers'),
    '"',
    ''
  ) AS `headers`,
  replace(json_extract(`contents`.`text`, '$.data'), '"', '') AS `data`,
  `contents`.`order` AS `order`,
  `contents`.`authorId` AS `authorId`,
  `contents`.`template` AS `template`,
  `contents`.`type` AS `type`,
  `contents`.`status` AS `status`,
  `contents`.`password` AS `password`,
  `contents`.`commentsNum` AS `commentsNum`,
  `contents`.`allowComment` AS `allowComment`,
  `contents`.`allowPing` AS `allowPing`,
  `contents`.`allowFeed` AS `allowFeed`,
  `contents`.`parent` AS `parent`
FROM
  `contents`
WHERE
  (`contents`.`type` = 'public_api');

-- ----------------------------
-- View structure for quote_contents
-- ----------------------------
DROP VIEW IF EXISTS `quote_contents`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `quote_contents` AS
SELECT
  `contents`.`cid` AS `cid`,
  `contents`.`title` AS `title`,
  `contents`.`slug` AS `slug`,
  `contents`.`created` AS `created`,
  `contents`.`modified` AS `modified`,
  `contents`.`text` AS `text`,
  `contents`.`order` AS `order`,
  `contents`.`authorId` AS `authorId`,
  `contents`.`template` AS `template`,
  `contents`.`type` AS `type`,
  `contents`.`status` AS `status`,
  `contents`.`password` AS `password`,
  `contents`.`commentsNum` AS `commentsNum`,
  `contents`.`allowComment` AS `allowComment`,
  `contents`.`allowPing` AS `allowPing`,
  `contents`.`allowFeed` AS `allowFeed`,
  `contents`.`parent` AS `parent`
FROM
  `contents`
WHERE
  (`contents`.`type` = 'quote');

-- ----------------------------
-- View structure for typecho_comments
-- ----------------------------
DROP VIEW IF EXISTS `typecho_comments`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_comments` AS
SELECT
  `comments`.`coid` AS `coid`,
  `comments`.`cid` AS `cid`,
  `comments`.`created` AS `created`,
  `comments`.`author` AS `author`,
  `comments`.`authorId` AS `authorId`,
  `comments`.`ownerId` AS `ownerId`,
  `comments`.`mail` AS `mail`,
  `comments`.`url` AS `url`,
  `comments`.`ip` AS `ip`,
  `comments`.`agent` AS `agent`,
  `comments`.`text` AS `text`,
  `comments`.`type` AS `type`,
  `comments`.`status` AS `status`,
  `comments`.`parent` AS `parent`
FROM
  `comments`;

-- ----------------------------
-- View structure for typecho_contents
-- ----------------------------
DROP VIEW IF EXISTS `typecho_contents`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_contents` AS
SELECT
  `contents`.`cid` AS `cid`,
  `contents`.`title` AS `title`,
  `contents`.`slug` AS `slug`,
  `contents`.`created` AS `created`,
  `contents`.`modified` AS `modified`,
  `contents`.`text` AS `text`,
  `contents`.`order` AS `order`,
  `contents`.`authorId` AS `authorId`,
  `contents`.`template` AS `template`,
  `contents`.`type` AS `type`,
  `contents`.`status` AS `status`,
  `contents`.`password` AS `password`,
  `contents`.`commentsNum` AS `commentsNum`,
  `contents`.`allowComment` AS `allowComment`,
  `contents`.`allowPing` AS `allowPing`,
  `contents`.`allowFeed` AS `allowFeed`,
  `contents`.`parent` AS `parent`
FROM
  `contents`;

-- ----------------------------
-- View structure for typecho_fields
-- ----------------------------
DROP VIEW IF EXISTS `typecho_fields`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_fields` AS
SELECT
  `fields`.`cid` AS `cid`,
  `fields`.`name` AS `name`,
  `fields`.`type` AS `type`,
  `fields`.`str_value` AS `str_value`,
  `fields`.`int_value` AS `int_value`,
  `fields`.`float_value` AS `float_value`
FROM
  `fields`;

-- ----------------------------
-- View structure for typecho_metas
-- ----------------------------
DROP VIEW IF EXISTS `typecho_metas`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_metas` AS
SELECT
  `metas`.`mid` AS `mid`,
  `metas`.`name` AS `name`,
  `metas`.`slug` AS `slug`,
  `metas`.`type` AS `type`,
  `metas`.`description` AS `description`,
  `metas`.`count` AS `count`,
  `metas`.`order` AS `order`,
  `metas`.`parent` AS `parent`
FROM
  `metas`;

-- ----------------------------
-- View structure for typecho_options
-- ----------------------------
DROP VIEW IF EXISTS `typecho_options`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_options` AS
SELECT
  `options`.`name` AS `name`,
  `options`.`user` AS `user`,
  `options`.`value` AS `value`
FROM
  `options`;

-- ----------------------------
-- View structure for typecho_relationships
-- ----------------------------
DROP VIEW IF EXISTS `typecho_relationships`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_relationships` AS
SELECT
  `relationships`.`cid` AS `cid`,
  `relationships`.`mid` AS `mid`
FROM
  `relationships`;

-- ----------------------------
-- View structure for typecho_users
-- ----------------------------
DROP VIEW IF EXISTS `typecho_users`;

CREATE ALGORITHM = UNDEFINED SQL SECURITY DEFINER VIEW `typecho_users` AS
SELECT
  `users`.`uid` AS `uid`,
  `users`.`name` AS `name`,
  `users`.`password` AS `password`,
  `users`.`mail` AS `mail`,
  `users`.`url` AS `url`,
  `users`.`screenName` AS `screenName`,
  `users`.`created` AS `created`,
  `users`.`activated` AS `activated`,
  `users`.`logged` AS `logged`,
  `users`.`group` AS `group`,
  `users`.`authCode` AS `authCode`
FROM
  `users`;

SET
  FOREIGN_KEY_CHECKS = 1;