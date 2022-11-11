<?php

namespace Sergo\PHP\Class\Users;

use Sergo\PHP\Interfaces\Users\InterfaceUsers;
use Sergo\PHP\Class\UUID\UUID;
use Sergo\PHP\interfaces\Persone\InterfaceName;

class User implements InterfaceUsers {
    public function __construct(
        private UUID $uuid,
        private InterfaceName $name
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