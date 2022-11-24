<?php

namespace Sergo\PHP\Class\HTTP\Request;

use JsonException;
use Sergo\PHP\Class\Exceptions\HttpException;
use Sergo\PHP\interfaces\HTTP\Request\InterfaceRequest;

class Request implements InterfaceRequest {

    public function __construct(
        private array $get,
        private array $server,
        private string $body
    )
    {
    }

    private function jsonBody(): array
    {
        try {
            $data = json_decode(
                $this->body,
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        } catch (JsonException $e) {
            throw new HttpException('Cannot decode json body'); 
        }

        if(!is_array($data)) {
            throw new HttpException('Not an array/object in json body');
        }

        return $data;
    }

    public function method(): string 
    {
        
        if(!array_key_exists('REQUEST_METHOD', $this->server)) {

            throw new HttpException("Cannot get method from the request");

        }

        return $this->server['REQUEST_METHOD'];
    }

    public function jsonBodyField(string $field): mixed
    {
        $data = $this->jsonBody();

        if(!array_key_exists($field, $data)) {

            throw new HttpException("No suth field: $field");

        }

        if(empty($data[$field])) {

            throw new HttpException("Empty field: $field");
        }

        return $data[$field];
    }

    public function path(): string
    {
        if(!array_key_exists('REQUEST_URI', $this->server)) {
            throw new HttpException('Cannot get path the request');
        }

        $components = parse_url($this->server['REQUEST_URI']);

        if(!is_array($components) || !array_key_exists('path', $components)) {
            throw new HttpException('Cannot get path from thr request');
        }

        return $components['path'];
    }

    public function query(string $param): string
    {
        if(!array_key_exists($param, $this->get)) {
            throw new HttpException('No such query param in the request' . $param);
        }

        $value = trim($this->get[$param]);

        if (empty($value)) {
            throw new HttpException("Empty query param in the request: $param");
        }

        return $value;
    }

    public function header(string $header): string
    {
        $headerName = mb_strtoupper("http_" . str_replace('-', '_', $header));

        if(!array_key_exists($headerName, $this->server)) {
            throw new HttpException('No such header in the request:' . $header);
        }

        $value = trim($this->server[$headerName]);

        if(empty($value)) {
            throw new HttpException('Empty header in the request' . $header);
        }

        return $value;
    }
}