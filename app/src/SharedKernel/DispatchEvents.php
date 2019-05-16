<?php

namespace App\SharedKernel;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

trait DispatchEvents
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    protected function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param array|Event[] $events
     */
    protected function dispatchEvents(array $events): void
    {
        foreach ($events as $event) {
            $this->eventDispatcher->dispatch(get_class($event), $event);
        }
    }
}
