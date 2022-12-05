<?php

namespace Sergo\PHP\Class\Repository;

use PDO;
use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryPosts;

class RepositoryPosts implements InterfaceRepositoryPosts {

    public function __construct(
        private PDO $connect,
        private LoggerInterface $logger 
    )
    {
    }

    public function save(Posts $post): void
    {
        $connection = $this->connect;
        $uuid = $post->uuid();
        $statment = $connection->prepare("INSERT INTO posts (uuid, author_uuid, title, text) VALUES (:uuid, :author_uuid, :title, :text );");

        $statment->execute([':uuid' => $post->uuid(), ':author_uuid' => $post->idUser(), 'title' => $post->title(), 'text' => $post->text()]);

        $this->logger->info("Create post UUID:$uuid");
    }

    public function delete(string $uuid): void
    {
        $connection = $this->connect;

        $statement = $connection->prepare("DELETE FROM posts WHERE uuid = :uuid;");

        $statement->execute([':uuid' => $uuid]);
    }

    public function getByUUIDinPosts(UUID $uuid): Posts 
    {
        $connection = $this->connect;

        $statement = $connection->prepare("SELECT * FROM posts WHERE uuid = :uuid;");
        $statement->execute([':uuid' => $uuid]);

        return $this->fetch($statement);
    }
    public function getByUuidAuthorInPosts(UUID $uuid): Posts 
    {
        $connection = $this->connect;

        $statement = $connection->prepare("SELECT * FROM posts WHERE author_uuid = :author_uuid;");
        $statement->execute([':author_uuid' => $uuid]);

        return $this->fetch($statement);
    }

    private function fetch($statement): Posts
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if($result == null) {
            $this->logger->warning("Post not found");
            exit();
        }

        return new Posts(new UUID($result['uuid']), new UUID($result['author_uuid']), $result['title'], $result['text']);
    }
}