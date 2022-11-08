<?php

namespace Sergo\PHP\Interfaces\HTTP\actionHTTP;

use Sergo\PHP\Class\HTTP\Request\Request;
use Sergo\PHP\Class\HTTP\Response\Response;

interface InterfaceAction {
    public function handle(Request $request): Response;
}