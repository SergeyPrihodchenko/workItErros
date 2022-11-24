<?php

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\actionHTTP\AddComments;
use Sergo\PHP\Class\HTTP\actionHTTP\AddLike;
use Sergo\PHP\Class\HTTP\actionHTTP\AddPost;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUsernameInUsers;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDInLikes;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDinPosts;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\ErrorResponse;

$container = require_once __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);
try {
    $path = $request->path();

} catch (HttpException) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    if ('yes' == $_SERVER['LOG_TO_CONSOLE']) {
        $logger->warning($e->getMessage());
    }
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
        '/comments/create' => AddComments::class,
        '/posts/create' => AddPost::class
    ],
];

if(!array_key_exists($method, $routes)) {
    $logger->notice("Route not found: $method $path");
    (new ErrorResponse("Route not found: $method $path"))->send();
    return;
}

if(!array_key_exists($path, $routes[$method])) {
    $logger->notice("Route not found: $method $path");  
    (new ErrorResponse("route not fond: $method $path"))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);

} catch (HttpException $e) {
    $logger->error($e->getMessage());
    (new ErrorResponse($e->getMessage()))->send();
}

$response->send();