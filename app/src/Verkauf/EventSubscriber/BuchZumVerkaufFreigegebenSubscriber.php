<?php

namespace App\Verkauf\EventSubscriber;

use App\SharedKernel\EventStreamRepository;
use App\Verkauf\Entity\Buch;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class BuchZumVerkaufFreigegebenSubscriber implements EventSubscriberInterface
{

    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository)
    {
        $this->eventStreamRepository = $eventStreamRepository;
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
            BuchZumVerkaufFreigegeben::class => 'buchZumVerkaufFreigeben',
        ];
    }

    public function buchZumVerkaufFreigeben(BuchZumVerkaufFreigegeben $event): void
    {
        /** @var Buch $buch */
        $buch = $this->eventStreamRepository->finde(Buch::class, $event->getBuchId());

        $buch->zumVerkaufFreigeben();

        $this->eventStreamRepository->speichern($buch);
    }
}
