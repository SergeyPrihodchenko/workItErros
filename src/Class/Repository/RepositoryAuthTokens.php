<?php

namespace Sergo\PHP\Class\Repository;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Exception;
use PDO;
use PDOException;
use Sergo\PHP\Class\Authentification\AuthToken;
use Sergo\PHP\Class\Exceptions\AuthTokenNotFoundException;
use Sergo\PHP\Class\Exceptions\AuthTokenRepositoryException;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryAuthToken;

class RepositoryAuthTokens implements InterfaceRepositoryAuthToken
{

    public function __construct(
        private \PDO $connection
    ) {
    }

    public function save(AuthToken $authToken): void
    {
        $query = <<<'SQL'
            INSERT INTO tokens (
                token,
                user_uuid,
                expires_on
            ) VALUES (
                :token,
                :user_uuid,
                :expires_on
            )
            ON CONFLICT (token) DO UPDATE SET
            expires_on = :expires_on
        SQL;

        try {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                ":token" => (string)$authToken->token(),
                ":user_uuid" => (string)$authToken->userUuid(),
                "expires_on" => $authToken->expiresOn()->format(DateTimeInterface::ATOM),
            ]);
        } catch (PDOException $e) {
            throw new AuthTokenRepositoryException(
                $e->getMessage(),
                (int)$e->getCode,
                $e
            );
        }
    }

    public function get(string $token): AuthToken
    {

        try {
            $statement = $this->connection->prepare(
                "SELECT * FROM tokens WHERE token = ?;"
            );
            $statement->execute([$token]);
            $result = $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new AuthTokenRepositoryException($e->getMessage(), (int)$e->getCode(), $e);
        }

        if (false === $result) {
            throw new AuthTokenNotFoundException("Cannot find token: $token");
        }

        try {
            return new AuthToken(
                $result['token'],
                new UUID($result['user_uuid']),
                new DateTimeImmutable($result['expires_on'])
            );
        } catch (Exception $e) {
            throw new AuthTokenRepositoryException(
                $e->getMessage(),
                $e->getCode(),
                $e
            );
        }
    }

    public function update(string $token): void
    {
        $date = (new DateTimeImmutable('', new DateTimeZone("Europe/Moscow")))->format(DateTime::ATOM);
        $statement = $this->connection->prepare("UPDATE tokens SET expires_on=:date WHERE token=:token");
        $statement->execute([":date" => $date, ":token" => $token]);
    }
}
