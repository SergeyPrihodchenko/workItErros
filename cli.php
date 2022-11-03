<?php

use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Repository\{RepositoryComments, RepositoryUsers, RepositoryPosts};
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:learndb.db');

try {
    $repositoryUser = new RepositoryUsers($connection);

    $user = $repositoryUser->getByUUIDInUsers(new UUID('f8dc79e8-d009-4168-a80d-021d6d7ef86f'));
} catch (\Throwable $th) {
    throw $th;
}

try {
    $repositoryPost = new RepositoryPosts($connection);

    $post = $repositoryPost->getByUUIDinPosts(new UUID('fd4717c0-a09a-49b6-aa6a-2c7444706d72'));
} catch (\Throwable $th) {
    throw $th;
}

try {
    $repositoryComment = new RepositoryComments($connection);
  
    $comment = $repositoryComment->getByUUIDinComments(new UUID('1af349fb-bfb8-42ee-9dc2-782ba58826aa'));

    var_dump($comment);
} catch (\Throwable $th) {
    throw $th;
}
