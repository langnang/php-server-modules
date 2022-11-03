<?php

namespace Langnang\Module\Content;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . '/../.root/models.php';

/**
 * @OA\Schema(schema="ContentModel")
 */
class ContentModel extends RootModel
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
  public $name;
  /**
   * @OA\Property()
   * @var string
   */
  public $slug;
  /**
   * @OA\Property()
   * @var string
   */
  public $type;
  /**
   * @OA\Property()
   * @var string
   */
  public $description;
  /**
   * @OA\Property()
   * @var int
   */
  public $order;
  /**
   * @OA\Property()
   * @var int
   */
  public $parent;
}
