<?php

namespace Langnang\Module\Relationship;

use Langnang\Module\Root\RootController;

require_once __DIR__ . '/models.php';

class Relationship extends RootController
{
  protected $_class = __CLASS__;
  protected $_table_path = __DIR__ . '/table.json';
}
