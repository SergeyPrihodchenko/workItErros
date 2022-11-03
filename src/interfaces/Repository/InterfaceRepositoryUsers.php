<?php

namespace Sergo\PHP\Interfaces\Repository;

use Sergo\PHP\Class\Users\User;
use Sergo\PHP\Class\UUID\UUID;

interface InterfaceRepositoryUsers {

    public function save(User $user): void;

    public function getByUsernameInUsers(string $username): User;

    public function getByUUIDInUsers(UUID $uuid): User;
}