<?php

namespace Langnang\Module\User;

use Exception;
use Langnang\Module\MySQL\MySqlTable;
use Langnang\Module\Root\RootController;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/models.php';

class User extends RootController
{
  protected $_class = __CLASS__;
  protected $_table_path = __DIR__ . '/table.json';
  /**
   * 
   */
  function execute_login($vars)
  {
  }
  /**
   * 用户登录
   * 根据用户名+密码查询对应用户信息
   * 更新对应 authCode
   * 返回 authCode
   */
  function login($vars)
  {

    global $_CONNECTION, $_API_LOGGER, $_API_LOGGER_UUID;

    $vars['password'] = md5($vars['password']);

    $user = $this->select_item($vars, ['name', 'password']);

    $authCode = md5(json_encode(array_merge($_SERVER, $user)));

    $user['authCode'] = $authCode;
    $user['activated'] = date('Y-m-d H:i:s', time());
    $this->execute_update_item(['uid' => $user['uid'], 'activated' => $user['activated'], 'authCode' => $user['authCode']]);
    return $user;
  }
  function logout($vars)
  {
    return $this->update_item(['uid' => $vars['_user'],  'authCode' => ""]);
    return [];
  }
  static function verify()
  {
    $user = new User();
    if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
      throw new Exception("Empty Authorization.");
    }
    $authorization = explode(" ", $_SERVER['HTTP_AUTHORIZATION']);
    if (sizeof($authorization) !== 2 || $authorization[0] !== 'Bearer') {
      throw new Exception("Error Format Authorization.");
    }
    try {
      $user = $user->select_item(["authCode" => $authorization[1]], ['authCode']);
    } catch (Exception $e) {
      throw new Exception("Error Authorization.");
    }
    return $user['uid'];
  }
  function get_row($row, $fields = [], $vars = [])
  {
    unset($row['password']);
    unset($row['authCode']);
    // $row['created'] = date('Y-m-d H:i:s', $row['created']);
    return $row;
  }
}
