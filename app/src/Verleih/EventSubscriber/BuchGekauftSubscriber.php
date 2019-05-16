<?php

namespace App\Verleih\EventSubscriber;

use App\Einkauf\Events\BuchGekauft;
use App\Verleih\Repository\VerleihRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class BuchGekauftSubscriber implements EventSubscriberInterface
{
    private $verleihRepository;

    public function __construct(VerleihRepository $verleihRepository)
    {
        $this->verleihRepository = $verleihRepository;
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
        $verleih = $this->verleihRepository->finde();

        $verleih->inventarisiereBuch(
            $event->getId(),
            $event->getTitel(),
            $event->getAutor(),
            $event->getIsbn(),
            $event->getKaufDatum()
        );

        $this->verleihRepository->speichern($verleih);
    }
}
