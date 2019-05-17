<?php

declare(strict_types = 1);

namespace App\Verleih\EventSubscriber;

use App\Einkauf\Event\BuchGekauft;
use App\Verleih\Entity\Buch;
use App\Verleih\Repository\BuchRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class BuchGekauftSubscriber implements EventSubscriberInterface
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
            BuchGekauft::class => 'inInventarAufnehmen'
        ];
    }

    public function inInventarAufnehmen(BuchGekauft $buchGekauft): void
    {
        $buch = Buch::inInventarAufnehmen($buchGekauft->payload()['buchId'], $buchGekauft->payload()['titel'], $buchGekauft->payload()['isbn'], $buchGekauft->payload()['kaufDatum']);

        $this->buchRepository->speichern($buch);
    }
}
