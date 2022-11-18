<?php

use Langnang\Module\PublicApi\PublicApi;
use Langnang\Module\Content\Content;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Quote\Quote;


require_once __DIR__ . '/../main.test.php';

require_once __DIR__ . '/main.auto.php';

$auto = new AutoQuoteItem();

var_dump($auto);


var_dump($auto->start());
