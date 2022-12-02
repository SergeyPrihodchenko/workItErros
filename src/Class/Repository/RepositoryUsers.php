<?php

namespace Sergo\PHP\Class\Repository;

use \PDO;
use Psr\Log\LoggerInterface;
use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class RepositoryUsers implements InterfaceRepositoryUsers {

    public function __construct(
        private PDO $connect,
        private LoggerInterface $logger
    )
    {
        
    }

    public function save(User $user): void
    {
        $connection = $this->connect;
        $uuid = $user->uuid();
        $statement = $connection->prepare("INSERT INTO users (uuid, username, first_name, last_name, password) VALUES (:uuid, :username, :first_name, :last_name, :password);");
        $statement->execute([
            ':uuid' => $user->uuid(),
            ':username' => $user->full_name(),
            ':first_name' => $user->first_name(),
            ':last_name' => $user->last_name(),
            ':password' => $user->hashedPassword()
        ]);
        $this->logger->info("User UUID:$uuid , add in table users");
    }

    public function delete(string $uuid): void {
        $statement = $this->connect->prepare("DELETE FROM users WHERE uuid = :uuid");

        $statement->execute([':uuid' => $uuid]);

        $this->logger->info("delete user UUID:$uuid");
    }

    public function getByUsernameInUsers(string $username): User
    {
        $connection = $this->connect;

        $statement = $connection->prepare("SELECT * FROM users WHERE username = :username ;");
        $statement->execute([':username' => $username]);

        return $this->fetch($statement);
    }

    public function getByUUIDInUsers(UUID $uuid): User
    {
        $connection = $this->connect;

        $statement = $connection->prepare("SELECT * FROM users WHERE uuid = :uuid ;");
        $statement->execute([':uuid' => $uuid]);
        
        return $this->fetch($statement);
    }

    private function fetch($statement): User
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if($result == null) {
            $this->logger->warning("Not found user");
            exit();
        }
        return new User(new UUID($result['uuid']),
         new Name($result['first_name'], $result['last_name']), $result['password']);
    }
}