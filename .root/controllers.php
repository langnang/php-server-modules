<?php

namespace Langnang\Module\Root;

use Exception;
use Langnang\SqlGenerator\MySqlGenerator\MySqlTable;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/interfaces.php';
require_once __DIR__ . '/models.php';

class RootController extends RootModel
{
  protected $_driver;
  protected $_schema;
  protected $_table;
  protected $_column;
  protected $_class;
  protected $_table_path;
  protected $_table_name;
  protected $allow_insert_columns = [];
  protected $allow_update_column = [];
  function set__table(array $table)
  {
    // if (!file_exists(__DIR__ . '/schema.json')) throw new Exception('no schema.json found.');
    // else {
    // }

    // $tables = json_decode(file_get_contents(__DIR__ . '/schema.json'), true);

    // if (!isset($tables[$this->_table_name])) throw new Exception("empty table for {$this->_table_name}");

    // $this->_table = new MySqlTable($tables[$this->_table_name]);

    $this->_table = new MySqlTable($table);
    // if (!$this->table_exists()) {
    // $created = $this->create_table();
    // @warning 后续操作将会导致页码首次请求出现异常，推测为处理延迟
    // if (!$created) throw new Exception("create table {$this->_table->name} failed.");
    // $this->execute_insert_list((array)$table['data']);
    // } else {
    // TODO 检测表结构与配置是否一致
    // TODO 若不一致，根据配置更新表结构
    // }
  }
  function get__table()
  {
    return $this->_table;
  }
  private function generate_schema_structure()
  {
  }
  // 执行操作>>检测表是否已存在
  function execute_table_exists()
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_table_exists = $this->_table->generate_table_exists();
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_table_exists', 'value'  => $sql_table_exists, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $count = (int)$_CONNECTION->fetchOne($sql_table_exists);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_table_exists', 'value'  => (string)$count, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = $count === 1;
    $_API_LOGGER->debug(__METHOD__, array('var' => 'result', 'value'  => (string)$result, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }

  function table_exists()
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    $result = $this->execute_table_exists();
    $_API_LOGGER->debug(__METHOD__, array('var' => 'result', 'value'  => (string)$result, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }
  // 执行操作>>创建表
  function execute_create_table()
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_create_table = $this->_table->generate_create_table();
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_create_table', 'value'  => $sql_create_table, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = $_CONNECTION->executeQuery($sql_create_table);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_create_table', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function create_table()
  {
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    return $this->execute_create_table();
  }

  // 执行操作>>单条新增
  function execute_insert_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_insert_item = $this->_table->generate_insert_item($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_insert_item', 'value'  => $sql_insert_item, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $_CONNECTION->executeQuery($sql_insert_item);
    $lastInsertId = (int)$_CONNECTION->lastInsertId();
    $_API_LOGGER->debug(__METHOD__, array('var' => 'lastInsertId', 'value'  => $lastInsertId, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = [
      $this->_table->primary_keys[0] => $lastInsertId
    ];
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function insert_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    // 删除 primary_keys
    $vars = $this->_table->unset_primary_keys($vars);

    $result = $this->execute_insert_item($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  // 执行操作>>批量新增
  function execute_insert_list(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_insert_list = $this->_table->generate_insert_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_insert_list', 'value'  => $sql_insert_list, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = $_CONNECTION->executeQuery($sql_insert_list);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'execute::sql_insert_list', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function insert_list(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    // 接受传值，默认为$_POST[0];
    $list = $vars[0];

    $result = $this->execute_insert_list($list);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }


  // 执行操作>>批量查询
  function execute_delete_list(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_delete_list = $this->_table->generate_delete_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'sql_delete_list', 'value'  => $sql_delete_list, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = $_CONNECTION->executeQuery($sql_delete_list);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'execute::sql_delete_list', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }
  function delete_list(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));

    if ($this->_table->primary_key_exists($vars) !== true) throw new Exception("empty primary key ({$this->_table->primary_key_exists($vars)}).");

    $result = $this->execute_delete_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 执行操作>>单条更新
  function execute_update_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $this->execute_select_item($vars);

    $sql_update_item = $this->_table->generate_update_item($vars);

    $_API_LOGGER->debug(__METHOD__, array('var'  => 'sql_update_item', 'value'  => $sql_update_item, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = $_CONNECTION->executeQuery($sql_update_item);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'execute::sql_update_item', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function update_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));

    // 检测 primary_keys
    if ($this->_table->primary_key_exists($vars) !== true) throw new Exception("empty primary key ({$this->_table->primary_key_exists($vars)}) value.");

    $result = $this->execute_update_item($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 执行操作>>计数查询
  function execute_select_count(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $_API_LOGGER->debug(__METHOD__, array('var' => 'vars', 'value'  => json_encode($vars), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $sql_select_count = $this->_table->generate_select_count($vars, $vars['columns']);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_count', 'value'  => $sql_select_count, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $rows = (array)$_CONNECTION->fetchAllAssociative($sql_select_count);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_count', 'value'  => json_encode($rows), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $total = count($rows);
    $count = array_reduce($rows, function ($t, $v) {
      return $t + (int)$v['_count'];
    }, 0);
    $rows = array_map(function ($item) use ($vars) {
      $row = $this->_table->get_row($item);
      $row['_count'] = (int)$item['_count'];
      $row = $this->get_row($row, $item, $vars);
      return $row;
    }, $rows);
    $result = [
      "rows" => $rows,
      "count" => $count,
      "total" => $total,
    ];
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }
  function select_count(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));

    if (empty($vars['columns'])) throw new Exception("empty count columns.");

    $result = $this->execute_select_count($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 执行操作>>单条查询
  /**
   * @param array $vars
   * @param array $columns = []
   */
  function execute_select_item(array $vars, array $columns = [])
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_select_item = $this->_table->generate_select_item($vars, $columns);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_item', 'value'  => $sql_select_item, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $row = $_CONNECTION->fetchAssociative($sql_select_item);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_item', 'value'  => json_encode($row), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    // 查询失败，没有对应数据
    if ($row === false) throw new Exception("no such row.");

    $row = $this->_table->get_row($row);
    $result = $this->get_row($row, $vars, $vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  /**
   * 
   */
  function select_item(array $vars, array $columns = [])
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    // 检测 primary_keys || columns
    if ($this->_table->columns_exist($vars, $columns) !== true) throw new Exception("empty primary key ({$this->_table->columns_exist($vars,$columns)}) value.");

    $result = $this->execute_select_item($vars, $columns);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }
  // 执行操作>>单条查询
  function execute_select_rand(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_select_rand = $this->_table->generate_select_rand($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_item', 'value'  => $sql_select_rand, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $row = $_CONNECTION->fetchAssociative($sql_select_rand);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_rand', 'value'  => json_encode($row), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    // 查询失败，没有对应数据
    if ($row === false) throw new Exception("no such row.");

    $row = $this->_table->get_row($row);
    $result = $this->get_row($row, $vars, $vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function select_rand(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    // 检测 primary_keys
    // if ($this->_table->primary_key_exists($vars) !== true) throw new Exception("empty primary key ({$this->_table->primary_key_exists($vars)}) value.");

    $result = $this->execute_select_rand($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 执行操作>>批量查询
  function execute_select_list(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $_API_LOGGER->debug(__METHOD__, array('var' => 'vars', 'value'  => json_encode($vars), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));


    $sql_select_count = $this->_table->generate_select_count($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_count', 'value'  => $sql_select_count, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $total = (int)$_CONNECTION->fetchOne($sql_select_count);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_count', 'value'  => $total, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $size = isset($vars['size']) ? (int)$vars['size'] : 20;
    $page = isset($vars['page']) ? (int)$vars['page'] : 1;
    // 重新赋值页码：超出默认最后一页。
    $page = ceil($total / $size) < $page ? ceil($total / $size) : $page;
    // 重新赋值页码：页码为正整数。
    $page = $page > 0 ? $page : 1;

    $sql_select_list = $this->_table->generate_select_list($vars, $page, $size);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_list', 'value'  => $sql_select_list, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $rows = $total === 0 ? [] :  $_CONNECTION->fetchAllAssociative($sql_select_list);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_list', 'value'  => json_encode($rows), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $rows = array_map(function ($item) use ($vars) {
      $row = $this->_table->get_row($item);
      $row = $this->get_row($row, $item, $vars);
      return $row;
    }, (array)$rows);
    $result = [
      "rows" => $rows,
      "total" => $total,
      "page" => $page,
      "size" => $size,
    ];
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }
  function select_list(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $vars = $this->before(__FUNCTION__, $vars);
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    // 删除 primary_keys，避免info查询
    $vars = $this->_table->unset_primary_keys($vars);

    $result = $this->execute_select_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  function execute_select_tree($vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $_API_LOGGER->debug(__METHOD__, array('var' => 'vars', 'value'  => json_encode($vars), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $sql_select_tree = $this->_table->generate_select_tree($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_tree', 'value'  => $sql_select_tree, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $rows = $_CONNECTION->fetchAllAssociative($sql_select_tree);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_tree', 'value'  => json_encode($rows), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $rows = array_map(function ($item) use ($vars) {
      $row = $this->_table->get_row($item);
      $row = $this->get_row($row, $item, $vars);
      return $row;
    }, (array)$rows);

    $tree = list_to_tree($rows, $this->_table->hierarchical_keys[0], $this->_table->hierarchical_keys[1]);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::list_to_tree', 'value'  => json_encode($tree), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return [
      "tree" => empty($rows) ? $rows : $tree[0]['children'][0],
      "rows" => $rows,
      "total" => sizeof($rows)
    ];
  }
  function select_tree(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    $this->set__table(json_decode(file_get_contents($this->_table_path), true));
    $vars = $this->before(__FUNCTION__, $vars);

    $result = $this->execute_select_tree($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 调用方法前
  function before($method, $vars)
  {
    return $vars;
  }
  // 生成语句后
  function generated($sql, $method, $vars)
  {
    return $sql;
  }
  // 执行操作后
  function executed($result, $sql, $method, $vars)
  {
    return $result;
  }
  function execute_sql($sqls)
  {
  }
  // 调用方法后
  function after($result, $sql, $method, $vars)
  {
    return $result;
  }
  /**
   * 处理查询后的列表对应数据
   */
  function get_row($row, $fields, $vars)
  {
    return $row;
  }
  protected function logger($var, $value)
  {
    global $_API_LOGGER, $_API_LOGGER_UUID;
    $_API_LOGGER->debug(__METHOD__, array('var'  => $var, 'value'  => $value, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
  }
  /**
   * 运行自动化任务
   */
  function automate_start()
  {
  }

  // TODO
  function upload_list($vars)
  {
    if (!is_dir(__DIR__ . '/../../../.tmp')) {
      mkdir(__DIR__ . '/../../../.tmp');
    }
    $file = $vars['_files'];
    foreach ($vars['_files'] as $file) {
      // $content = file_get_contents($file['tmp_name']);
      if (!move_uploaded_file($file['tmp_name'], __DIR__ . '/../../../.tmp/' . $file['name'])) {
        throw new Exception("Failed to move uploaded file");
      }
      $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
      $reader->setReadDataOnly(TRUE);
      $spreadsheet = $reader->load(__DIR__ . '/../../../.tmp/' . $file['name']); //载入excel表格
      $worksheet = $spreadsheet->getActiveSheet();
      $highestRow = $worksheet->getHighestRow(); // 总行数
      $highestColumn = $worksheet->getHighestColumn(); // 总列数

      $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5

      $lines = $highestRow - 2;
      if ($lines <= 0) {
        throw new Exception('Not have enough data.');
      }
      $sql = "INSERT INTO `t_student` (`name`, `chinese`, `maths`, `english`) VALUES ";

      for ($row = 3; $row <= $highestRow; ++$row) {
        $name = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //姓名
        $chinese = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //语文
        $maths = $worksheet->getCellByColumnAndRow(3, $row)->getValue(); //数学
        $english = $worksheet->getCellByColumnAndRow(4, $row)->getValue(); //外语

        $sql .= "('$name','$chinese','$maths','$english'),";
      }
      $sql = rtrim($sql, ","); //去掉最后一个,号
      var_dump($sql);
    }
    throw new Exception("method not ready.");
  }
}
