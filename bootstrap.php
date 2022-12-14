<?php

use Faker\Provider\ar_EG\Internet;
use Faker\Provider\ar_EG\Person;
use Faker\Provider\ar_EG\Text;
use Faker\Provider\Lorem;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Authentification\JsonBodyUsernameIdentification;
use Sergo\PHP\Class\Authentification\JsonBodyUuidIdentification;
use Sergo\PHP\Class\Authentification\PasswordAuthentification;
use Sergo\PHP\Class\Container\DIContainer;
use Sergo\PHP\Class\HTTP\actionHTTP\Token\BearerTokenAuthentification;
use Sergo\PHP\Class\Repository\RepositoryAuthTokens;
use Sergo\PHP\Class\Repository\RepositoryComments;
use Sergo\PHP\Class\Repository\RepositoryLikes;
use Sergo\PHP\Class\Repository\RepositoryPosts;
use Sergo\PHP\Class\Repository\RepositoryUsers;
use Sergo\PHP\Interfaces\Authentification\InterfaceIdentification;
use Sergo\PHP\Interfaces\Authentification\InterfacePasswordAuthentification;
use Sergo\PHP\Interfaces\Authentification\InterfaceTokenAuthentification;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryAuthToken;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

require_once __DIR__ . '/vendor/autoload.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$logger = (new Logger('blog'));

if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger
        ->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log'))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false
        ));
}

if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
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
    InterfacePasswordAuthentification::class,
    PasswordAuthentification::class
);

$container->bind(
    InterfaceTokenAuthentification::class,
    BearerTokenAuthentification::class
);

$container->bind(
    InterfaceRepositoryAuthToken::class,
    RepositoryAuthTokens::class
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

$faker = new \Faker\Generator();

$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));

$container->bind(
    \Faker\Generator::class,
    $faker
);


return $container;
