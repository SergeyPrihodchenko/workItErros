<?php

namespace Sergo\PHP\Class\Users;

use Sergo\PHP\Interfaces\Users\InterfaceLike;
use Sergo\PHP\Class\UUID\UUID;

class Like implements InterfaceLike {

    public function __construct(
        private UUID $uuid,
        private UUID $postUUID,
        private UUID $userUUID
    )
    {
    }

    public function UUID(): string 
    {
        return $this->uuid;
    }
    public function postUUID(): string 
    {
        return $this->postUUID;
    }
    public function userUUID(): string 
    {
        return $this->userUUID;
    }
}