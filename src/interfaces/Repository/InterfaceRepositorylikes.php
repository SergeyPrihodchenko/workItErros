<?php

namespace Sergo\PHP\Interfaces\Repository;

use Sergo\PHP\Class\Users\Like;

interface InterfaceRepositoryLikes 
{
    public function save(Like $like): void;

    public function getByPostUuid(string $uuid): array;
}