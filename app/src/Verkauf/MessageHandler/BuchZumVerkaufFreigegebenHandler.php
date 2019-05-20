<?php

namespace App\Verkauf\MessageHandler;

use App\SharedKernel\EventStreamRepository;
use App\Verkauf\Entity\Buch;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;

final class BuchZumVerkaufFreigegebenHandler
{
    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository)
    {
        $this->eventStreamRepository = $eventStreamRepository;
    }

    public function __invoke(BuchZumVerkaufFreigegeben $event): void
    {
        /** @var Buch $buch */
        $buch = $this->eventStreamRepository->finde(Buch::class, $event->getBuchId());

        $buch->zumVerkaufFreigeben();

        $this->eventStreamRepository->speichern($buch);
    }
}
