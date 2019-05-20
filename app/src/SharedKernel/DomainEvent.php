<?php

namespace App\SharedKernel;

use ReflectionClass;

abstract class DomainEvent
{
    abstract public static function fromPayload(array $payload): self;

    abstract public function getPayload(): array;

    final public function getShortClassName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }

    final public function getFullyQualifiedClassName(): string
    {
        return static::class;
    }
}
