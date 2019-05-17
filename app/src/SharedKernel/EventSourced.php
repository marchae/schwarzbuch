<?php

namespace App\SharedKernel;

use RuntimeException;

abstract class EventSourced
{
    /**
     * @var array|DomainEvent[]
     */
    private $domainEvents = [];

    /**
     * @param array|DomainEvent[] $events
     *
     * @return EventSourced
     */
    public static function replay(array $events): self
    {
        $me = static::instanciate();

        foreach ($events as $event) {
            $me->apply($event);
        }

        return $me;
    }

    abstract public static function instanciate(): self;

    private function apply(DomainEvent $event): void
    {
        $method = 'apply' . $event->getClassName();

        if (!method_exists($this, $method)) {
            throw new RuntimeException(sprintf('Missing method "%s"', $method));
        }

        $this->{$method}($event);
    }

    /**
     * @return array|DomainEvent[]
     */
    public function popDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $domainEvents;
    }

    abstract public function getId(): string;

    protected function raise(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;

        $this->apply($event);
    }
}
