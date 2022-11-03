<?php

namespace Sergo\PHP\Class\Repository;

use PDO;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\interfaces\Repository\InterfaceRepositoryPosts;

class RepositoryPosts implements InterfaceRepositoryPosts {

    public function __construct(
        private PDO $connect 
    )
    {
    }

    public function save(Posts $post): void
    {
        $connection = $this->connect;

        $statment = $connection->prepare("INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text );");

        $statment->execute([':uuid' => $post->uuid(), ':author_uuid' => $post->idUser(), 'title' => $post->title(), 'text' => $post->text()]);
    }

    public function getByUUIDinPosts(UUID $uuid): Posts 
    {
        $connection = $this->connect;

        $statement = $connection->prepare("SELECT * FROM posts WHERE uuid = :uuid;");
        $statement->execute([':uuid' => $uuid]);

        return $this->fetch($statement);
    }

    private function fetch($statement): Posts
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return new Posts(new UUID($result['uuid']), new UUID($result['author_uuid']), $result['title'], $result['text']);
    }
}