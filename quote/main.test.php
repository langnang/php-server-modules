<?php

use Langnang\Module\PublicApi\PublicApi;
use Langnang\Module\Content\Content;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Quote\Quote;
use Langnang\Module\Automate\Automate;




require_once __DIR__ . '/../main.test.php';

$_AUTOMATE = new Automate();


require_once __DIR__ . '/main.auto.php';



$auto = new AutoQuoteItem();

$result = $auto->start();


header('Content-Type: application/json');
/** 打印响应报文 */
echo json_encode(array_filter((array)$result), JSON_UNESCAPED_UNICODE);
