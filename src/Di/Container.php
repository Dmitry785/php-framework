<?php

namespace App\Di;

use ClosedGeneratorException;

class Container
{
    private $bindings = [];
    private $singletonIds = [];
    private $instances = [];

    public function get(string $id) : object{
        return new ClosedGeneratorException();
    }
    public function
}