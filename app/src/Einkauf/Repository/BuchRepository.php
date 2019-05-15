<?php

declare(strict_types = 1);

namespace App\Einkauf\Repository;

use App\Einkauf\Entity\Buch;
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

    public function finde(string $id): ?Buch
    {
        if (!isset($this->buecher[$id])) {
            return null;
        }

        return $this->buecher[$id];
    }

    public function speichern(Buch $buch): void
    {
        $this->buecher[$buch->id()] = $buch;

        foreach ($buch->popDomainEvents() as $domainEvent) {
            $this->dispatcher->dispatch(get_class($domainEvent), $domainEvent);
        }
    }
}
