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
  public $uid;
  /**
   * @OA\Property()
   * @var string
   */
  public $name;
  /**
   * @OA\Property()
   * @var string
   */
  public $password;
  /**
   * @OA\Property()
   * @var string
   */
  public $mail;
  /**
   * @OA\Property()
   * @var int
   */
  public $url;
  /**
   * @OA\Property()
   * @var string
   */
  public $screenName;
  /**
   * @OA\Property()
   * @var int
   */
  public $created;
  /**
   * @OA\Property()
   * @var int
   */
  public $activated;
  /**
   * @OA\Property()
   * @var int
   */
  public $logged;
  /**
   * @OA\Property()
   * @var string
   */
  public $group;
  /**
   * @OA\Property()
   * @var string
   */
  public $authCode;
}
