<?php

namespace Sergo\PHP\Class\Persone;

use Sergo\PHP\interface\InterfaceName;

class Name implements InterfaceName {
    public function __construct(
        private string $first_name,
        private string $last_name,
    )
    {
        
    }

    public function first_name(): string
    {
        return $this->first_name;
    }

    public function last_name(): string
    {
        return $this->last_name;
    }

    public function full_name() {
        return $this->last_name . ' ' . $this->first_name;
    }
}