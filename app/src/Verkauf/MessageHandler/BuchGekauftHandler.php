<?php

namespace App\Verkauf\MessageHandler;

use App\Einkauf\Event\BuchGekauft;
use App\SharedKernel\EventStreamRepository;
use App\Verkauf\Entity\Buch;

final class BuchGekauftHandler
{
    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository)
    {
        $this->eventStreamRepository = $eventStreamRepository;
    }

    public function __invoke(BuchGekauft $event): void
    {
        $buch = Buch::nehmeBuchInInventarAuf(
            $event->getBuchId(),
            $event->getIsbn(),
            $event->getTitel(),
            $event->getAutor(),
            $event->getPreis()
        );

        $this->eventStreamRepository->speichern($buch);
    }
}
