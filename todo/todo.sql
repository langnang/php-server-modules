-- start::create table
CREATE TABLE `todo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '编号',
  `title` VARCHAR(255) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
    `description` VARCHAR(255) CHARACTER
  SET
    utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述',
    `done` INT(1) NULL DEFAULT NULL COMMENT '是否已完成',
    `parent` INT(11) NULL DEFAULT NULL COMMENT '父本',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER
SET
  = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- end::create table
-- start::drop table
-- end::drop table
-- start::insert
INSERT INTO
  `todo` (`title`, `description`, `done`)
VALUES
  (?, ?, ?);

-- end::insert
-- start::delete
-- end::delete
-- start::update
-- end::update
-- start::select
-- end::select