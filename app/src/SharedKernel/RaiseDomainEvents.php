<?php

namespace App\SharedKernel;

trait RaiseDomainEvents
{
    /**
     * @var array|DomainEvent[]
     */
    private $domainEvents = [];

    /**
     * @return array|DomainEvent[]
     */
    public function popDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $domainEvents;
    }

    protected function raise(DomainEvent $event): void
    {
        $this->domainEvents[] = $event;
    }
}
