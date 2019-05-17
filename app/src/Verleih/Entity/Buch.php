<?php

namespace App\Verleih\Entity;

use App\SharedKernel\EventSourced;
use App\Verleih\Event\BuchAusgeliehen;
use App\Verleih\Event\BuchInventarisiert;
use App\Verleih\Event\BuchZurueckgegeben;
use DateTimeImmutable;
use DomainException;

final class Buch extends EventSourced
{
    private const MAXIMALE_VERLEIH_VORGAENGE_BUCH = 3;

    private $id;
    private $isbn;
    private $titel;
    private $autor;
    /**
     * @var DateTimeImmutable
     */
    private $kaufDatum;
    /**
     * @var array|VerleihVorgang[]
     */
    private $verleihVorgaenge = [];

    private function __construct()
    {
    }

    public static function inInventarAufnehmen(string $id, string $titel, string $autor, string $isbn, DateTimeImmutable $kaufDatum): Buch
    {
        $buch = new self();
        $buch->raise(new BuchInventarisiert($id, $isbn, $titel, $autor, $kaufDatum));

        return $buch;
    }

    public static function instanciate(): EventSourced
    {
        return new self();
    }

    public function ausleihen(Student $student, DateTimeImmutable $rueckgabeTermin): void
    {
        if ($this->istAusgeliehen()) {
            throw new DomainException('Buch bereits ausgeliehen');
        }

        if ($student->istGesperrt()) {
            throw new DomainException('Student ist für den Verleih gesperrt');
        }

        $this->raise(new BuchAusgeliehen($this->getId(), $student->getId(), new DateTimeImmutable(), $rueckgabeTermin));
    }

    public function istAusgeliehen(): bool
    {
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($verleihVorgang->istOffen() && $verleihVorgang->getBuchId() === $this->getId()) {
                return true;
            }
        }

        return false;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function zurueckgeben(): void
    {
        if (!$this->istAusgeliehen()) {
            throw new DomainException('Buch ist nicht ausgeliehen');
        }

        $this->raise(new BuchZurueckgegeben($this->getId()));
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    public function getKaufDatum(): DateTimeImmutable
    {
        return $this->kaufDatum;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'titel' => $this->titel,
            'autor' => $this->autor,
            'kaufDatum' => $this->kaufDatum->format('d.m.Y'),
            'verleihVorgaenge' => array_map(
                static function (VerleihVorgang $verleihVorgang) {
                    return $verleihVorgang->toArray();
                },
                $this->verleihVorgaenge
            ),
        ];
    }

    public function maximaleAusleihvorgaengeErreicht(): bool
    {
        return count($this->verleihVorgaenge) >= self::MAXIMALE_VERLEIH_VORGAENGE_BUCH;
    }

    protected function applyBuchInventarisiert(BuchInventarisiert $event): void
    {
        $this->id = $event->getBuchId();
        $this->isbn = $event->getIsbn();
        $this->titel = $event->getTitel();
        $this->autor = $event->getAutor();
        $this->kaufDatum = $event->getKaufDatum();
    }

    protected function applyBuchAusgeliehen(BuchAusgeliehen $event): void
    {
        $verleihVorgang = VerleihVorgang::beginnen(
            uniqid('', true),
            $event->getBuchId(),
            $event->getStudentId(),
            $event->getAusleihDatum(),
            $event->getRueckgabeTermin()
        );

        $this->verleihVorgaenge[] = $verleihVorgang;
    }

    protected function applyBuchZurueckgegeben(BuchZurueckgegeben $event): void
    {
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($verleihVorgang->istOffen() && $event->getBuchId() === $verleihVorgang->getBuchId()) {
                $verleihVorgang->abschliessen();
            }
        }
    }
}
