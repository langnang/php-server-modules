<?php

namespace Langnang\Module\Meta;

use Langnang\Module\Root\RootController;

require_once __DIR__ . '/../.mysql/mysql.php';
require_once __DIR__ . '/models.php';

class Meta extends RootController
{
  function __construct($args = [])
  {
    $table = file_get_contents(__DIR__ . '/table.json');
    $this->set__table(json_decode($table, true));
  }
}
