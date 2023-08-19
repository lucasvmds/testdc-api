<?php

declare(strict_types=1);

namespace App\Dto;

use App\Exceptions\Dto\PropertyNotFoundException;
use ReflectionClass;
use ReflectionProperty;

class Base
{
    public function __get(string $name): mixed
    {
        $this->validateProperty($name);
        return $this->{$name};
    }

    public function __set(string $name, mixed $value): void
    {
        $this->validateProperty($name);
        $this->{$name} = $value;
    }

    private function validateProperty(string $name): void
    {
        $class = get_called_class();
        $reflection = new ReflectionClass($class);
        $properties = $reflection->getProperties(ReflectionProperty::IS_READONLY);
        if (!array_search($name, $properties))
                throw new PropertyNotFoundException("Property [$name] not found in class [$class]");
    }
}