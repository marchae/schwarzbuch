<?php

namespace App\SharedKernel;

use ReflectionClass;
use Symfony\Component\EventDispatcher\Event;

abstract class DomainEvent extends Event
{
    abstract public static function fromPayload(array $payload): self;

    abstract public function getPayload(): array;

    final public function getClassName(): string
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
