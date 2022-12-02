<?php

namespace Sergo\PHP\Interfaces\Repository;

use Sergo\PHP\Class\Users\Comments;
use Sergo\PHP\Class\UUID\UUID;

interface InterfaceRepositoryComments {

    public function save(Comments $comment): void;

    public function getByUUIDinComments($uuid): Comments;

    public function delete(string $uuid): void;
    
}