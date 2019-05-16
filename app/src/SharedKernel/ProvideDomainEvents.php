<?php

namespace App\SharedKernel;

use Symfony\Component\EventDispatcher\Event;

trait ProvideDomainEvents
{
    /**
     * @var array|Event[]
     */
    private $domainEvents = [];

    /**
     * @return array|Event[]
     */
    public function popDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $domainEvents;
    }

    protected function addDomainEvent(Event $event): void
    {
        $this->domainEvents[] = $event;
    }
}
