<?php
global $_SWAGGER;
$module = "api";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);

use Langnang\Module\Api\Api;
use Langnang\Module\Content\Content;

require_once __DIR__ . '/controllers.php';
/**
 * @OA\Info(
 *   title="Public APIs",
 *   description="Public API",
 *   version="0.0.1",
 * )
 */
$router->addGroup("/public", function (FastRoute\RouteCollector $router) {
  $controller = new Api();
  /**
   * @OA\Get(
   *     path="/api/public/random",
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('GET', '/random', function ($vars) use ($controller) {
    global $_FAKER;
    $list = $controller->select_count([
      'type' => "api",
      'size' => PHP_INT_MAX,
      'columns' => ['cid', 'title', 'text']
    ]);
    $row = $_FAKER->randomElement($list['rows']);

    $parent = $row['cid'];

    $method = $row['text']['method'];
    $url = $row['text']['url'];
    $headers = [];
    $data = [];
    $response = Requests::{$method}($url, $headers, $data);

    $controller->insert_item([
      "type" => "api",
      "text" => json_encode($response, JSON_UNESCAPED_UNICODE),
      "parent" => $parent
    ]);



    $body = json_decode($response->body, true);
    if (!is_null($body)) $response->body = $body;

    return $response;
  });
  /**
   * @OA\Post(
   *     path="/api/public/insert",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),     
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/insert', [$controller, 'insert_item']);
  /**
   * @OA\Post(
   *     path="/api/public/delete",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/delete', [$controller, 'delete_list']);
  /**
   * @OA\Post(
   *     path="/api/public/update",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/update', [$controller, 'update_item']);
  /**
   * @OA\Post(
   *     path="/api/public/count",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/count', [$controller, 'select_count']);
  /**
   * @OA\Post(
   *     path="/api/public/list",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/list', [$controller, 'select_list']);
  /**
   * @OA\Post(
   *     path="/api/public/tree",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/tree', [$controller, 'select_tree']);
  /**
   * @OA\Post(
   *     path="/api/public/info",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/ApiModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/info', [$controller, 'select_item']);
});
