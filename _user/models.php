<?php

namespace Langnang\Module\User;

use Langnang\Module\Root\RootModel;

require_once __DIR__ . '/../.root/models.php';

/**
 * @OA\Schema(schema="UserModel")
 */
class UserModel extends RootModel
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
  public $channel;
  /**
   * @OA\Property()
   * @var int
   */
  public $level;
  /**
   * @OA\Property()
   * @var string
   */
  public $message;
  /**
   * @OA\Property()
   * @var int
   */
  public $time;
  public $username;
  public $userid;
}
