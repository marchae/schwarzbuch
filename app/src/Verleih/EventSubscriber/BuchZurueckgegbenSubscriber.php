<?php

namespace App\Verleih\EventSubscriber;

use App\SharedKernel\DispatchDomainEvents;
use App\SharedKernel\EventStreamRepository;
use App\Verleih\Entity\Buch;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;
use App\Verleih\Event\BuchZurueckgegeben;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class BuchZurueckgegbenSubscriber implements EventSubscriberInterface
{
    use DispatchDomainEvents;

    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->eventStreamRepository = $eventStreamRepository;
        $this->setEventDispatcher($eventDispatcher);
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
            BuchZurueckgegeben::class => 'buchFuerVerkaufFreigeben',
        ];
    }

    public function buchFuerVerkaufFreigeben(BuchZurueckgegeben $event): void
    {
        /** @var Buch $buch */
        $buch = $this->eventStreamRepository->finde(Buch::class, $event->getBuchId());

        if ($buch->maximaleAusleihvorgaengeErreicht()) {
            $this->dispatchEvents([new BuchZumVerkaufFreigegeben($buch)]);
        }
    }
}
