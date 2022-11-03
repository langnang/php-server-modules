<?php
global $_SWAGGER;
$module = "user";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);

use Langnang\Module\User\User;

require_once __DIR__ . '/controllers.php';
/**
 * @OA\Info(
 *   title="User APIs",
 *   description="Log",
 *   version="0.0.1",
 * )
 */
$router->addGroup("/user", function (FastRoute\RouteCollector $router) {
  $controller = new User();
  /**
   * @OA\Get(
   *     path="/api/user/config",
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('GET', '/config', [$controller, 'get__table']);
  /**
   * @OA\Post(
   *     path="/api/user/insert",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),     
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/insert', [$controller, 'insert_item']);
  /**
   * @OA\Post(
   *     path="/api/user/delete",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/delete', [$controller, 'delete_list']);
  /**
   * @OA\Post(
   *     path="/api/user/update",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/update', [$controller, 'update_item']);
  /**
   * @OA\Post(
   *     path="/api/user/count",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/count', [$controller, 'select_count']);
  /**
   * @OA\Post(
   *     path="/api/user/list",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/list', [$controller, 'select_list']);
  /**
   * @OA\Post(
   *     path="/api/user/tree",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/tree', [$controller, 'select_tree']);
  /**
   * @OA\Post(
   *     path="/api/user/info",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/UserModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/info', [$controller, 'select_item']);
});
