<?php

namespace Langnang\Module\PublicApi;

use Exception;
use Langnang\Module\Content\Content;
use Langnang\Module\MySQL\MySqlTable;
use Langnang\Module\Root\RootController;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/models.php';

class PublicApi extends Content
{
  // protected $_class = __CLASS__;
  // protected $_table_path = __DIR__ . '/table.json';

  function before($method, $vars)
  {
    $vars['type'] = 'public_api';
    
    return $vars;
  }
  function get_row($row, $fields = [], $vars = [])
  {
    // unset($row['type']);
    // return $row;
    $text = json_decode($row['text'], true);
    if (empty($text)) return $row;
    return array_merge($text, $row);
  }
}
