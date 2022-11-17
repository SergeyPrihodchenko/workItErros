<?php

use Sergo\PHP\Class\Container\DIContainer;
use Sergo\PHP\Class\Repository\RepositoryComments;
use Sergo\PHP\Class\Repository\RepositoryLikes;
use Sergo\PHP\Class\Repository\RepositoryPosts;
use Sergo\PHP\Class\Repository\RepositoryUsers;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer();

$container->bind(
    PDO::class,
    new PDO('sqlite:learndb.db')
);

$container->bind(
    InterfaceRepositoryPosts::class,
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