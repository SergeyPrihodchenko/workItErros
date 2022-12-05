<?php

namespace Sergo\PHP\Class\Users;

use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Interfaces\Users\InterfaceUsers;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\interfaces\Persone\InterfaceName;

class User implements InterfaceUsers {
    public function __construct(
        private UUID $uuid,
        private InterfaceName $name,
        private string $hashedPassword
    )
    {
        
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function first_name(): string
    {
        return $this->name->first_name();
    }

    public function last_name(): string
    {
        return $this->name->last_name();
    }
    public function full_name(): string
    {
        return $this->name->full_name();
    }
    public function hashedPassword(): string
    {
        return $this->hashedPassword;
    }

    private static function hash(string $password, string $uuid): string 
    {
        return hash('sha256', $uuid . $password);
    }

    static function createFrom(
        Name $name,
        string $password
    ): self
    {
        $uuid = UUID::random();
        return new self(
            $uuid,
            $name,
            self::hash($password, $uuid)
        );
    }

    public function checkPassword(string $password): bool
    {
        return $this->hashedPassword === self::hash($password, $this->uuid);
    }
}