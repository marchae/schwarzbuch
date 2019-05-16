<?php

declare(strict_types = 1);

namespace App\Common;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
abstract class InMemoryRepository
{
    private $aggregates = [];
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function finde(string $id): ?AggregateRoot
    {
        if (!isset($this->aggregates[$id])) {
            return null;
        }

        return $this->aggregates[$id];
    }

    public function speichern(AggregateRoot $aggregateRoot)
    {
        $this->aggregates[$aggregateRoot->id()] = $aggregateRoot;

        foreach ($aggregateRoot->popRecordedEvents() as $domainEvent) {
            $this->dispatcher->dispatch(get_class($domainEvent), $domainEvent);
        }

    }
}
