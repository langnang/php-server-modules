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
   * @OA\Property(default="faker")
   * @var string
   */
  public $title;
  /**
   * @OA\Property()
   * @var string
   */
  public $text;
  /**
   * @OA\Property(default="mock")
   * @var string
   */
  public $type;
}
