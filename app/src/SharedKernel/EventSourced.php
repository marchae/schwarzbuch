<?php

namespace App\SharedKernel;

use RuntimeException;

trait EventSourced
{
    /**
     * @var array|DomainEvent[]
     */
    private $domainEvents = [];

    /**
     * @param array|DomainEvent[] $events
     *
     * @return IEventSourced
     */
    final public static function replay(array $events): IEventSourced
    {
        $me = new static();

        foreach ($events as $event) {
            $me->apply($event);
        }

        return $me;
    }

    private function apply(DomainEvent $event): void
    {
        $method = 'apply' . $event->getShortClassName();

        if (!method_exists($this, $method)) {
            throw new RuntimeException(sprintf('Missing method "%s"', $method));
        }

        $this->{$method}($event);
    }

    /**
     * @return array|DomainEvent[]
     */
    final public function popDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $domainEvents;
    }

    private function raise(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;

        $this->apply($event);
    }
}
