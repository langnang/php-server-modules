<?php

namespace Langnang\Module\Root;

use Exception;
use Langnang\SqlGenerator\MySqlGenerator\MySqlTable;

require_once __DIR__ . '/interfaces.php';
require_once __DIR__ . '/models.php';

class RootController extends RootModel
{
  protected $_table;
  function set__table(array $table)
  {
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
    // 删除 primary_keys
    $vars = $this->_table->unset_primary_keys($vars);

    $result = $this->execute_insert_item($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  // 执行操作>>批量新增
  function execute_insert_list(array $vars): mixed
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_insert_list = $this->_table->generate_insert_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_insert_list', 'value'  => $sql_insert_list, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $result = $_CONNECTION->executeQuery($sql_insert_list);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'execute::sql_insert_list', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function insert_list(array $vars): mixed
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
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

    if ($this->_table->primary_key_exists($vars) !== true) throw new Exception("empty primary key ({$this->_table->primary_key_exists($vars)}).");

    $result = $this->execute_delete_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 执行操作>>单条更新
  function execute_update_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

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

    if (empty($vars['columns'])) throw new Exception("empty count columns.");

    $result = $this->execute_select_count($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  // 执行操作>>单条查询
  function execute_select_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $sql_select_item = $this->_table->generate_select_item($vars);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'sql_select_item', 'value'  => $sql_select_item, "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    $row = $_CONNECTION->fetchAssociative($sql_select_item);
    $_API_LOGGER->debug(__METHOD__, array('var' => 'execute::sql_select_item', 'value'  => json_encode($row), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    // 查询失败，没有对应数据
    if ($row === false) throw new Exception("no such row.");

    $result = $this->get_row($row, $vars, $vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function select_item(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
    // 检测 primary_keys
    if ($this->_table->primary_key_exists($vars) !== true) throw new Exception("empty primary key ({$this->_table->primary_key_exists($vars)}) value.");

    $result = $this->execute_select_item($vars);
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

    $result = $this->get_row($row, $vars, $vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));

    return $result;
  }
  function select_rand(array $vars)
  {
    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;
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
    // 删除 primary_keys，避免info查询
    $vars = $this->_table->unset_primary_keys($vars);

    $result = $this->execute_select_list($vars);
    $_API_LOGGER->debug(__METHOD__, array('var'  => 'result', 'value'  => json_encode($result), "uuid" => $_API_LOGGER_UUID, "timestamp" => timestamp()));
    return $result;
  }

  /**
   * 对查询后的数据处理
   */
  function get_row($row, $fields = [], $vars = [])
  {
    return $row;
  }
}
