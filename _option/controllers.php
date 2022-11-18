<?php

namespace Langnang\Module\Option;

use Langnang\Module\Root\RootController;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/models.php';

class Option extends RootController
{
  protected $_class = __CLASS__;
  protected $_table_path = __DIR__ . '/table.json';

  function get_row($row, $fields, $vars)
  {
    $value = json_decode($row['value'], true);
    if (!empty($value)) $row['value'] = $value;
    return $row;
  }
}
