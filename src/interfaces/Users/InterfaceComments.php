<?php

namespace Sergo\PHP\interface;

interface InterfaceComments {

    public function uuid(): string;

    public function idUser(): string;

    public function idPost(): string;

    public function text(): string;
}