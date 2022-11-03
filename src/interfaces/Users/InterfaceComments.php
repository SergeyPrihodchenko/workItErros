<?php

namespace Sergo\PHP\Interfaces\Users;

interface InterfaceComments {

    public function uuid(): string;

    public function idUser(): string;

    public function idPost(): string;

    public function text(): string;
}