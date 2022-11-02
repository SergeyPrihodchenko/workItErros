<?php

namespace Sergo\PHP\interface;

interface InterfaceUsers {

    public function uuid(): string;

    public function first_name(): string;

    public function last_name(): string;

    public function full_name(): string;
}