<?php

namespace Langnang\SqlGenerator\MySqlGenerator;

require_once __DIR__ . '/../.root/models.php';
require_once __DIR__ . '/../.root/controllers.php';

use Langnang\Module\Root\RootModel;

class MySqlColumn extends RootModel
{
  /**
   * @OA\Property(default=NULL, title="目录")
   * @var string
   */
  public $catalog;
  /**
   * @OA\Property(default=NULL, title="数据库名")
   * @var string
   */
  public $schema;
  /**
   * @OA\Property(default=NULL, title="数据表名")
   * @var string
   */
  public $table;
  /**
   * @OA\Property(default=NULL, format="string", title="名称")
   * @var string
   */
  public $name;
  /**
   * @OA\Property(default=NULL, format="string", title="列的次序")
   * @var string
   */
  public $position = 0;
  /**
   * @OA\Property(default=NULL, format="string", title="默认值")
   * @var string
   */
  public $default;
  /**
   * @OA\Property(default=NULL, format="string", title="可为空")
   * @var string
   */
  public $nullable;
  function set_nullable($value)
  {
    if (strtoupper($value) === 'NO') {
      $this->nullable = false;
    } else {
      $this->nullable = (bool)$value;
    }
  }
  /**
   * @OA\Property(default=NULL, format="int", title="以字符为单位的最大长度")
   * @var string
   */
  public $character_maximum_length;
  /**
   * @OA\Property(default=NULL, format="int", title="以字节为单位的最大长度")
   * @var string
   */
  public $character_octet_length;
  /**
   * @OA\Property(default=NULL, format="int", title="存储数据的实际长度")
   * @var string
   */
  public $numeric_precision;
  /**
   * @var string
   */
  public $numeric_scale;
  /**
   * @OA\Property(default=NULL, title="字符集")
   * @var string
   */
  public $character_set;
  /**
   * @OA\Property(default=NULL, title="字符序")
   * @var string
   */
  public $collation;
  /**
   * @OA\Property(default=NULL, format="string", title="数据类型")
   * @var string
   */
  public $type;
  function get_type()
  {
    $list = preg_split("/(\(|\)|,| )/", $this->type);
    return ["data_type" => $list[0], "max_length" => is_numeric($list[1]) ? (int)$list[1] : null, "unsigned" => null];
  }
  private $type_options = [
    "number" => ["tinyint", "smallint", "mediumint", "int", "integer", "bigint", "float", "double", "decimal"],
    "datetime" => ["date", "time", "year", "datetime", "timestamp"],
    "char" => ["char", "varchar",],
    "bolb" => ["tinyblob", "blob", "mediumblob", "longblob",],
    "text" => ["tinyTEXT", "text", "mediumTEXT", "longtext",],
  ];
  /**
   * @OA\Property(default=NULL, format="string", title="字段涉及的键")
   * @var string
   * NULL,'PRI','UNI','MUL'
   * 1. 如果Key是空的, 那么该列值的可以重复, 表示该列没有索引, 或者是一个非唯一的复合索引的非前导列
   * 2. 如果Key是PRI,  那么该列是主键的组成部分
   * 3. 如果Key是UNI,  那么该列是一个唯一值索引的第一列(前导列),并别不能含有空值(NULL)
   * 4. 如果Key是MUL,  那么该列的值可以重复, 该列是一个非唯一索引的前导列(第一列)或者是一个唯一性索引的组成部分但是可以含有空值NULL
   * 同时满足上述4种情况的多种，显示的Key值按照优先级来显示 PRI->UNI->MUL
   */
  public $key;
  /**
   * @OA\Property(default=NULL, format="string", title="额外信息")
   * @var string
   */
  public $extra;
  /**
   * @OA\Property(default=NULL, format="string", title="权限")
   * @var string
   */
  public $privileges;
  /**
   * @OA\Property(default=NULL, format="string", title="注释")
   * @var string
   */
  public $comment;
  /**
   * type == number
   * @OA\Property(default=false, format="bool", title="自动新增")
   * @var string
   */
  public $auto_increment = false;
  /**
   * @var bool 
   */
  public $unsigned = false;
  /**
   * 填充零
   * @var bool 
   */
  public $zerofill = false;
  /**
   * @var int
   */
  public $key_length;
  /**
   * @var bool
   */
  public $binary = false;
  /**
   * @var bool
   */
  public $on_update_current_timestamp = false;
  /**
   * @OA\Property(default=NULL, format="int", title="长度")
   * @var int
   */
  public $length;
  /**
   * @OA\Property(default=NULL, format="int", title="小数位数")
   * @var int
   */
  public $decimals = 0;
  /**
   * @OA\Property(default=false, format="bool", title="不为空")
   * @var bool
   */
  public $not_null = false;
  /**
   * @OA\Property(default=NULL, format="", title="可选值，分组形式")
   * @var string
   */
  public $options = ["default" => []];
  // 表中列名与模块参数对应
  static $INFORMATION_SCHEMA = [
    'TABLE_CATALOG' => 'catalog',
    'TABLE_SCHEMA' => 'schema',
    'TABLE_NAME' => 'table',
    'COLUMN_NAME' => 'name',
    'ORDINAL_POSITION' => 'position',
    'COLUMN_DEFAULT' => 'default',
    'IS_NULLABLE' => 'nullable',
    // 'DATA_TYPE' => 'type',
    'CHARACTER_MAXIMUM_LENGTH' => 'character_maximum_length',
    'CHARACTER_OCTET_LENGTH' => 'character_octet_length',
    'NUMERIC_PRECISION' => 'numeric_precision',
    'NUMERIC_SCALE' => 'numeric_scale',
    'CHARACTER_SET_NAME' => 'character_set',
    'COLLATION_NAME' => 'collation',
    'COLUMN_TYPE' => 'type',
    'COLUMN_KEY' => 'key',
    'EXTRA' => 'extra',
    'PRIVILEGES' => 'privileges',
    'COLUMN_COMMENT' => 'comment',
  ];
  function __root__construct(array $args)
  {
    foreach (self::$INFORMATION_SCHEMA as $key => $name) {
      isset($args[$key]) ? $this->__set($name, $args[$key]) : NULL;
    }
  }
  /**
   * @OA\Property(default=NULL, format="string", title="查询条件")
   * @var string
   */
  public $condition;
  /**
   * @OA\Property(default=NULL, format="bool", title="可以修改")
   * @var boolean
   */
  public $modifiable = false;
  /**
   * @OA\Property(default=NULL, format="string", title="查询排序")
   * @var string
   */
  public $order;
  /**
   * @OA\Property(default=NULL, format="string", title="赋值处理方式")
   * @var string
   */
  public $decode;
  /**
   * 根据配置的解码操作，解码传入的数据，符合数据类型
   */
  function decode_value(string $value)
  {
    if (in_array($this->get_type()['data_type'], $this->type_options['number'])) $value = (int)$value;

    if (empty($this->decode)) return $value;

    return filter($value, $this->decode);
  }
  /**
   * @OA\Property(default=NULL, format="string", title="取值处理方式")
   * @var string
   */
  public $encode;
  /**
   * 根据配置的编码操作，编码传入的数据，符合数据类型
   */
  function encode_value($value)
  {
    if (empty($this->encode)) return $value;

    return filter($value, $this->encode);
  }


