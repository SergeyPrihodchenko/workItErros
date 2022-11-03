<?php

namespace Sergo\PHP\Class\Repository;

use \PDO;
use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class RepositoryUsers implements InterfaceRepositoryUsers {

    public function __construct(
        private PDO $connect
    )
    {
        
    }

    public function save(User $user): void
    {
        $connection = $this->connect;
        $statement = $connection->prepare("INSERT INTO users (uuid, username, first_name, last_name) VALUES (:uuid, :username, :first_name, :last_name);");
        $statement->execute([
            ':uuid' => $user->uuid(),
            ':username' => $user->full_name(),
            ':first_name' => $user->first_name(),
            ':last_name' => $user->last_name()
        ]);
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

        return new User(new UUID($result['uuid']),
         new Name($result['first_name'], $result['last_name']));
    }
}