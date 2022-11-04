<?php

use Langnang\Module\Api\Api;
use Langnang\Module\Content\Content;
use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;
use Langnang\Module\Quote\Quote;

/** 异常终止程序后，1秒后刷新页面 */

require_once __DIR__ . '/../main.test.php';
// 用户Ngix、Apache显示动态进度
header('X-Accel-Buffering: no');
//防止执行超时
set_time_limit(0);
//清空并关闭输出缓存
ob_end_clean();

$controller = new Quote();

$meta_controller = new Api();
$rows = $meta_controller->select_list([
  'slug' => 'quote',
  'size' => PHP_INT_MAX,
])['rows'];

if (sizeof($rows) === 0) {
  print('Missing Quote APIs');
  exit();
}

header("refresh: 1");

?>
<?php

?>

<html>

<head>
  <title>Mock - 数据模拟</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <style>
    body,
    div input {
      font-family: Consolas;
      font-size: 9pt
    }
  </style>

</head>

<body>
  <ol id="app"> </ol>
  <script language="JavaScript">
    const app = document.getElementById('app');

    function updateProgress(sMsg, iWidth) {
      const item = document.createElement("li");
      item.innerHTML = sMsg;
      if (app.children[0]) {
        app.insertBefore(item, app.children[0]);
      } else {
        app.appendChild(item);
      }
    }
  </script>
  <?php

  flush(); //将输出发送给客户端浏览器 

  while (true) {
    $row = $_FAKER->randomElement($rows);
    $quote = [];
    $quote['type'] = $row['slug'][1];

    $response = WpOrg\Requests\Requests::{$row['method']}($row['url'], (array)$row['headers'], (array)$row['data']);
    $body = json_decode($response->body, true);
    if (!is_null($body)) $response->body = $body;

    foreach ((array)$row['response'] as $key => $value) {
      $quote[$key] = $response->{$value};
    }
    // $value = json_encode($_FAKER->{$method}(), JSON_UNESCAPED_UNICODE);
  ?>
    <script language="JavaScript">
      updateProgress('<? echo json_encode($quote, JSON_UNESCAPED_UNICODE); ?>');
    </script>
  <?php
    $controller->insert_item($quote);

    flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。 

    ob_flush();

    // sleep(1);// 秒级延迟 1s

    usleep(0.5 * 1000 * 1000); // 微秒级延迟 0.1s
  }
  ?>
  <?php
  flush();
  ob_end_flush();
  ?>
</body>

</html>