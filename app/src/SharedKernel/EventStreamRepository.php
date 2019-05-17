<?php

namespace App\SharedKernel;

use Doctrine\DBAL\Connection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Throwable;

class EventStreamRepository
{
    use DispatchDomainEvents;

    private $connection;

    public function __construct(Connection $connection, EventDispatcherInterface $eventDispatcher)
    {
        $this->connection = $connection;
        $this->setEventDispatcher($eventDispatcher);
    }

    public function finde(string $className, string $id): ?EventSourced
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM event_stream WHERE aggregate_root = ? AND aggregate_id = ?'
        );
        $statement->bindValue(1, $className);
        $statement->bindValue(2, $id);
        $statement->execute();

        $events = [];
        foreach ($statement->fetchAll() as $eventEntries) {
            $events[] = $eventEntries['event']::fromPayload(json_decode($eventEntries['payload'], true));
        }

        return $className::replay($events);
    }

    public function speichern(EventSourced $eventSourced): void
    {
        $pendingEvents = $eventSourced->popDomainEvents();

        $this->connection->beginTransaction();

        try {
            foreach ($pendingEvents as $event) {
                $this->connection->insert(
                    'event_stream',
                    [
                        'aggregate_id' => $eventSourced->getId(),
                        'aggregate_root' => get_class($eventSourced),
                        'event' => get_class($event),
                        'payload' => json_encode($event->getPayload()),
                    ]
                );
            }

            $this->connection->commit();
        } catch (Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }

        $this->dispatchEvents($pendingEvents);
    }
}
