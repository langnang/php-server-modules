<?php

namespace Langnang\Module\Relationship;

use Langnang\Module\Root\RootController;

require_once __DIR__ . '/models.php';

class Relationship extends RootController
{
  function __construct($args = [])
  {
    $table = file_get_contents(__DIR__ . '/table.json');
    $this->set__table(json_decode($table, true));
  }
}
