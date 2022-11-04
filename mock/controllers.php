<?php

namespace Langnang\Module\Mock;

use Langnang\Module\Root\RootController;

require_once __DIR__ . '/../.root/controllers.php';
require_once __DIR__ . '/models.php';
require_once __DIR__ . '/interfaces.php';

class Mock extends RootController
{
  protected $class = __CLASS__;
  protected $_table_path = __DIR__ . '/table.json';
}
