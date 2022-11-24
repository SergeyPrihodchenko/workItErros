<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Authentification\JsonBodyUsernameIdentification;
use Sergo\PHP\Class\Authentification\JsonBodyUuidIdentification;
use Sergo\PHP\Class\Container\DIContainer;
use Sergo\PHP\Class\Repository\RepositoryComments;
use Sergo\PHP\Class\Repository\RepositoryLikes;
use Sergo\PHP\Class\Repository\RepositoryPosts;
use Sergo\PHP\Class\Repository\RepositoryUsers;
use Sergo\PHP\Interfaces\Authentication\InterfaceIdentification;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

require_once __DIR__ . '/vendor/autoload.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$logger = (new Logger('blog'));

if('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
    ->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log'))
    ->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.error.log',
        level: Logger::ERROR,
        bubble: false
    ));
}

if('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
   ->pushHandler(
    new StreamHandler("php://stdout")
   );
}

$container->bind(
    LoggerInterface::class,
    $logger
);

$container->bind(
    PDO::class,
    new PDO('sqlite:' . $_SERVER['SQLITE_DB_PATH'])
);

$container->bind(
    InterfaceRepositoryPosts::class,
    RepositoryPosts::class
);
$container->bind(
    InterfaceIdentification::class,
    JsonBodyUuidIdentification::class 
);
$container->bind(
    InterfaceIdentification::class,
    JsonBodyUsernameIdentification::class 
);
$container->bind(
    InterfacerepositoryPosts::class,
    RepositoryPosts::class
);

$container->bind(
    InterfaceRepositoryUsers::class,
    RepositoryUsers::class
);

$container->bind(
    InterfaceRepositoryComments::class,
    RepositoryComments::class
);
$container->bind(
    InterfaceRepositoryLikes::class,
    RepositoryLikes::class
);


return $container;