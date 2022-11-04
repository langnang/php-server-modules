<?php

use Langnang\Module\Meta\Meta;
use Langnang\Module\Mock\Mock;

/** 异常终止程序后，1秒后刷新页面 */
header("refresh: 1");

require_once __DIR__ . '/../main.test.php';
// 用户Ngix、Apache显示动态进度
header('X-Accel-Buffering: no');
//防止执行超时
set_time_limit(0);
//清空并关闭输出缓存
ob_end_clean();

$controller = new Mock();

$meta_controller = new Meta();
$rows = $meta_controller->select_count([
  'type' => "option",
  'slug' => 'mock.type',
  'siz' => PHP_INT_MAX,
  'columns' => ['name']
])['rows'];

$rows = array_map(function ($item) {
  return $item['name'];
}, $rows);

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
    $method = $_FAKER->randomElement($rows);
    $value = $_FAKER->{$method}();
  ?>
    <script language="JavaScript">
      updateProgress('<? echo json_encode(["method" => $method, "value" => $value], JSON_UNESCAPED_UNICODE); ?>');
    </script>
  <?php
    $controller->insert_item([
      "type" => "{$method}",
      "text" => $value,
    ]);
    flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。 

    ob_flush();

    // sleep(1);// 秒级延迟 1s

    usleep(0.1 * 1000 * 1000); // 微秒级延迟 0.1s
  }
  ?>
  <?php
  flush();
  ob_end_flush();
  ?>
</body>

</html>