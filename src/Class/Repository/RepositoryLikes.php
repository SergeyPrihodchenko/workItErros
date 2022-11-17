<?php

namespace Sergo\PHP\Class\Repository;

use Exception;
use PDO;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;
use Sergo\PHP\Class\Users\Like;
use Sergo\PHP\Class\UUID\UUID;

class RepositoryLikes implements InterfaceRepositoryLikes {

    public function __construct(
        private PDO $connect
    )
    {
    }

    private function checkLike($postuuid, $useruuid) {
        $statement = $this->connect->query("SELECT uuid FROM likes WHERE postuuid = '$postuuid' OR useruuid = '$useruuid' ;");

        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    public function save(Like $like): void 
    {
        if((bool)($this->checkLike($like->postUUID(), $like->userUUID()))  === false) {
            var_dump((bool)($this->checkLike($like->postUUID(), $like->userUUID())));
            $statement = $this->connect->prepare('INSERT INTO likes VALUES (:uuid, :postuuid, :useruuid);');
        $statement->execute([
            ':uuid' => $like->UUID(),
            ':postuuid' => $like->postUUID(),
            ':useruuid' => $like->userUUID() ]);
        }
    }

    public function getByPostUuid(string $uuid): array 
    {

        $statement = $this->connect->prepare("SELECT * FROM likes WHERE postuuid = :uuid ;");
        $statement->execute(
            [':uuid' => $uuid]
        );

        return $this->fetch($statement);
    }

    private function fetch($statement): array 
    {
        $likes = [];

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if($result === false) {
            throw new RepositoryException('**********No such element');
        }

        foreach ($result as $value) {
            try {
                $like = new Like(
                    new UUID($value['uuid']),
                    new UUID($value['postuuid']),
                    new UUID($value['useruuid'])
                );
            } catch (\Exception $e) {
                throw new Exception('*********** ' . $e->getMessage());
            }

            $likes[] = $like;
        }
        var_dump($likes);
        return $likes;
    }
}