<?php

use Sergo\PHP\Class\Exception\HttpException;
use Sergo\PHP\Class\HTTP\actionHTTP\addComments;
use Sergo\PHP\class\HTTP\actionHTTP\deletePost;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUsernameInUsers;
use Sergo\PHP\Class\HTTP\actionHTTP\FindByUUIDinPosts;
use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\Repository\RepositoryComments;
use Sergo\PHP\Class\Repository\RepositoryPosts;
use Sergo\PHP\Class\Repository\RepositoryUsers;

require_once __DIR__ . '/vendor/autoload.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

try {
    $path = $request->path();
} catch (HttpException $e) {
    throw $e->getMessage();
}

$routes = [
    '/users/find' => new FindByUsernameInUsers(new RepositoryUsers(new PDO('sqlite:learndb.db'))),
    '/posts/find' => new FindByUUIDinPosts(new RepositoryPosts(new PDO('sqlite:learndb.db'))),
    '/posts/comment' => $request->method() === 'POST' ? new addComments(new RepositoryComments( new PDO('sqlite:learndb.db'))) : null,
    '/posts' => new deletePost(new RepositoryPosts(new PDO('sqlite:learndb.db')))
];

$routes[$path]->handle($request);
