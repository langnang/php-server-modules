<?php
// mock
global $_SWAGGER;
$module = "mock";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);

use Langnang\Module\Mock\Mock;
use Langnang\Module\Meta\Meta;

require_once __DIR__ . '/controllers.php';
/**
 * @OA\Info(
 *   title="Mock",
 *   description="数据模拟",
 *   version="0.0.1",
 * )
 */
$router->addGroup("/mock", function (FastRoute\RouteCollector $router) {
  $controller = new Mock();

  /**
   * @OA\Get(
   *     path="/api/mock/faker/{method}",
   *     @OA\Parameter(
   *         name="method",
   *         in="path",
   *         required=true,
   *         @OA\Schema(type="string",default="uuid")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('GET', '/faker/{method}', function ($vars) use ($controller) {
    global $_FAKER;
    $method = $vars['method'];
    $value = $_FAKER->{$vars['method']}();
    $controller->insert_item([
      "type" => "{$method}",
      "text" => $value,
    ]);
    return ["method" => $method, "value" => $value];
  });
  /**
   * @OA\Post(
   *     path="/api/mock/insert",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),     
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/insert', [$controller, 'insert_item']);
  /**
   * @OA\Post(
   *     path="/api/mock/delete",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/delete', [$controller, 'delete_list']);
  /**
   * @OA\Post(
   *     path="/api/mock/update",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/update', [$controller, 'update_item']);
  /**
   * @OA\Post(
   *     path="/api/mock/count",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/count', [$controller, 'select_count']);
  /**
   * @OA\Post(
   *     path="/api/mock/list",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/list', [$controller, 'select_list']);
  /**
   * @OA\Post(
   *     path="/api/mock/tree",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/tree', [$controller, 'select_tree']);
  /**
   * @OA\Post(
   *     path="/api/mock/info",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/MockModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/info', [$controller, 'select_item']);
  /**
   * @OA\Get(
   *     path="/api/mock/rand",
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('GET', '/rand', function ($vars) use ($controller) {
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
    return ["method" => $method, "value" => $value];
  });
});
