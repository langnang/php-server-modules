<?php

use Langnang\Module\Content\Content;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Option\Option;

require_once __DIR__ . '/../main.test.php';

global $_FAKER;

$option_controller = new Option();

$options = $option_controller->select_item([
  "name" => "mockTypes",
  "user" => 0,
]);
$options = $options['value'];


$method = $_FAKER->randomElement($options);
$value = $_FAKER->{$method}();
if (is_array($value)) $value = json_encode($_FAKER->{$method}(), JSON_UNESCAPED_UNICODE);
// $value = addslashes(json_encode($_FAKER->{$method}(), JSON_UNESCAPED_UNICODE));
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

var_dump(array_merge($row, $content));

// $mock_controller->insert_item($row);
