<?php

namespace Langnang\Module\Mock;

use Langnang\Module\Root\RootController;

require_once __DIR__ . '/models.php';
require_once __DIR__ . '/interfaces.php';

class Mock extends RootController
{
  function __construct(array $args = [])
  {
    $table = file_get_contents(__DIR__ . '/table.json');
    $this->set__table(json_decode($table, true));
  }
}
