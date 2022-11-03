<?php

namespace Sergo\PHP\Class\Repository;

use Sergo\PHP\Class\Exception\RepositoryException;
use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryComments;

class RepositoryComments implements InterfaceRepositoryComments {
    public function __construct(
        private \PDO $connect
    )
    {
    }

    public function save(Comments $comment): void 
    {
        $connection = $this->connect;

        try {
            $statement = $connection->prepare("INSERT INTO comments (uuid, post_uuid, author_uuid, text) VALUES (:uuid, :post_uuid, :author_uuid, :text);");

            $statement->execute([':uuid' => $comment->uuid(), ':post_uuid' => $comment->idPost(), ':author_uuid' => $comment->idUser(), ':text' => $comment->text()]);

        } catch (RepositoryException $th) {
            throw $th->getMessage();
        }
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
            throw new RepositoryException('**********No such element');
        }
        return new Comments(
            new UUID($result['uuid']),
            new UUID($result['author_uuid']),
            new UUID($result['post_uuid']),
            $result['text']);
    }
}