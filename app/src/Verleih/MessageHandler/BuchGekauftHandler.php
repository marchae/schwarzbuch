<?php

namespace App\Verleih\MessageHandler;

use App\Einkauf\Event\BuchGekauft;
use App\SharedKernel\EventStreamRepository;
use App\Verleih\Entity\Buch;

final class BuchGekauftHandler
{
    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository)
    {
        $this->eventStreamRepository = $eventStreamRepository;
    }

    public function __invoke(BuchGekauft $event): void
    {
        $buch = Buch::inInventarAufnehmen(
            $event->getBuchId(),
            $event->getTitel(),
            $event->getAutor(),
            $event->getIsbn(),
            $event->getKaufDatum()
        );

        $this->eventStreamRepository->speichern($buch);
    }
}
