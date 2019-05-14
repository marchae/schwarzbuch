<?php

declare(strict_types = 1);

namespace App\Entity\Verleih;

use App\Event\Verleih\BuchZumVerkaufFreigegeben;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Buch
{
    private $id;
    private $titel;
    /**
     * @var Ausleihe[]
     */
    private $ausleihVerlauf = [];
    private $zumVerkaufFreigegeben = false;

    private $domainEvents = [];

    private function __construct(string $id, string $titel)
    {
        $this->id = $id;
        $this->titel = $titel;
    }

    public static function zumVerleihFreigeben(string $id, string $titel): self
    {
        return new self($id, $titel);
    }
    
    public function ausleihen(string $nutzerId, \DateTimeInterface $bis): void
    {
        if ($this->zumVerkaufFreigegeben) {
            throw new \DomainException(sprintf('Buch %s steht nicht zum Verleih zur Verfügung', $this->titel()));
        }

        foreach ($this->ausleihVerlauf as $ausleihe) {
            if (!$ausleihe->abgeschlossen()) {
                throw new \DomainException(sprintf('Buch %s wurde noch nicht zurückgegeben', $this->titel()));
            }
        }

        $this->ausleihVerlauf[] = Ausleihe::fuerNutzer($nutzerId, $this->id, $bis);
    }

    public function zurueckgeben(): void
    {
        foreach ($this->ausleihVerlauf as $ausleihe) {
            if (!$ausleihe->abgeschlossen()) {
                $ausleihe->abschliessen();
            }
        }

        if (count($this->ausleihVerlauf) >= 3) {
            $this->buchZumVerkaufFreigeben();
        }
    }

    private function buchZumVerkaufFreigeben()
    {
        $this->zumVerkaufFreigegeben = true;
        $this->raise(new BuchZumVerkaufFreigegeben($this->id(), new \DateTimeImmutable()));
    }

    private function raise($event)
    {
        $this->domainEvents[] = $event;
    }

    public function popDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $domainEvents;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function titel(): string
    {
        return $this->titel;
    }
}
