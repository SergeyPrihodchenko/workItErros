<?php

namespace Sergo\PHP\Class\Repository;

use Exception;
use PDO;
use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Exceptions\RepositoryException;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryLikes;
use Sergo\PHP\Class\Users\Like;
use Sergo\PHP\Class\UUID\UUID;

class RepositoryLikes implements InterfaceRepositoryLikes {

    public function __construct(
        private PDO $connect,
        private LoggerInterface $logger
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

           $uuid = $like->UUID();
            $statement = $this->connect->prepare('INSERT INTO likes VALUES (:uuid, :postuuid, :useruuid);');
        $statement->execute([
            ':uuid' => $like->UUID(),
            ':postuuid' => $like->postUUID(),
            ':useruuid' => $like->userUUID() ]);
            $this->logger->info("create like UUID:$uuid");
        }
    }

    public function delete($uuid): void {

        $statement = $this->connect->prepare("DELETE FROM likes WHERE uuid = :uuid");
        $statement->execute([':uuid' => $uuid]);
        $this->logger->info("delete like UUID:$uuid");
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
            $this->logger->warning('No such element');
            throw new RepositoryException('No such element');
        }

        foreach ($result as $value) {
            try {
                $like = new Like(
                    new UUID($value['uuid']),
                    new UUID($value['postuuid']),
                    new UUID($value['useruuid'])
                );
            } catch (\Exception $e) {
                throw new Exception($e->getMessage());
            }

            $likes[] = $like;
        }
        return $likes;
    }
}