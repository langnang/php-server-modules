<?php

namespace Langnang\SqlGenerator\MySqlGenerator;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . "/schema.php";

class MySql extends RootModel
{
  /**
   * @OA\Property(default=NULL, title="名称")
   * @var string
   */
  public $name;
  /**
   * @OA\Property(default=NULL, title="域名")
   * @var string
   */
  public $host;
  /**
   * @OA\Property(default=3306, title="端口")
   * @var int
   */
  public $port = 3306;
  /**
   * @OA\Property(default=NULL, title="用户")
   * @var string
   */
  public $username;
  /**
   * @OA\Property(default=NULL, title="密码")
   * @var string
   */
  public $password;
  /**
   * @OA\Property(default=NULL, title="数据表")
   * @var object
   */
  public $schemas = [];
  function set_schemas(array $schemas)
  {
    $this->schemas = array_reduce($schemas, function ($list, $schema) {
      $schema = new MySqlSchema($schema);
      $list[$schema->name] = $schema;
      return $list;
    }, []);
  }
  /**
   * 生成查表结构语句
   */
  static function generate_select_schemas()
  {
    return "SELECT * FROM `information_schema`.`SCHEMATA`;";
  }

  function generate_select_tables($schemas = [])
  {
    return "SELECT * FROM `information_schema`.`TABLES` " . (empty($schemas) ? "" : "WHERE `TABLE_SCHEMA` IN (" .
      substr(array_reduce($schemas, function ($carry, $item) {
        return "$carry, '$item'";
      }, ""), 2)
      . ")") . ";";
  }

  function generate_select_columns($schemas = [])
  {
    return "SELECT * FROM `information_schema`.`COLUMNS` " . (empty($schemas) ? "" : "WHERE `TABLE_SCHEMA` IN (" .
      substr(array_reduce($schemas, function ($carry, $item) {
        return "$carry, '$item'";
      }, ""), 2)
      . ")") . ";";
  }
}
