<?php

use Sergo\PHP\Class\Commands\PopulateDB;
use Sergo\PHP\Class\Commands\Posts\DeletePost;
use Sergo\PHP\Class\Commands\Users\CreateUser;
use Sergo\PHP\Class\Commands\Users\UpdateUser;
use Symfony\Component\Console\Application;

$container  = require_once __DIR__ . '/bootstrap.php';

$aplication = new Application();

$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class

];

foreach ($commandsClasses as $commandClass) {

    $command = $container->get($commandClass);

    $aplication->add($command);
}

$aplication->run();
