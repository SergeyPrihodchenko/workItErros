<?php

namespace Sergo\PHP\Interfaces\Authentification;

use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\Users\User;

Interface InterfaceAuthentification {
    public function user(Request $request): User;
}