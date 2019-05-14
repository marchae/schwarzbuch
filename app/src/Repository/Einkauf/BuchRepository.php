<?php

declare(strict_types = 1);

namespace App\Repository\Einkauf;

use App\Entity\Einkauf\Buch;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchRepository
{
    private $buecher = [];
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function findeById(string $id): ?Buch
    {
        if (!isset($this->buecher[$id])) {
            return null;
        }

        return $this->buecher[$id];
    }

    public function speichern(Buch $buch): void
    {
        $pendingDomainEvents = $buch->popDomainEvents();

        $this->buecher[$buch->id()] = $buch;

        foreach ($pendingDomainEvents as $domainEvent) {
            $this->dispatcher->dispatch($domainEvent::NAME, $domainEvent);
        }
    }
}
