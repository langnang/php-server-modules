<?php
// TODO
global $_SWAGGER;
$module = "toolkit";
array_push($_SWAGGER, ["name" => "{$module}", "url" => "/?/api/swagger/{$module}", "path" => __DIR__]);
/**
 * @OA\Info(title="Toolkit")
 */
$router->addGroup("/toolkit", function (FastRoute\RouteCollector $router) {
});
