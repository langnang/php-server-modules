<?php

use Langnang\SqlGenerator\MySqlGenerator\MySql;
use Langnang\SqlGenerator\MySqlGenerator\MySqlSchema;

require_once __DIR__ . '/../main.test.php';

global $_CONFIG, $_CONNECTION;


$mysql = new MySql();

$sql_select_schemas = $mysql->generate_select_schemas();

$schemas = (array)$_CONNECTION->fetchAllAssociative($sql_select_schemas);

$mysql->set_schemas($schemas);

$sql_select_tables = $mysql->generate_select_tables([$_CONFIG['db']['dbname']]);

$tables = (array)$_CONNECTION->fetchAllAssociative($sql_select_tables);

// var_dump($tables);
// table 分组
foreach ($tables as $table) {
  if (!isset($mysql->schemas[$table['TABLE_SCHEMA']])) continue;
  array_push($mysql->schemas[$table['TABLE_SCHEMA']]->tables, $table);
}

foreach ($mysql->schemas as $name => $schema) {
  $mysql->schemas[$name]->set_tables($schema->tables);
}

$sql_select_columns = $mysql->generate_select_columns([$_CONFIG['db']['dbname']]);

$columns = (array)$_CONNECTION->fetchAllAssociative($sql_select_columns);

// column 分组
foreach ($columns as $column) {
  if (!isset($mysql->schemas[$column['TABLE_SCHEMA']]) || !isset($mysql->schemas[$column['TABLE_SCHEMA']]->tables[$column['TABLE_NAME']])) continue;
  array_push($mysql->schemas[$column['TABLE_SCHEMA']]->tables[$column['TABLE_NAME']]->columns, $column);
}
// var_dump($mysql->schemas);
// print_r($mysql);

foreach ($mysql->schemas as $schema_name => $schema) {
  foreach ($schema->tables as $table_name => $table) {
    $mysql->schemas[$schema_name]->tables[$table_name]->set_columns($table->columns);
  }
}

header('Content-Type: application/json');
echo json_encode(array_filter((array)$mysql), JSON_UNESCAPED_UNICODE);

exit;

$schema = new MySqlSchema([
  "name" => $_CONFIG['db']['dbname'],
]);

$sql_select_tables = $schema->generate_select_tables();

$tables = (array)$_CONNECTION->fetchAllAssociative($sql_select_tables);

$schema->set_tables($tables);

header('Content-Type: application/json');
/** 打印响应报文 */
echo json_encode(array_filter((array)$schema), JSON_UNESCAPED_UNICODE);
exit;

// TODO 生成表结构 JSON