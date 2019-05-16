<?php

declare(strict_types = 1);

namespace App\Common;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
abstract class EventSourcingAggregateRoot extends AggregateRoot
{
    abstract protected function apply(DomainEvent $event): void;

    public function raise(DomainEvent $event)
    {
        parent::raise($event);

        $this->apply($event);
    }

    /**
     * @param DomainEvent[] $events
     */
    public function replay(array $events)
    {
        foreach ($events as $event) {
            $this->apply($event);
        }
    }
}
