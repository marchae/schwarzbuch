<?php

declare(strict_types = 1);

namespace App\Verkauf\EventSubscriber;

use App\Verkauf\Entity\Buch;
use App\Verkauf\Repository\BuchRepository;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class BuchZumVerkaufFreigegebenSubscriber implements EventSubscriberInterface
{
    /**
     * @var BuchRepository
     */
    private $buchRepository;

    public function __construct(BuchRepository $buchRepository)
    {
        $this->buchRepository = $buchRepository;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            BuchZumVerkaufFreigegeben::class => 'freigeben'
        ];
    }

    public function freigeben(BuchZumVerkaufFreigegeben $buchZumVerkaufFreigegeben): void
    {
        /** @var Buch $buch */
        $buch = $this->buchRepository->finde($buchZumVerkaufFreigegeben->payload()['buchId']);

        $buch->zumVerkaufFreigeben();

        $this->buchRepository->speichern($buch);
    }
}
