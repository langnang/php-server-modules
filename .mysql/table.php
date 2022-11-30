<?php

namespace Langnang\SqlGenerator\MySqlGenerator;

use Exception;
use Langnang\Module\Root\RootModel;

require_once __DIR__ . "/column.php";
class MySqlTable extends RootModel
{
  /**
   * @OA\Property(default=NULL, title="目录")
   * @var string
   */
  public $catalog;
  /**
   * @OA\Property(default=NULL, title="名称")
   * @var string
   */
  public $schema;
  /**
   * @OA\Property(default=NULL, title="名称")
   * @var string
   */
  public $name;
  /**
   * @OA\Property(default=NULL, title="类型")
   * @var string
   */
  public $type;
  /**
   * @OA\Property(default=NULL, title="存储引擎")
   * @var string
   */
  public $engine = "MyISAM";
  /**
   * @OA\Property(default=NULL, title="版本")
   * @var string
   */
  public $version;
  /**
   * @OA\Property(default=NULL, title="行记录格式")
   * @var string
   */
  public $row_format = 'Dynamic';
  /**
   * @OA\Property(default=0, title="行数")
   * @var int
   */
  public $rows = 0;
  /**
   * @OA\Property(default=0, title="平均每行字节数")
   * @var int
   */
  public $avg_row_length = 0;
  /**
   * @OA\Property(default=0, title="整个表的数据量(单位：字节)")
   * @var int
   */
  public $data_length = 0;
  /**
   * @OA\Property(default=0, title="表可以容纳的最大数据量")
   * @var int
   */
  public $max_data_length = 0;
  /**
   * @OA\Property(default=0, title="索引占用磁盘的空间大小")
   * @var int
   */
  public $index_length = 0;
  /**
   * @OA\Property(default=0, title="碎片大小")
   * @var int
   */
  public $data_free = 0;
  /**
   * @OA\Property(default=0, title="主键自动增长")
   * @var int
   */
  public $auto_increment = 0;
  /**
   * @OA\Property(default=0, title="创建时间")
   * @var string
   */
  public $create_time;
  /**
   * @OA\Property(default=0, title="更新时间")
   * @var string
   */
  public $update_time;
  /**
   * @OA\Property(default=0, title="检查时间")
   * @var string
   */
  public $check_time;
  /**
   * @OA\Property(default=0, title="字符序")
   * @var string
   */
  public $collation = 'utf8_general_ci';
  function get_character_set()
  {
    return explode("_", $this->collation)[0];
  }
  /**
   * @OA\Property(default=0, title="如果启用，则对整个表的内容计算时的校验和")
   * @var string
   */
  public $checksum;
  /**
   * @OA\Property(default=0, title="指表创建时的其他所有选项")
   * @var string
   */
  public $create_options;
  /**
   * @OA\Property(default=0, title="注释")
   * @var string
   */
  public $comment;
  // 表中列名与模块参数对应
  static $INFORMATION_SCHEMA = [
    'TABLE_CATALOG' => 'catalog',
    'TABLE_SCHEMA' => 'schema',
    'TABLE_NAME' => 'name',
    'TABLE_TYPE' => 'type',
    'ENGINE' => 'engine',
    'VERSION' => 'version',
    'ROW_FORMAT' => 'row_format',
    'TABLE_ROWS' => 'rows',
    'AVG_ROW_LENGTH' => 'avg_row_length',
    'DATA_LENGTH' => 'data_length',
    'MAX_DATA_LENGTH' => 'max_data_length',
    'INDEX_LENGTH' => 'index_length',
    'DATA_FREE' => 'data_free',
    'AUTO_INCREMENT' => 'auto_increment',
    'CREATE_TIME' => 'create_time',
    'UPDATE_TIME' => 'update_time',
    'CHECK_TIME' => 'check_time',
    'TABLE_COLLATION' => 'collation',
    'CHECKSUM' => 'checksum',
    'CREATE_OPTIONS' => 'create_options',
    'TABLE_COMMENT' => 'comment',
  ];
  function __root__construct(array $args)
  {
    foreach (self::$INFORMATION_SCHEMA as $key => $name) {
      isset($args[$key]) ? $this->__set($name, $args[$key]) : NULL;
    }
  }
  /**
   * 列
   * @var array
   */
  public $columns = [];
  function set_columns(array $columns)
  {
    // 主键列表
    $primary_keys = [];
    // 索引
    $indexes = [];
    // 数组列排序
    $column_positions = [];
    // 可以修改的列
    $column_modifiable = [];

    $columns = array_reduce($columns, function ($table, $item) use (&$primary_keys, &$indexes, &$column_positions, &$column_modifiable) {
      $column = new MySqlColumn($item);
      $table[$column->name] = $column;

      if ($column->modifiable == true) array_push($column_modifiable, $column->name);

      $column_positions[$column->name] = $column->position;
      switch ($column->key) {
        case "PRI":
          array_push($primary_keys, $column->name);
          break;
        case "MUL":
          array_push($indexes, $column->name);
          break;
        default:
          break;
      }
      return $table;
    }, []);
    // asort() - 根据数组的值，对数组进行升序排列
    foreach ($column_positions as $column => $position) {
      $column_positions[$column] = $columns[$column];
    }

    $this->columns = $column_positions;
    if (!empty($primary_keys)) $this->primary_keys = $primary_keys;
    $this->indexes = $indexes;

    $this->modifiable_keys = $column_modifiable;
  }
  /**
   * 关联表
   * @var array
   */
  protected $relations_tables = [];
  function set_relations_tables($tables = [])
  {
    $this->relations_tables = array_reduce($tables, function ($relations_tables, $item) {
      $table = new MySqlTable($item);
      $relations_tables[$table->name] = $table;
      return $relations_tables;
    }, []);
  }
  // 关联列
  protected $relation_on = [];
  /**
   * 根据列结构生成主键列表
   */
  protected $primary_keys = [];
  function get_primary_keys()
  {
    return $this->primary_keys;
  }
  /**
   * 根据列结构生成可修改列
   */
  protected $modifiable_keys = [];
  /**
   * 用于查询树形数据的层次键
   * 子健，父键
   */
  protected $hierarchical_keys = [];
  /**
   * 索引
   * @var array
   */
  protected $indexes = [];
  function get_indexes()
  {
    return $this->indexes;
  }
  /**
   * 生成检测表是否存在语句
   */
  function generate_table_exists(): string
  {
    return "SELECT COUNT(*) FROM `information_schema`.`TABLES` WHERE `table_name` = '{$this->name}';";
  }
  /**
   * @description 根据列结构生成建表语句
   */
  function generate_create_table(): string
  {
    return "CREATE TABLE IF NOT EXISTS `{$this->name}` (\n"
      . substr(
        array_reduce($this->columns, function ($t, $column) {
          return $t . $column->generate_create_column((array)$this) . ", \n";
        }, "")
          // primary_keys
          . (empty($this->primary_keys) ? "" : ("\tPRIMARY KEY (`" . implode("`, `", $this->primary_keys) . "`) USING BTREE, \n"))
          // indexes
          . (empty($this->indexes) ? "" : array_reduce($this->indexes, function ($t, $v) {
            return $t . "\tINDEX `{$v}`(`$v`) USING BTREE, \n";
          }, "")),
        0,
        -3
      )
      . "\n) ENGINE = {$this->engine} AUTO_INCREMENT = {$this->auto_increment} CHARACTER SET = {$this->get_character_set()} COLLATE = {$this->collation} ROW_FORMAT = {$this->row_format};";
  }
  // 生成删表语句
  function generate_drop_table(): string
  {
    return "DROP TABLE IF EXISTS `{$this->name}`;";
  }
  // 根据列结构生成改表语句
  function generate_update_table(): string
  {
    return "ALTER TABLE `{$this->name}` 
  ;";
  }
  /**
   * @description 根据数据生成单条插入语句
   * @param array $row 需要插入表的单条数据
   * @param array $columns
   */
  function generate_insert_item(array $row, array $columns = []): string
  {
    return "
INSERT INTO `{$this->name}` 
 ("
      . substr(array_reduce($this->columns, function ($total, $column) use ($row) {
        if (!is_null($row[$column->name])) {
          return $total . ", `{$column->name}`";
        }
        return $total;
      }, ""), 2)
      . ") 
VALUES 
 (" .
      substr(array_reduce($this->columns, function ($total, $column) use ($row) {
        if (!is_null($row[$column->name])) {
          if (is_int($row[$column->name])) {
            return $total . ", {$column->encode_value($row[$column->name])}";
          } else {
            return $total . ", '" . addslashes($column->encode_value($row[$column->name])) . "'";
          }
        }
        return $total;
      }, ""), 2)
      .
      ");";
  }
  function generate_insert_list(array $rows): string
  {
    return array_reduce($rows, function ($total, $row) {
      return $total . $this->generate_insert_item($row);
    }, "");
  }
  // 生成批量删除语句
  function generate_delete_list($row): string
  {
    return "DELETE FROM `{$this->name}` WHERE " . substr(array_reduce($this->primary_keys, function ($total, $key) use ($row) {
      return $total . " AND " . $this->columns[$key]->generate_delete_list_condition($row[$key]);
    }, ""), 4);
  }
  // 生成单条修改语句
  function generate_update_item($row): string
  {
    return "UPDATE `{$this->name}`
  SET "
      . substr(array_reduce($this->columns, function ($total, $column) use ($row) {
        if (!is_null($row[$column->name])) {
          if (is_int($row[$column->name])) {
            return $total . ", `{$column->name}` = {$column->encode_value($row[$column->name])}";
          } else {
            return $total . ", `{$column->name}` = '" . addslashes($column->encode_value($row[$column->name])) . "'";
          }
        }
        return $total;
      }, ""), 2)
      . " WHERE "
      . substr(array_reduce($this->primary_keys, function ($total, $key) use ($row) {
        if (!is_null($row[$key])) {
          if (is_int($row[$key])) {
            return $total . " AND `{$key}` = {$row[$key]}";
          } else {
            return $total . " AND `{$key}` = '{$this->columns[$key]->encode_value($row[$key])}'";
          }
        }
        return $total;
      }, ""), 4);
  }
  // 生成多条查表语句
  function generate_select_list($row, $page = 1, $size = PHP_INT_MAX): string
  {
    $offset = ($page - 1) * $size;

    return "SELECT * FROM `{$this->name}` {$this->generate_where_condition($row)} {$this->generate_sort_order()} LIMIT {$size} OFFSET {$offset} ";
  }
  // 生成单条查表语句
  function generate_select_item($row, $columns = []): string
  {
    if (empty($columns)) $columns = $this->primary_keys;
    $row = array_reduce($columns, function ($carry, $item) use ($row) {
      $carry[$item] = $row[$item];
      return $carry;
    }, []);
    return "SELECT * FROM `{$this->name}` {$this->generate_where_condition($row)} {$this->generate_sort_order()} LIMIT 1 ";
  }
  /**
   * 生成计数查表语句
   * @param array $row
   * @param array $columns 
   */
  function generate_select_count($row, $columns = []): string
  {
    return "SELECT COUNT(*) AS `_count`"
      . array_reduce((array)$columns, function ($t, $v) {
        return $t . ", `{$v}`";
      }, "")
      . " FROM `{$this->name}`"
      . $this->generate_where_condition($row, $columns)
      // GROUP BY
      . (empty((array)$columns) ? "" : (" GROUP BY "
        . substr(array_reduce((array)$columns, function ($t, $v) {
          return $t . ", `{$v}`";
        }, ""), 2)
      ))
      . $this->generate_sort_order();
  }
  function generate_select_tree(array $row): string
  {
    if (sizeof($this->hierarchical_keys) !== 2) throw new Exception("the hierarchy keys is not configured.");
    $child_key = $this->hierarchical_keys[0];
    $parent_key = $this->hierarchical_keys[1];

    $parent = $this->columns[$parent_key]->encode_value($row[$parent_key]);
    return "select t3.* from (
  select t1.*,
    if(find_in_set(`{$parent_key}`, @pids) > 0, @pids := concat(@pids, ',', `{$child_key}`), 
    if (t1.{$child_key} = {$parent}, {$parent}, 0)) as ischild
  from (select t.* from `{$this->name}` t) t1,
  (select @pids :=  {$parent}) t2
) t3 where ischild != 0 ORDER BY {$child_key}, {$parent_key}";
  }
  // TODO 生成
  function generate_select_concat(array $columns)
  {
    return "SELECT *, CONCAT FROM `{$this->name}`";
  }
  // TODO 生成
  function generate_select_group_concat(array $columns)
  {
    return "SELECT *, CONCAT('[', GROUP_CONCAT(`cid`, ','), ']') AS `cids` FROM `{$this->name}` GROUP BY "
      . substr(array_reduce($this->primary_keys, function ($t, $v) use ($columns) {
        if (in_array($v, array_keys($columns))) return  $t;
        return $t . ", `{$v}`";
      }, ""), 2);
  }
  // 生成随机查询语句
  function generate_select_rand(array $row): string
  {
    return "SELECT * FROM `{$this->name}` {$this->generate_where_condition($row)} ORDER BY RAND() LIMIT 1 ";
  }

  /**
   * 生成条件语句
   * @param array $row 
   * @param array $except 过滤列
   */
  function generate_where_condition($row, $except = []): string
  {
    $result = " WHERE 1 = 1";

    foreach ($this->columns as $name => $column) {
      // 过滤条件
      if (in_array($name, $except)) continue;

      if (!isset($row[$name]) || empty_not_zero($row[$name])) continue;
      // 过滤空查询条件
      if (empty($column->condition)) continue;

      $result .= " AND `{$name}` " . str_replace("?", $row[$name], $column->condition);
    }

    return $result;
  }
  // 生成排序语句
  function generate_sort_order(): string
  {
    $columns = array_filter($this->columns, function ($column) {
      return !empty($column->order);
    });
    $columns = array_reduce($columns, function ($t, $column) {
      if ($column->order > 0) $t[$column->name] = "ASC";
      elseif ($column->order < 0) $t[$column->name] = "DESC";
      return $t;
    }, []);
    if (sizeof($columns) == 0) return "";
    $result = "";
    foreach ($columns as $column => $order) {
      $result .= ", `{$column}` {$order}";
    }
    return " ORDER BY " . substr($result, 2);
  }

  /**
   * 根据列配置，转换生成数据
   */
  function get_row($row)
  {
    $result = [];
    foreach ($this->columns as $name => $column) {
      if (!isset($row[$name])) continue;
      $result[$name] = $column->decode_value($row[$name]);
    }
    return $result;
  }
  function unset_columns($row, $columns = [])
  {
    foreach ($columns as $name) {
      unset($row[$name]);
    }
    return $row;
  }
  // 删除关键字对应的值
  function unset_primary_keys($row)
  {
    return $this->unset_columns($row, $this->primary_keys);
  }
  function columns_exist($row, $columns = [])
  {
    foreach ($columns as $name) {
      // 未声明变量 或者 （变量为空且不为零）
      if (!isset($row[$name]) || (empty($row[$name]) && $row[$name] !== 0)) return $name;
    }
    return true;
  }
  // 检测关键字是否存在
  function primary_key_exists($row)
  {
    return $this->columns_exist($row, $this->primary_keys);
  }
  /**
   * 查询表结构
   */
  function generate_select_columns()
  {
    return "SELECT * FROM `information_schema`.`COLUMNS` WHERE `TABLE_NAME` = '{$this->name}';";
  }
}

class MySqlIndex extends RootModel
{
  public $name;
  public $columns;
  public $type;
  public $method;
  public $comment;
}

class MysqlForeignKey extends RootModel
{
  public $name;
  public $columns;
  public $referenced_scheme;
  public $referenced_table;
  public $referenced_fiels;
}
class MysqlTrigger extends RootModel
{
}
