<?php
namespace Sergo\PHP\phpunit\action;

use PHPUnit\Framework\TestCase;
use Sergo\PHP\Class\Container\DIContainer;
use Sergo\PHP\Class\Exceptions\NotFoundException;
use Sergo\PHP\phpunit\action\SomeClassWithoutDependencies;

class DIContainerTest extends TestCase 
{

    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {
        // Создаём объект контейнера
        $container = new DIContainer();
        // Описываем ожидаемое исключение
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage(
        'Cannot resolve type: tests\action\SomeClassWithoutDependencies'
        );
        // Пытаемся получить объект несуществующего класса
        $container->get(SomeClassWithoutDependencies::class);
    }

    public function testItResolvesClassWithoutDependencies(): void
    {

        $container = new DIContainer();


        $object = $container->get(SomeClassWithoutDependencies::class);

        $this->assertInstanceOf(
        SomeClassWithoutDependencies::class,
        $object
        );
    }


}