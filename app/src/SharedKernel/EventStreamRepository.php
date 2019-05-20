<?php

namespace App\SharedKernel;

use Doctrine\DBAL\Connection;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

class EventStreamRepository
{
    use DispatchDomainEvents;

    private $connection;

    public function __construct(Connection $connection, MessageBusInterface $domainEventBus)
    {
        $this->connection = $connection;
        $this->setDomainEventBus($domainEventBus);
    }

    public function finde(string $className, string $id): ?IEventSourced
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

        if (count($events) === 0) {
            return null;
        }

        return $className::replay($events);
    }

    public function speichern(IEventSourced $eventSourced): void
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

    /**
     * @return array|DomainEvent[]
     */
    public function getAll(): array
    {
        $statement = $this->connection->prepare('SELECT * FROM event_stream ORDER BY occurence ASC;');
        $statement->execute();

        $events = [];
        foreach ($statement->fetchAll() as $eventEntries) {
            $events[] = $eventEntries['event']::fromPayload(json_decode($eventEntries['payload'], true));
        }

        return $events;
    }
}
