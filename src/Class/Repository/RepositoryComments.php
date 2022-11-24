<?php

namespace Sergo\PHP\Class\Repository;

use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;

class RepositoryComments implements InterfaceRepositoryComments {
    public function __construct(
        private \PDO $connect,
        private LoggerInterface $logger
    )
    {
    }

    public function save(Comments $comment): void 
    {
        $connection = $this->connect;
        $uuid = $comment->uuid();
        $statement = $connection->prepare("INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text);");

        $statement->execute([':uuid' => $comment->uuid(), ':post_uuid' => $comment->idPost(), ':author_uuid' => $comment->idUser(), ':text' => $comment->text()]);
        $this->logger->info("Create comment UUID:$uuid");
    }

    public function getByUUIDinComments($uuid): Comments
    {
        $connection = $this->connect;

        $statement = $connection->prepare("SELECT * FROM comments WHERE uuid = :uuid ;");
        $statement->execute([':uuid' => $uuid]);

        return $this->fetch($statement);
    }

    private function fetch($statement): Comments
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if($result === false) {
            $this->logger->warning("No such element");
            throw new RepositoryException('No such element');
        }
        return new Comments(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            new UUID($result['post_uuid']),
            $result['text']);
    }
}