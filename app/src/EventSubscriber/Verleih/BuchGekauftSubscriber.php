<?php

declare(strict_types = 1);

namespace App\EventSubscriber\Verleih;

use App\Entity\Verleih\Buch;
use App\Event\Einkauf\BuchGekauft;
use App\Repository\Verleih\BuchRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchGekauftSubscriber implements EventSubscriberInterface
{
    /**
     * @var BuchRepository
     */
    private $buchRepository;

    public function __construct(BuchRepository $buchRepository)
    {
        $this->buchRepository = $buchRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BuchGekauft::NAME => 'zumVerleihFreigeben',
        ];
    }

    public function zumVerleihFreigeben(BuchGekauft $buchGekauft)
    {
        $buch = Buch::zumVerleihFreigeben($buchGekauft->id(), $buchGekauft->titel());

        $this->buchRepository->speichern($buch);
    }
}
