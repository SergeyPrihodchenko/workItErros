<?php

namespace Sergo\PHP\Class\Users;

use Sergo\PHP\Class\Persone\Name;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\interface\InterfaceUsers;

class User implements InterfaceUsers {
    public function __construct(
        private UUID $uuid,
        private Name $name
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
}