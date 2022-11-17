<?php

namespace Sergo\PHP\Interfaces\Users;

interface InterfaceLike {

    public function UUID(): string;

    public function postUUID(): string;

    public function userUUID(): string;

}