  function generate_create_column(array $table_option)
  {
    return "`{$this->name}` {$this->type}" . ($this->nullable ? ' NULL ' : ' NOT NULL')  . " {$this->extra}";
    // return "\t`{$this->name}` "
    //   // type
    //   . (in_array($this->type, $this->type_options['text']) ? $this->type : "{$this->type}({$this->length})")
    //   // number unsigned
    //   . ($this->unsigned ? " UNSIGNED " : "")
    //   // charset
    //   . (in_array($this->type, array_merge($this->type_options['char'], $this->type_options['text'])) ? " CHARACTER SET {$table_option['character_set']} COLLATE {$table_option['collate']} " : " ")
    //   // is null
    //   . (($this->not_null || in_array($this->name, $table_option['primary_keys'])) ? 'NOT' : '') . " NULL "
    //   // number auto_increment
    //   . ($this->auto_increment ? " AUTO_INCREMENT " : "")
    //   // default
    //   . "DEFAULT " . (is_null($this->default) ? "NULL" : (in_array($this->type, array_merge($this->type_options['char'], $this->type_options['text'])) ? "'{$this->default}'" : "{$this->default}"))
    //   // comment  
    //   . (is_null($this->comment) ? "" : " COMMENT '{$this->comment}'");
  }

  function generate_update_column()
  {
  }

  function generate_delete_column()
  {
  }

  function generate_delete_list_condition(array $value = [])
  {
    // 批量操作，默认数组格式
    return " `{$this->name}` IN ("
      . substr(array_reduce((array)$value, function ($total, $item) {
        if (in_array($this->type, ['int', 'bigint'])) {
          return $total . ", {$item}";
        } else {
          return $total . ", '{$item}'";
        }
      }, ""), 2)
      . ")";
  }
}
