<?php

use Langnang\SqlGenerator\MySqlGenerator\MySqlSchema;

require_once __DIR__ . '/../main.test.php';

global $_CONFIG, $_CONNECTION;

$schema = new MySqlSchema([
  "name" => $_CONFIG['db']['dbname'],
]);

$sql_select_tables = $schema->generate_select_tables();

$tables = (array)$_CONNECTION->fetchAllAssociative($sql_select_tables);

$schema->set_tables($tables);

header('Content-Type: application/json');
/** 打印响应报文 */
echo json_encode(array_filter((array)$schema), JSON_UNESCAPED_UNICODE);
