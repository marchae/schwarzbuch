<?php

namespace App\Verleih\Entity;

use App\SharedKernel\EventSourced;
use App\SharedKernel\IEventSourced;
use App\Verleih\Event\BuchAusgeliehen;
use App\Verleih\Event\BuchInventarisiert;
use App\Verleih\Event\BuchZurueckgegeben;
use DateTimeImmutable;
use DomainException;

final class Buch implements IEventSourced
{
    use EventSourced;

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

    public function ausleihen(Student $student, DateTimeImmutable $rueckgabeTermin): void
    {
        if ($this->istAusgeliehen()) {
            throw new DomainException('Buch bereits ausgeliehen');
        }

        if ($student->istGesperrt()) {
            throw new DomainException('Student ist für den Verleih gesperrt');
        }

        $this->raise(
            new BuchAusgeliehen(
                uniqid('', true),
                $this->getId(),
                $student->getId(),
                new DateTimeImmutable(),
                $rueckgabeTermin
            )
        );
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

        $offenerVerleihVorgang = null;
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($verleihVorgang->istOffen() && $verleihVorgang->getBuchId() === $this->getId()) {
                $offenerVerleihVorgang = $verleihVorgang;
            }
        }

        $this->raise(new BuchZurueckgegeben($offenerVerleihVorgang->getId(), $this->getId(), new DateTimeImmutable()));
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

    private function applyBuchInventarisiert(BuchInventarisiert $event): void
    {
        $this->id = $event->getBuchId();
        $this->isbn = $event->getIsbn();
        $this->titel = $event->getTitel();
        $this->autor = $event->getAutor();
        $this->kaufDatum = $event->getKaufDatum();
    }

    private function applyBuchAusgeliehen(BuchAusgeliehen $event): void
    {
        $verleihVorgang = VerleihVorgang::beginnen(
            $event->getVerleihVorgangId(),
            $event->getBuchId(),
            $event->getStudentId(),
            $event->getAusleihDatum(),
            $event->getRueckgabeTermin()
        );

        $this->verleihVorgaenge[] = $verleihVorgang;
    }

    private function applyBuchZurueckgegeben(BuchZurueckgegeben $event): void
    {
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($verleihVorgang->istOffen() && $event->getBuchId() === $verleihVorgang->getBuchId()) {
                $verleihVorgang->abschliessen();
            }
        }
    }
}
