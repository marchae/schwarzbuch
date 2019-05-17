<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

use App\Common\DomainEvent;
use App\Common\EventSourcingAggregateRoot;
use App\Verleih\Event\BuchAusgeliehen;
use App\Verleih\Event\BuchInInventarAufgenommen;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Buch extends EventSourcingAggregateRoot
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $titel;
    /**
     * @var string
     */
    private $isbn;
    /**
     * @var \DateTimeInterface
     */
    private $kaufDatum;
    /**
     * @var VerleihVorgang[]
     */
    private $verleihHistorie = [];

    private function __construct()
    {
    }

    public static function inInventarAufnehmen(string $id, string $titel, string $isbn, \DateTimeInterface $kaufDatum): self
    {
        $buch = new self();

        $buch->raise(
            new BuchInInventarAufgenommen(['id' => $id, 'titel' => $titel, 'isbn' => $isbn, 'kaufDatum' => $kaufDatum])
        );

        return $buch;
    }

    public function ausleihen(string $studentId, \DateTimeImmutable $rueckgabeTermin): void
    {
        // Ist der Student gesperrt? Nutze den Student-AR, um ihn entsprechend zu prüfen.

        if ($this->istVerliehen()) {
            throw new \DomainException('Buch ist bereits verliehen');
        }

        if ($this->ausleihLimitErreicht()) {
            throw new \DomainException('Buch steht dem Verleih nicht mehr zur Verfügung');
        }

        $this->raise(
            new BuchAusgeliehen(
                [
                    'buchId' => $this->id(),
                    'studentId' => $studentId,
                    'ausgabeDatum' => new \DateTimeImmutable(),
                    'rueckgabeDatum' => $rueckgabeTermin,
                ]
            )
        );
    }

    public function zurueckgeben(): void
    {
        if (!$this->istVerliehen()) {
            throw new \DomainException('Ups, Buch ist gar nicht verliehen');
        }

        foreach ($this->verleihHistorie as $verleihVorgang) {
            if ($verleihVorgang->offen()) {
                $verleihVorgang->abschliessen();
            }
        }

        // @todo wie könnten wir das anders lösen?
        if ($this->ausleihLimitErreicht()) {
            $this->gebeBuchZumVerkaufFrei();
        }
    }

    public function ausleihLimitErreicht(): bool
    {
        return count($this->verleihHistorie) >= 3;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function titel(): string
    {
        return $this->titel;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function kaufDatum(): \DateTimeInterface
    {
        return $this->kaufDatum;
    }

    protected function apply(DomainEvent $event): void
    {
        switch (true) {
            case $event instanceof BuchAusgeliehen:
                $this->verleihHistorie[] = VerleihVorgang::beginnen(
                    uniqid(),
                    $event->payload()['buchId'],
                    $event->payload()['studentId'],
                    $event->payload()['ausgabeDatum'],
                    $event->payload()['rueckgabeTermin']
                );
                break;

            case $event instanceof BuchInInventarAufgenommen:
                $this->id = $event->payload()['id'];
                $this->titel = $event->payload()['titel'];
                $this->isbn = $event->payload()['isbn'];
                $this->kaufDatum = $event->payload()['kaufDatum'];
                break;
        }
    }

    private function istVerliehen(): bool
    {
        foreach ($this->verleihHistorie as $verleihVorgang) {
            if ($verleihVorgang->offen()) {
                return true;
            }
        }

        return false;
    }

    private function gebeBuchZumVerkaufFrei(): void
    {
        $this->raise(new BuchZumVerkaufFreigegeben(['id' => $this->id()]));
    }
}
