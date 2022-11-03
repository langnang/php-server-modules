<?php

namespace Langnang\Module\Mock;

/**
 * @OA\Schema(schema="MockModel")
 */
class MockModel
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
  public $text;
  /**
   * @OA\Property(default=NULL)
   * @var string
   */
  public $type;
}
