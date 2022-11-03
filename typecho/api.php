<?php
global $_SWAGGER;
$module = "typecho";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);

use Langnang\Module\Typecho\TypechoMetaController;
use Langnang\Module\Typecho\TypechoContentController;

require_once __DIR__ . '/controllers.php';
/**
 * @OA\Info(
 *   title="Typecho APIs",
 *   description="Typecho",
 *   version="0.0.1",
 * )
 */
$router->addGroup("/typecho", function (FastRoute\RouteCollector $router) {
  /**
   * @OA\Tag(
   *     name="meta",
   *     description="Typecho Meta",
   * )
   */
  $router->addGroup("/meta", function (FastRoute\RouteCollector $router) {

    $controller = new TypechoMetaController();
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/insert",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),     
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/insert', [$controller, 'execute_insert_item']);
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/delete",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/delete', [$controller, 'execute_delete_list']);
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/update",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/update', [$controller, 'execute_update_item']);
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/count",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/count', [$controller, 'execute_select_count']);
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/list",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/list', [$controller, 'execute_select_list']);
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/tree",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/tree', [$controller, 'execute_select_tree']);
    /**
     * @OA\Post(
     *     path="/api/typecho/meta/info",
     *     tags={"meta"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoMetaModel")
     *     ),
     *     @OA\Response(response="200", description="")
     * )
     */
    $router->addRoute('POST', '/info', [$controller, 'execute_select_item']);
  });
  /**
   * @OA\Tag(
   *     name="content",
   *     description="Typecho Content",
   * )
   */
  $router->addGroup("/content", function (FastRoute\RouteCollector $router) {
    $controller = new TypechoContentController();
    /**
     * @OA\Post(
     *     path="/api/typecho/content/insert",
     *     tags={"content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoContentModel")
     *     ),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    $router->addRoute('POST', '/insert', [$controller, 'execute_insert_item']);
    /**
     * @OA\Post(
     *     path="/api/typecho/content/import",
     *     tags={"content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoContentModel")
     *     ),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    $router->addRoute('POST', '/import', [$controller, 'execute_import_list']);
    /**
     * @OA\Post(
     *     path="/api/typecho/content/delete",
     *     tags={"content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoContentModel")
     *     ),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    $router->addRoute('POST', '/delete', [$controller, 'execute_delete_list']);
    /**
     * @OA\Post(
     *     path="/api/typecho/content/update",
     *     tags={"content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoContentModel")
     *     ),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    $router->addRoute('POST', '/update', [$controller, 'execute_update_item']);
    /**
     * @OA\Post(
     *     path="/api/typecho/content/list",
     *     tags={"content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoContentModel")
     *     ),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    $router->addRoute('POST', '/list', [$controller, 'execute_select_list']);
    /**
     * @OA\Post(
     *     path="/api/typecho/content/info",
     *     tags={"content"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/TypechoContentModel")
     *     ),
     *     @OA\Response(response="200", description="Success")
     * )
     */
    $router->addRoute('POST', '/info', [$controller, 'execute_select_item']);
  });
});
