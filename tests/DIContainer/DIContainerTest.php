<?php

namespace Sergo\PHP\phpunit\DIContainer;

use PHPUnit\Framework\TestCase;
use Sergo\PHP\Class\Container\DIContainer;
use Sergo\PHP\Class\Exceptions\NotFoundException;
use Sergo\PHP\Class\Repository\RepositoryUsers;
use Sergo\PHP\Interfaces\Repository\InterfaceRepositoryUsers;

class DIContainerTest extends TestCase {
    
    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        $container = new DIContainer();

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: Sergo\PHP\phpunit\DIContainer\SomeClass'
        );

        $container->get(SomeClass::class);
    }

    public function testItResolvesClassWithotDependencies(): void 
    {
        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42));

        $object = $container->get(ClassDependingOnAnother::class);

        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }

    public function testItResolvesClassByContract(): void 
    {
        $container = new DIContainer();

        $container->bind(
            InterfaceRepositoryUsers::class,
            RepositoryUsers::class
        );

        $object = $container->get(InterfaceRepositoryUsers::class);

        $this->assertInstanceOf(
            RepositoryUsers::class,
            $object
        );
    }

    public function testItReturnsPredefindeObject(): void
    {
        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );

        $this->assertSame(42, $object->value());
    }
}