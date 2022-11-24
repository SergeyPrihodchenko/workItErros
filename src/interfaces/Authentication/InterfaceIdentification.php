<?php

namespace Sergo\PHP\Interfaces\Authentication;

use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\Users\User;

Interface InterfaceIdentification {
    public function user(Request $request): User;
}