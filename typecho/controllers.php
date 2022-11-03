<?php

namespace Langnang\Module\Typecho;

use Exception;

require_once __DIR__ . '/models.php';
require_once __DIR__ . '/interfaces.php';
class TypechoController implements TypechoInterface
{
  /**
   * 实体类名
   */
  protected static $model_class;
  function execute_create_table()
  {
  }
  function execute_drop_table()
  {
  }
  // 单项新增
  function __execute_insert_item($vars, $model_class = null)
  {
    $model_class = empty($model_class) ? $this::$model_class : $model_class;
    global $_CONNECTION;
    $model = new $model_class();
    unset($vars[$model->get_primary_key()]);
    $model->__construct($vars, true);
    $sql = $model->get_sql_insert();
    $_CONNECTION->executeQuery($sql);
    return call_user_func([$this, 'execute_select_item'], [$model->get_primary_key() => $_CONNECTION->lastInsertId()], $model_class);
  }
  function execute_insert_item($vars, $model_class = null)
  {
    return $this->__execute_insert_item($vars, $model_class);
  }
  function __execute_import_item($vars, $model_class = null)
  {
    throw new Exception("method not ready.");
  }
  function execute_import_list($vars, $model_class = null)
  {
    return $this->__execute_import_item($vars, $model_class);
  }

  // 多项删除
  function __execute_delete_list($vars, $model_class = null)
  {
    $model_class = empty($model_class) ? $this::$model_class : $model_class;
    global $_CONNECTION;
    $model = new $model_class($vars);
    if (in_array(0, $model->get_delete_primary_key_values())) throw new Exception("cannot delete root(0).");
    $sql = $model->get_sql_delete();

    return $_CONNECTION->executeQuery($sql);
  }
  function execute_delete_list($vars, $model_class = null)
  {
    return $this->__execute_delete_list($vars, $model_class);
  }
  // 单项修改
  function __execute_update_item($vars, $model_class = null)
  {
    $model_class = empty($model_class) ? $this::$model_class : $model_class;
    global $_CONNECTION;
    $model = new $model_class();
    // 检测 primary_key
    if (is_null($vars[$model->get_primary_key()])) throw new Exception("empty {$model->get_primary_key()} value.");
    $model->__construct($vars, true);
    $sql = $model->get_sql_update();
    $_CONNECTION->executeQuery($sql);
    return call_user_func([$this, 'execute_select_item'], [$model->get_primary_key() => $model->{$model->get_primary_key()}], $model_class);
  }
  function execute_update_item($vars, $model_class = null)
  {
    return $this->__execute_update_item($vars, $model_class);
  }

  function __execute_select_count($vars, $model_class = null)
  {
    global $_CONNECTION;
    if (empty($vars['columns'])) throw new Exception("empty count columns.");
    $model_class = empty($model_class) ? $this::$model_class : $model_class;
    $model = new $model_class($vars);
    $sql = $model->get_sql_count($vars['columns']);
    $rows = (array)$_CONNECTION->fetchAllAssociative($sql);
    $total = count($rows);
    $count = array_reduce($rows, function ($t, $v) {
      return $t + (int)$v['count_total'];
    }, 0);
    $rows = array_map(function ($item) use ($model_class) {
      $model = new $model_class($item);
      $model->count_total = (int)$item['count_total'];
      return $model;
    }, $rows);
    return [
      "rows" => $rows,
      "count" => $count,
      "total" => $total,
    ];
  }
  function execute_select_count($vars, $model_class = null)
  {
    return $this->__execute_select_count($vars, $model_class);
  }
  // 多项列表查询
  function __execute_select_list($vars, $model_class = null)
  {
    global $_CONNECTION;
    // model_class
    $model_class = empty($model_class) ? $this::$model_class : $model_class;
    $model = new $model_class();
    // 删除 primary_key，避免info查询
    unset($vars[$model->get_primary_key()]);
    $model->__construct($vars);
    $total = (int)$_CONNECTION->fetchOne($model->get_sql_count());
    $size = isset($vars['size']) ? (int)$vars['size'] : 20;
    $page = isset($vars['page']) ? (int)$vars['page'] : 1;
    // 重新赋值页码：超出默认最后一页。
    $page = ceil($total / $size) < $page ? ceil($total / $size) : $page;
    // 重新赋值页码：页码为正整数。
    $page = $page > 0 ? $page : 1;
    $sql = $model->get_sql_select($page, $size);
    return [
      "rows" => array_map(function ($item) use ($model_class) {
        return new $model_class($item);
      }, $total === 0 ? [] : (array) $_CONNECTION->fetchAllAssociative($sql)),
      "total" => $total,
      "page" => $page,
      "size" => $size,
    ];
  }
  function execute_select_list($vars, $model_class = null)
  {
    return $this->__execute_select_list($vars, $model_class);
  }
  function execute_select_tree($vars, $model_class = null)
  {
    $vars['page'] = 1;
    $vars['size'] = PHP_INT_MAX;
    $result = $this->__execute_select_list($vars, $model_class);
    $result['tree'] = list_to_tree($result['rows'], 'mid', 'parent', 0);
    return $result;
  }
  // 单项详情查询
  function __execute_select_item($vars, $model_class = null)
  {
    $model_class = empty($model_class) ? $this::$model_class : $model_class;
    global $_CONNECTION;
    $model = new $model_class();
    // 检测 primary_key
    if (is_null($vars[$model->get_primary_key()])) throw new Exception("empty {$model->get_primary_key()} value.");
    $model->__construct($vars);
    $row = $_CONNECTION->fetchAssociative($model->get_sql_select());
    if ($row === false) {
      throw new Exception("no such row.");
    } else {
      return new $model_class($_CONNECTION->fetchAssociative($model->get_sql_select()));
    }
  }
  function execute_select_item($vars, $model_class = null)
  {
    return $this->__execute_select_item($vars, $model_class);
  }
  function __execute_export_list($vars, $model_class = null)
  {
    throw new Exception("method not ready.");
  }
  function execute_export_list($vars, $model_class = null)
  {
    return $this->__execute_export_list($vars, $model_class);
  }
}
class TypechoMetaController extends TypechoController
{
  static $model_class = "Langnang\Module\Typecho\TypechoMetaModel";
  function execute_select_item($vars, $model_class = null)
  {
    $result = $this->__execute_select_item($vars, $model_class);
    if (empty($result->cids)) {
      $result->contents = $result->cids;
    } else {
      $result->contents = $this->execute_select_list(["cids" => $result->cids, "size" => PHP_INT_MAX, "page" => 1], TypechoContentController::$model_class)["rows"];
    }
    return $result;
  }
}

class TypechoContentController extends TypechoController
{
  static $model_class = "Langnang\Module\Typecho\TypechoContentModel";
  function execute_select_item($vars, $model_class = null)
  {
    $result = $this->__execute_select_item($vars, $model_class);
    if (empty($result->mids)) {
      $result->metas = $result->mids;
    } else {
      $result->metas = $this->execute_select_list(["mids" => $result->mids, "size" => PHP_INT_MAX, "page" => 1], TypechoMetaController::$model_class)["rows"];
    }
    return $result;
  }
}
