<?php

global $_SWAGGER;
$module = "comment";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);

use Langnang\Module\Comment\Comment;

require_once __DIR__ . '/controllers.php';
/**
 * @OA\Info(
 *   title="Comment APIs",
 *   description="Log",
 *   version="0.0.1",
 * )
 */
$router->addGroup("/{$module}", function (FastRoute\RouteCollector $router) {
  $controller = new Comment();
  /**
   * @OA\Get(
   *     path="/api/comment/config",
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('GET', '/config', [$controller, 'get__table']);
  /**
   * @OA\Post(
   *     path="/api/comment/insert",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),     
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/insert', [$controller, 'insert_item']);
  /**
   * @OA\Post(
   *     path="/api/comment/delete",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/delete', [$controller, 'delete_list']);
  /**
   * @OA\Post(
   *     path="/api/comment/update",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/update', [$controller, 'update_item']);
  /**
   * @OA\Post(
   *     path="/api/comment/count",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/count', [$controller, 'select_count']);
  /**
   * @OA\Post(
   *     path="/api/comment/list",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/list', [$controller, 'select_list']);
  /**
   * @OA\Post(
   *     path="/api/comment/tree",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/tree', [$controller, 'select_tree']);
  /**
   * @OA\Post(
   *     path="/api/comment/info",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/CommentModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/info', [$controller, 'select_item']);
});
