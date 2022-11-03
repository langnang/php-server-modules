<?php

namespace Langnang\Module\Api;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . '/../.root/models.php';

/**
 * @OA\Schema(schema="ApiModel")
 */
class ApiModel extends RootModel
{
  /**
   * @OA\Property()
   * @var int
   */
  public $cid;
  /**
   * @OA\Property()
   * @var string
   */
  public $url;
  /**
   * @OA\Property()
   * @var string
   */
  public $method;
  /**
   * @OA\Property()
   * @var string
   */
  public $description;
}
