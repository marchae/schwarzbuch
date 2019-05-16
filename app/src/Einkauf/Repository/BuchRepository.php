<?php

namespace App\Einkauf\Repository;

use App\Einkauf\Entity\Buch;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class BuchRepository
{
    private $buecher;
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->buecher = [];
        $this->eventDispatcher = $eventDispatcher;
    }

    public function speichern(Buch $buch): void
    {
        $this->buecher[$buch->getId()] = $buch;

        $domainEvents = $buch->popDomainEvents();

        foreach ($domainEvents as $domainEvent) {
            $this->eventDispatcher->dispatch(get_class($domainEvent), $domainEvent);
        }
    }

    public function finde(string $id): ?Buch
    {
        return $this->buecher[$id] ?? null;
    }
}
