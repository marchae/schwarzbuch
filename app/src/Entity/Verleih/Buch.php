<?php

declare(strict_types = 1);

namespace App\Entity\Verleih;

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

    public function __construct(string $id, string $titel)
    {
        $this->id = $id;
        $this->titel = $titel;
    }

    public function ausleihen(string $nutzerId, \DateTimeInterface $bis): void
    {
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
