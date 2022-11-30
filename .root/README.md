# Root

## RootModel

**Properties**

| 参数 | 说明 | 类型 | 可选值 | 默认值 |
| ---- | ---- | ---- | ------ | ------ |
|      |      |      |        |        |

**Methods**

| 事件名称      | 说明 | 参数          |
| ------------- | ---- | ------------- |
| \_\_construct |      |               |
| \_\_set       |      | $name, $value |
| \_\_get       |      | $name         |

## RootInterface

**Properties**

| 参数 | 说明 | 类型 | 可选值 | 默认值 |
| ---- | ---- | ---- | ------ | ------ |
|      |      |      |        |        |

**Methods**

| 事件名称 | 说明 | 参数 |
| -------- | ---- | ---- |
|          |      |      |

## RootController

**Properties**

| 参数         | 说明 | 类型 | 可选值 | 默认值        |
| ------------ | ---- | ---- | ------ | ------------- |
| \_class      |      |      |        | \_\_CLASS\_\_ |
| \_table      |      |      |        |               |
| \_table_path |      |      |        |               |
|              |      |      |        |               |
|              |      |      |        |               |
|              |      |      |        |               |
|              |      |      |        |               |

**Methods**

| 事件名称                  | 说明                     | 参数                          |
| ------------------------- | ------------------------ | ----------------------------- |
| set\_\_table              |                          |                               |
| get\_\_table              |                          |                               |
| generate_schema_structure | 生成数据库表结构         |
| execute_table_exists      | 执行检测表操作           |                               |
| table_exists              | 检测表是否已存在         |                               |
| execute_create_table      | 执行新建表操作           |                               |
| create_table              | 新建表                   |                               |
| execute_insert_item       | 执行单条新增操作         | $vars                         |
| insert_item               | 单条新增                 | $vars                         |
| execute_insert_list       | 执行批量新增操作         | $vars                         |
| insert_list               | 批量新增                 | $vars                         |
| upload_list               |                          | $vars                         |
| execute_delete_list       | 执行批量删除操作         | $vars                         |
| delete_list               | 批量删除                 | $vars                         |
| execute_update_item       | 执行单条修改操作         | $vars                         |
| update_item               | 单条修改                 | $vars                         |
| execute_select_count      | 执行计数查询操作         | $vars                         |
| select_count              | 计数查询                 | $vars                         |
| execute_select_item       | 执行单条查询操作         | $vars                         |
| select_item               | 单条执行操作查询         | $vars                         |
| execute_select_rand       | 执行随机查询操作         | $vars                         |
| select_rand               | 随机查询                 | $vars                         |
| execute_select_list       | 执行批量查询操作         | $vars                         |
| select_list               | 批量查询，可分页         | $vars                         |
| execute_select_tree       | 执行树形查询操作         | $vars                         |
| select_tree               | 树形查询                 | $vars                         |
| before                    | 调用方法前               | $method, $vars                |
| generated                 | 生成语句后               | $sql, $method, $vars          |
| executed                  | 执行操作后               | $result, $sql, $method, $vars |
| after                     | 调用方法后               | $method, $vars                |
| get_row                   | 处理查询后的列表对应数据 | $row, $fields, $vars          |
