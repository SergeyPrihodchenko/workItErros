<?php

namespace Sergo\PHP\interfaces\HTTP\Request;

interface InterfaceRequest {

    public function path(): string;

    public function query(string $param): string;

    public function header(string $header): string;
}