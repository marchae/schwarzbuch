<?php

namespace App\Verleih\MessageHandler;

use App\SharedKernel\DispatchDomainEvents;
use App\SharedKernel\EventStreamRepository;
use App\Verleih\Entity\Buch;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;
use App\Verleih\Event\BuchZurueckgegeben;
use Symfony\Component\Messenger\MessageBusInterface;

final class BuchZurueckgegebenHandler
{
    use DispatchDomainEvents;

    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository, MessageBusInterface $domainEventBus)
    {
        $this->eventStreamRepository = $eventStreamRepository;
        $this->setDomainEventBus($domainEventBus);
    }

    public function __invoke(BuchZurueckgegeben $event): void
    {
        /** @var Buch $buch */
        $buch = $this->eventStreamRepository->finde(Buch::class, $event->getBuchId());

        if ($buch->maximaleAusleihvorgaengeErreicht()) {
            $this->dispatchEvents([new BuchZumVerkaufFreigegeben($buch->getId())]);
        }
    }
}
