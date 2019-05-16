<?php

declare(strict_types = 1);

namespace App\Verleih\EventSubscriber;

use App\Verleih\Event\BuchAusgegeben;
use App\Verleih\Repository\BuchRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class BuchAusgegebenSubscriber implements EventSubscriberInterface
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
            BuchAusgegeben::class => 'wennBuchAusgegeben'
        ];
    }

    public function wennBuchAusgegeben(BuchAusgegeben $buchVerliehen): void
    {
        $buch = $this->buchRepository->finde($buchVerliehen->buchId());
        $buch->ausleihen($buchVerliehen->verleihId());
    }
}
