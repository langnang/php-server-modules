<?php

use Langnang\Module\Automate\AutometeItem;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Content\Content;
use Langnang\Module\Option\Option;

global $_AUTOMATE;

class AutoMockItem extends AutometeItem
{

  public $name = "Mock";
  function __construct()
  {
    $option_controller = new Option();

    $options = $option_controller->select_item([
      "name" => "mockTypes",
      "user" => 0,
    ]);
    $this->options = $options['value'];
  }
  function start()
  {
    global $_FAKER;


    $method = $_FAKER->randomElement($this->options);
    $value = $_FAKER->{$method}();
    if (is_array($value)) $value = json_encode($_FAKER->{$method}(), JSON_UNESCAPED_UNICODE);

    $row = [
      "title" => "mock-{$method}",
      "text" => $value,
      "type" => "mock",
      "status" => "{$method}",
      "created" => time(),
      "modified" => time(),
    ];

    $content_controller = new Content();
    $content = $content_controller->insert_item($row);
    return array_merge($row, $content);
  }
}

$_AUTOMATE->insert(new AutoMockItem());
