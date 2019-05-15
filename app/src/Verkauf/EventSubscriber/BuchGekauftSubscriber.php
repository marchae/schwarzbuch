<?php

declare(strict_types = 1);

namespace App\Verkauf\EventSubscriber;

use App\Einkauf\Event\BuchGekauft;
use App\Verkauf\Entity\Buch;
use App\Verkauf\Repository\BuchRepository;
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
        $buch = Buch::nehmeBuchInInventarAuf($buchGekauft->buchId(), $buchGekauft->titel(), $buchGekauft->isbn(), $buchGekauft->preis());

        $this->buchRepository->speichern($buch);
    }
}
