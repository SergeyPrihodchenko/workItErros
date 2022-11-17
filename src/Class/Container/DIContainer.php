<?php

namespace Sergo\PHP\Class\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use Sergo\PHP\Class\Exceptions\NotFoundException;

class DIContainer implements ContainerInterface
{

    private array $resolves = [];

    public function bind(string $type, $resolver): void {

        $this->resolves[$type] = $resolver;

    }

    public function get(string $type): object
    {
        if(array_key_exists($type, $this->resolves)) {

            $typeToCreate = $this->resolves[$type];

            if(is_object($typeToCreate)) {
                return $typeToCreate;
            }

            return $this->get($typeToCreate);
        }

        if(!class_exists($type)) {
            throw new NotFoundException("Cannot resolve type: $type");
        }

        $reflectionClass = new ReflectionClass($type);

        $constructor = $reflectionClass->getConstructor();

        if($constructor == null) {
            return new $type();
        }

        $parameters = [];

        foreach($constructor->getParameters() as $parameter) {
            
            $parameterType = $parameter->getType()->getName();

            $parameters[] = $this->get($parameterType);
        }

        return new $type(...$parameters);
    }

    public function has(string $type): bool
    {
        try {
            $this->get($type);
            } catch (NotFoundException $e) {
            return false;
            }
    
            return true;
    }
}