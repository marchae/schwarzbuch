<?php

namespace App\SharedKernel;

use Symfony\Component\Messenger\MessageBusInterface;

trait DispatchDomainEvents
{
    /**
     * @var MessageBusInterface
     */
    private $domainEventBus;

    protected function setDomainEventBus(MessageBusInterface $domainEventBus): void
    {
        $this->domainEventBus = $domainEventBus;
    }

    /**
     * @param array|DomainEvent[] $events
     */
    protected function dispatchEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->domainEventBus->dispatch($event);
        }
    }
}
