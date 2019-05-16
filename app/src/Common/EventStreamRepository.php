<?php

declare(strict_types = 1);

namespace App\Common;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
abstract class EventStreamRepository
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    abstract protected function getAggregateRootClassName(): string;

    public function __construct(Connection $connection, EventDispatcherInterface $dispatcher)
    {
        $this->connection = $connection;
        $this->dispatcher = $dispatcher;
    }

    public function finde(string $id): ?EventSourcingAggregateRoot
    {
        $aggregateRootClassName = $this->getAggregateRootClassName();

        $statement = $this->connection->prepare(
            'SELECT * FROM event_stream WHERE aggregate_root = ? AND aggregate_id = ?'
        );
        $statement->bindValue(1, $aggregateRootClassName);
        $statement->bindValue(2, $id);
        $statement->execute();

        $events = [];
        foreach ($statement->fetchAll() as $eventEntries) {
            $events[] = $eventEntries['event']::fromPayload(json_decode($eventEntries['payload'], true));
        }

        $aggregateRoot = $aggregateRootClassName::replay($events);

        return $aggregateRoot;
    }

    public function speichern(EventSourcingAggregateRoot $aggregateRoot)
    {
        $pendingEvents = $aggregateRoot->popRecordedEvents();

        $this->connection->beginTransaction();

        try {
            foreach ($pendingEvents as $event) {
                $this->connection->insert(
                    'event_stream',
                    [
                        'aggregate_id' => $aggregateRoot->id(),
                        'aggregate_root' => get_class($aggregateRoot),
                        'event' => get_class($event),
                        'payload' => json_encode($event->payload()),
                    ]
                );
            }

            $this->connection->commit();
        } catch (DBALException $e) {
            $this->connection->rollBack();

            throw new \Exception('no good');
        }

        foreach ($pendingEvents as $domainEvent) {
            $this->dispatcher->dispatch(get_class($domainEvent), $domainEvent);
        }
    }
}
