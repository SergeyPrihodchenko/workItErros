<?php

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\Class\HTTP\actionHTTP\AddComments;
use Sergo\PHP\Class\HTTP\actionHTTP\AddLike;
use Sergo\PHP\Class\HTTP\actionHTTP\AddPost;
use Sergo\PHP\Class\HTTP\actionHTTP\AddUser;
use Sergo\PHP\Class\HTTP\actionHTTP\DeleteCommentByUuid;
use Sergo\PHP\Class\HTTP\actionHTTP\DeleteLikeByUuid;
use Sergo\PHP\class\HTTP\actionHTTP\DeletePostByUuid;
use Sergo\PHP\Class\HTTP\actionHTTP\DeleteUserByUuid;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUsernameInUsers;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDComment;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDInLikes;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDinPosts;
use Sergo\PHP\Class\HTTP\actionHTTP\LogIn;
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
        '/posts/likes' => FindByUUIDInLikes::class,
        '/comment/show' => FindByUUIDComment::class,
    ],
    'POST' => [
        '/comments/create' => AddComments::class,
        '/posts/create' => AddPost::class,
        '/user/create' => AddUser::class,
        '/posts/like' => AddLike::class,
        '/login' => LogIn::class
    ],
    'DELETE' => [
        '/posts/delete' => DeletePostByUuid::class,
        '/comments/delete' => DeleteCommentByUuid::class,
        '/users/delete' => DeleteUserByUuid::class,
        '/like/delete' => DeleteLikeByUuid::class
    ]
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