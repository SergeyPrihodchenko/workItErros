<?php
                  
namespace Sergo\PHP\Interfaces\Repository;

use Sergo\PHP\Class\Users\Posts;
use Sergo\PHP\Class\UUID\UUID;

interface InterfaceRepositoryPosts {

    public function save(Posts $post): void;

    public function delete(UUID $uuid): void;

    public function getByUUIDinPosts(UUID $uuid): Posts;

}