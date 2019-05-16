<?php

declare(strict_types = 1);

namespace App\Infrastructure;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class AggregateRoot
{
    /**
     * @var Event[]
     */
    private $domainEvents = [];

    public function raise(Event $event)
    {
        $this->domainEvents[] = $event;
    }

    /**
     * @return Event[]
     */
    public function popRecordedEvents(): array
    {
        $pendingEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $pendingEvents;
    }
}
