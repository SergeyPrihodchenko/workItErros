<?php

namespace Sergo\PHP\phpunit\DIContainer;

class ClassDependingOnAnother {

    public function __construct(

        private SomeClassWithoutDependencies $one,
        private SomeClassWithParameter $two
    ) 
    {}
}