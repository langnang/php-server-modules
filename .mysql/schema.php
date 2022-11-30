<?php

namespace Langnang\SqlGenerator\MySqlGenerator;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . "/table.php";

class MySqlSchema extends RootModel
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
  public $name;
  /**
   * @OA\Property(default=NULL, title="字符集")
   * @var string
   */
  public $character_set = "utf8";
  /**
   * @OA\Property(default=NULL, title="字符序")
   * @var string
   */
  public $collation = "utf8_general_ci";
  /**
   * @OA\Property(default=NULL, title="SQL存储路径")
   * @var string
   */
  public $sql_path = "";
  // 表中列名与模块参数对应
  static $INFORMATION_SCHEMA = [
    'CATALOG_NAME' => 'catalog',
    'SCHEMA_NAME' => 'name',
    'DEFAULT_CHARACTER_SET_NAME' => 'character_set',
    'DEFAULT_COLLATION_NAME' => 'collation',
    'SQL_PATH' => 'sql_path',
  ];
  function __root__construct(array $args)
  {
    foreach (self::$INFORMATION_SCHEMA as $key => $name) {
      isset($args[$key]) ? $this->__set($name, $args[$key]) : NULL;
    }
  }
  public $tables = [];
  function set_tables(array $tables = [])
  {
    $this->tables = array_reduce($tables, function ($list, $item) {
      $table = new MySqlTable($item);
      $list[$table->name] = $table;
      return $list;
    }, []);
  }
  /**
   * 查询表列表信息
   */
  function generate_select_tables()
  {
    return "SELECT * FROM `information_schema`.`TABLES` WHERE `TABLE_SCHEMA` = '{$this->name}';";
  }

  function save_info()
  {
  }
}
