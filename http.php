<?php

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\HTTP\actionHTTP\AddComments;
use Sergo\PHP\Class\HTTP\actionHTTP\AddLike;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUsernameInUsers;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDInLikes;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDinPosts;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;

$container = require_once __DIR__ . '/bootstrap.php';

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);
try {
    $path = $request->path();

} catch (HttpException) {
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException) {
    (new ErrorResponse())->send();
    return;
}

$routes = [
    'GET' => [
        '/users/show' => FindByUsernameInUsers::class,
        '/posts/show' => FindByUUIDinPosts::class,
        '/posts/like' => AddLike::class,
        '/posts/likes' => FindByUUIDInLikes::class
    ],
    'POST' => [
        '/posts/create' => AddComments::class
    ],
];

if(!array_key_exists($method, $routes)) {
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

if(!array_key_exists($path, $routes[$method])) {
    (new ErrorResponse("route not fond: $method $path"))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);

} catch (HttpException $e) {
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();