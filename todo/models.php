<?php

namespace Langnang\Module\Todo;

class TodoModel
{
  public $id;
  public $title;
  public $description;
  public $done;
  public $parent;

  function __construct($args)
  {
    $this->set_id($args['id']);
    $this->title = $args['title'];
    $this->description = $args['description'];
    $this->set_done($args['done']);
  }
  function set_id($value)
  {
    // 赋值时判断是否为空
    if (!is_null($value)) $this->id = (int)$value;
  }
  function get_id()
  {
    return $this->id;
  }
  function set_done($value)
  {
    $this->done = (bool)$value;
  }
  function get_done()
  {
    return $this->done;
  }
}
