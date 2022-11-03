<?php

namespace Sergo\PHP\Class\Users;

use Sergo\PHP\Interfaces\Users\InterfacePosts;
use Sergo\PHP\Class\UUID\UUID;


class Posts implements InterfacePosts {
    public function __construct(
        private UUID $uuid,
        private UUID $idUser,
        private string $title,
        private string $text
    )
    {
        
    }

    public function uuid(): UUID
    {
        return $this->uuid;
    }

    public function idUser(): string
    {
        return $this->idUser;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function text(): string
    {
        return $this->text;
    }
 }