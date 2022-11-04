<?php

use Langnang\Module\Automate\AutometeItem;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;

global $_AUTOMATE;

class AutoMockItem extends AutometeItem
{

  public $name = "Mock";
  function __construct()
  {
    $meta_controller = new Meta();
    $rows = $meta_controller->select_count([
      'type' => "option",
      'slug' => 'mock.type',
      'siz' => PHP_INT_MAX,
      'columns' => ['name']
    ])['rows'];

    $this->options = array_map(function ($item) {
      return $item['name'];
    }, $rows);
  }
  function start()
  {
    global $_FAKER;
    $mock_controller = new Mock();
    $method = $_FAKER->randomElement($this->options);
    $value = json_encode($_FAKER->{$method}(), JSON_UNESCAPED_UNICODE);
    $row = [
      "type" => "{$method}",
      "text" => $value,
    ];
    $mock_controller->insert_item($row);
    return $row;
  }
}

$_AUTOMATE->insert(new AutoMockItem());
