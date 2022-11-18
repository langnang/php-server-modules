<?php

namespace Langnang\Module\Option;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . '/../.root/models.php';

/**
 * @OA\Schema(schema="OptionModel")
 */
class OptionModel extends RootModel
{
  /**
   * @OA\Property()
   * @var string
   */
  public $name;
  /**
   * @OA\Property()
   * @var int
   */
  public $user;
  /**
   * @OA\Property()
   * @var string
   */
  public $value;
}
