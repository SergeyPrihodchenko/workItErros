<?php

namespace Sergo\PHP\interface;

use Sergo\PHP\Class\Users\Comments;

interface InterfaceRepositoryComments {

    public function save(Comments $comment): void;

    public function getByUUIDinComments($uuid): Comments;
    
}