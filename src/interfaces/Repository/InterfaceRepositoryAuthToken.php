<?php

namespace Sergo\PHP\Interfaces\Repository;

use Sergo\PHP\Class\Authentification\AuthToken;

interface InterfaceRepositoryAuthToken
{

    public function save(AuthToken $authToken): void;

    public function get(string $token): AuthToken;

    public function update(string $token): void;
}
