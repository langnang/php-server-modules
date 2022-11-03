<?php

use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;

header("refresh: 2");

require_once __DIR__ . '/../main.test.php';

$controller = new Mock();

global $_FAKER;
$meta_controller = new Meta();
$row = $meta_controller->select_rand([
  'type' => "option",
  'slug' => 'mock.type',
]);
$method = $row['name'];
$value = $_FAKER->{$method}();
$controller->insert_item([
  "type" => "{$method}",
  "text" => $value,
]);
var_dump(["method" => $method, "value" => $value]);
