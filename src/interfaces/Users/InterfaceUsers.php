<?php

namespace Sergo\PHP\Interfaces\Users;

use Sergo\PHP\Class\Persone\Name;

interface InterfaceUsers {

    public function uuid(): string;

    public function first_name(): string;

    public function last_name(): string;

    public function full_name(): string;
    
    public function hashedPassword(): string;

    static function createFrom(Name $name, string $password): self;
}
