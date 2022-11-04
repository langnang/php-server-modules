<?php

namespace Langnang\Module\Automate;

class Automate
{
  public $list = [];
  public $active;
  // 新增任务
  function insert(AutometeItem $item)
  {
    array_push($this->list, $item);
  }
  // 启动
  function start()
  {
    $index = mt_rand(0, sizeof($this->list) - 1);

    $this->active = $this->list[$index];

    $result = $this->active->start();

    return $result;
  }
}


class AutometeItem
{
  public $name;
}
