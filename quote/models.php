<?php

namespace Langnang\Module\Quote;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . '/../.root/models.php';

/**
 * @OA\Schema(schema="QuoteModel")
 */
class QuoteModel extends RootModel
{
  /**
   * @OA\Property()
   * @var int
   */
  public $id;
  /**
   * @OA\Property()
   * @var string
   */
  public $text;
  /**
   * @OA\Property()
   * @var string
   */
  public $type;
  /**
   * @OA\Property()
   * @var string
   */
  public $author;
  /**
   * @OA\Property()
   * @var int
   */
  public $created;
}
