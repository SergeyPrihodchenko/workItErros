<?php

namespace Sergo\PHP\Class\Users;

use Sergo\PHP\Class\UUID\UUID;

class Comments {
    public function __construct(
        private UUID $uuid,
        private UUID $idUser,
        private UUID $idPost,
        private string $text
    )
    {
        
    }

    public function uuid(): string
    {
        return $this->uuid;
    }

    public function idUser(): string
    {
        return $this->idUser;
    }

    public function idPost(): string
    {
        return $this->idPost;
    }

    public function text(): string
    {
        return $this->text;
    }
}