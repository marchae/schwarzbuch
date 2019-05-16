<?php

namespace App\Verkauf\EventSubscriber;

use App\Einkauf\Events\BuchGekauft;
use App\Verkauf\Entity\Buch;
use App\Verkauf\Repository\BuchRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BuchGekauftSubscriber implements EventSubscriberInterface
{
    private $buchRepository;

    public function __construct(BuchRepository $buchRepository)
    {
        $this->buchRepository = $buchRepository;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BuchGekauft::class => 'buchInInventarAufnehmen',
        ];
    }

    public function buchInInventarAufnehmen(BuchGekauft $event): void
    {
        $buch = Buch::nehmeBuchInInventarAuf(
            $event->getId(),
            $event->getTitel(),
            $event->getIsbn(),
            $event->getPreis()
        );

        $this->buchRepository->speichern($buch);
    }
}
