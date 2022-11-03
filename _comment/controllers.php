<?php

namespace Langnang\Module\Comment;

use Exception;
use Langnang\Module\MySQL\MySqlTable;
use Langnang\Module\Root\RootController;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/models.php';

class Comment extends RootController
{
  static $model_class = 'Langnang\Module\Comment\CommentModel';
  function __construct($args = [])
  {
    $table = file_get_contents(__DIR__ . '/table.json');
    $this->set__table(json_decode($table, true));
  }
}
