<?php

namespace Sergo\PHP\Interfaces\Users;

interface InterfacePosts {

    public function uuid(): string;

    public function idUser(): string;

    public function title(): string;

    public function text(): string;
}