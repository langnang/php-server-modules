<?php
global $_SWAGGER;
$module = "quote";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);

use Langnang\Module\Api\Api;
use Langnang\Module\Content\Content;
use Langnang\Module\Quote\Quote;
use WpOrg\Requests\Requests;

require_once __DIR__ . '/controllers.php';
/**
 * @OA\Info(
 *   title="Quote APIs",
 *   description="Quote API",
 *   version="0.0.1",
 * )
 */
$router->addGroup("/{$module}", function (FastRoute\RouteCollector $router) {
  $controller = new Quote();
  /**
   * @OA\Post(
   *     path="/api/quote/insert",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),     
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/insert', [$controller, 'insert_item']);
  /**
   * @OA\Post(
   *     path="/api/quote/delete",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/delete', [$controller, 'delete_list']);
  /**
   * @OA\Post(
   *     path="/api/quote/update",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/update', [$controller, 'update_item']);
  /**
   * @OA\Post(
   *     path="/api/quote/count",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/count', [$controller, 'select_count']);
  /**
   * @OA\Post(
   *     path="/api/quote/list",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/list', [$controller, 'select_list']);
  /**
   * @OA\Post(
   *     path="/api/quote/tree",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/tree', [$controller, 'select_tree']);
  /**
   * @OA\Post(
   *     path="/api/quote/info",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(ref="#/components/schemas/QuoteModel")
   *     ),
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('POST', '/info', [$controller, 'select_item']);
  /**
   * @OA\Get(
   *     path="/api/quote/rand",
   *     @OA\Response(response="200", description="")
   * )
   */
  $router->addRoute('GET', '/rand', [$controller, 'crawler_rand']);
});
