<?php

namespace App\SharedKernel;

interface IEventSourced
{
    public function getId(): string;

    /**
     * @param array|DomainEvent[] $events
     *
     * @return IEventSourced
     */
    public static function replay(array $events): self;


    /**
     * @return array|DomainEvent[]
     */
    public function popDomainEvents(): array;
}
