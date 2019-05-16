<?php

declare(strict_types = 1);

namespace App\Common;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
abstract class AggregateRoot
{
    /**
     * @var DomainEvent[]
     */
    private $domainEvents = [];

    abstract public function id(): string;

    public function raise(DomainEvent $event)
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @return DomainEvent[]
     */
    public function popRecordedEvents(): array
    {
        $pendingEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $pendingEvents;
    }
}
