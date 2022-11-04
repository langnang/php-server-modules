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
  <title>Quote - 常用语录</title>
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

    $result = [];
    $response = WpOrg\Requests\Requests::{$row['method']}($row['url'], (array)$row['headers'], (array)$row['data']);
    $body = json_decode($response->body, true);
    if (!is_null($body)) $response->body = $body;

    switch ($row['response']['type']) {
      case 'object':
        $quote = [];
        $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
        foreach ((array)$row['response']['corr'] as $key => $value) {
          $quote[$key] = $body[$value];
        }
        $controller->insert_item($quote);
        $result = $quote;
        break;
      case 'array':
        $quotes = array_map(function ($item) use ($row) {
          $quote = [];
          $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
          foreach ((array)$row['response']['corr'] as $key => $value) {
            $quote[$key] = $item[$value];
          }
          return $quote;
        }, $body);
        $controller->insert_list([$quotes]);
        $result = $quotes;
        break;
      case 'text':
      default:
        $quote = [];
        $quote['type'] = implode("_", array_slice($row['slug'], 1, sizeof($row['slug']) - 1));
        foreach ((array)$row['response']['corr'] as $key => $value) {
          $quote[$key] = $response->{$value};
        }
        $controller->insert_item($quote);
        $result = $quote;
        break;
    }
    // $value = json_encode($_FAKER->{$method}(), JSON_UNESCAPED_UNICODE);
  ?>
    <script language="JavaScript">
      updateProgress('<? echo addslashes(json_encode($result, JSON_UNESCAPED_UNICODE)); ?>');
    </script>
  <?php

    flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。 

    ob_flush();

    // sleep(1);// 秒级延迟 1s

    usleep(1 * 1000 * 1000); // 微秒级延迟 0.1s
  }
  ?>
  <?php
  flush();
  ob_end_flush();
  ?>
</body>

</html>