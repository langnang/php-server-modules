# MySQLGenerator

> MySQLGenerator - MySQL 语句生成器

## Database

**Properties**

| 参数     | 说明   | 类型   | 可选值 | 默认值 |
| -------- | ------ | ------ | ------ | ------ |
| name     | 名称   | string |
| host     | 域名   | string |
| host     | 端口   | number |        | 3306   |
| username | 用户   | string |
| password | 密码   | string |
| schemas  | 数据库 | array  |

**Methods**

| 事件名称                | 说明             | 参数 |
| ----------------------- | ---------------- | ---- |
| set_schemas             |
| generate_select_schemas | 生成查表结构语句 |

## Schema

**Properties**

| 参数          | 说明         | 类型   | 可选值 | 默认值          |
| ------------- | ------------ | ------ | ------ | --------------- |
| catalog       | 目录         | string |
| name          | 名称         | string |
| character_set | 字符集       | string |        | utf8            |
| collation     | 字符序       | string |        | utf8_general_ci |
| sql_path      | SQL 存储路径 | string |
| tables        | 数据表       | array  |

**Methods**

| 事件名称               | 说明           | 参数 |
| ---------------------- | -------------- | ---- |
| set_tables             |                |
| generate_select_tables | 查询表列表信息 |

## Table

**Properties**

| 参数            | 说明                                     | 类型   | 可选值 | 默认值  |
| --------------- | ---------------------------------------- | ------ | ------ | ------- |
| catalog         | 目录                                     |
| schema          | 数据库名                                 |
| name            | 名称                                     |        |        |         |
| type            | 类型                                     |
| engine          | 存储引擎                                 |        |        | MyISAM  |
| version         | 版本                                     |
| row_format      | 行记录格式                               |        |        | Dynamic |
| rows            | 行数                                     | number |
| avg_row_length  | 平均每行字节数                           |
| data_length     | 整个表的数据量                           |
| max_data_length | 表可以容纳的最大数据量                   |
| index_length    | 索引占用磁盘的空间大小                   |
| data_free       | 碎片大小                                 |
| auto_increment  | 主键自动增长                             |
| create_time     | 创建时间                                 |
| update_time     | 更新时间                                 |
| check_time      | 检查时间                                 |
| collation       | 字符序                                   |
| checksum        | 如果启用，则对整个表的内容计算时的校验和 |
| create_options  | 指表创建时的其他所有选项                 |
| comment         | 注释                                     |
| columns         | 列                                       | array  |
|                 |                                          |

**Methods**

| 事件名称                     | 说明                     | 参数 |
| ---------------------------- | ------------------------ | ---- |
| generate_table_exists        | 生成检测表是否存在语句   |
| generate_create_table        | 生成建表语句             |
| generate_drop_table          | 生成删表语句             |
| generate_update_table        | 根据列结构生成改表语句   |
| generate_select_columns      | 查询表结构               |
| generate_insert_item         | 生成单条插入语句         |
| generate_insert_list         |                          |
| generate_delete_list         | 生成批量删除语句         |
| generate_update_item         | 生成单条修改语句         |
| generate_select_list         | 生成多条查表语句         |
| generate_select_item         | 生成单条查表语句         |
| generate_select_count        | 生成计数查表语句         |
| generate_select_tree         |                          |
| generate_select_concat       |                          |
| generate_select_group_concat |                          |
| generate_select_rand         | 生成随机查询语句         |
| generate_where_condition     | 生成条件语句             |
| generate_sort_order          | 生成排序语句             |
| get_row                      | 根据列配置，转换生成数据 |
| unset_primary_keys           | 删除关键字对应的值       |
| primary_key_exists           | 检测关键字是否存在       |
|                              |                          |
|                              |                          |
|                              |                          |

## Column

**Properties**

| 参数                     | 说明                   | 类型 | 可选值 | 默认值 |
| ------------------------ | ---------------------- | ---- | ------ | ------ |
| catalog                  | 目录                   |      |        |        |
| schema                   | 数据库名               |      |        |        |
| table                    | 数据表名               |      |        |        |
| name                     | 名称                   |      |        |        |
| position                 | 列的次序               |      |        |        |
| default                  | 默认值                 |      |        |        |
| nullable                 | 可为空                 |      |        |        |
| character_maximum_length | 以字符为单位的最大长度 |      |        |        |
| character_octet_length   | 以字节为单位的最大长度 |      |        |        |
| numeric_precision        | 存储数据的实际长度     |      |        |        |
| numeric_scale            |                        |      |        |        |
| character_set            | 字符集                 |      |        |        |
| collation                |                        |      |        |        |
| type                     | 数据类型，长度         |      |        |        |
| key                      | 字段涉及的键           |      |        |        |
| extra                    | 额外信息               |      |        |        |
| privileges               | 权限                   |      |        |        |
| comment                  | 注释                   |      |        |        |
|                          |                        |      |        |        |
|                          |                        |      |        |        |
|                          |                        |      |        |        |

**Methods**

| 事件名称     | 说明 | 参数 |
| ------------ | ---- | ---- |
| decode_value |      |      |
| encode_value |      |      |
|              |      |      |
|              |      |      |
|              |      |      |
