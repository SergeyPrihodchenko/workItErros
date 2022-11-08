<?php

namespace Sergo\PHP\Class\HTTP\Response;

use Sergo\PHP\interfaces\HTTP\Response\InterfaceResponse;

abstract class Response implements InterfaceResponse
{
    protected const SUCCESS = true;

    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();

        header('Content-Type: application/json');

        echo json_encode($data, JSON_THROW_ON_ERROR);
    }

    abstract protected function payload(): array;
}

