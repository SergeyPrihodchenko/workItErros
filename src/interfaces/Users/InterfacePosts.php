<?php

namespace Sergo\PHP\Interfaces\Users;

use Sergo\PHP\Class\UUID\UUID;

interface InterfacePosts {

    public function uuid(): UUID;

    public function idUser(): string;

    public function title(): string;

    public function text(): string;
}