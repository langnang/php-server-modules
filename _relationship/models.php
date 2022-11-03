<?php

namespace Langnang\Module\Relationship;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . '/../.root/models.php';

/**
 * @OA\Schema(schema="RelationshipModel")
 */
class RelationshipModel extends RootModel
{
  /**
   * @OA\Property()
   * @var int
   */
  public $cid;
  /**
   * @OA\Property()
   * @var int
   */
  public $mid;
}
