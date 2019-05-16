<?php

namespace App\Verleih\Entity;

use App\SharedKernel\ProvideDomainEvents;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;
use DateTimeImmutable;
use DomainException;

final class Verleih
{
    private const MAXIMALE_VERLEIH_VORGAENGE_STUDENT = 3;
    private const MAXIMALE_VERLEIH_VORGAENGE_BUCH = 3;

    use ProvideDomainEvents;

    private $id;
    /**
     * @var array|Buch[]
     */
    private $buecher;
    /**
     * @var array|Student[]
     */
    private $studenten;
    /**
     * @var array|VerleihVorgang[]
     */
    private $verleihVorgaenge;

    private function __construct(string $id, array $buecher, array $studenten, array $verleihVorgaenge)
    {
        $this->id = $id;
        $this->buecher = $buecher;
        $this->studenten = $studenten;
        $this->verleihVorgaenge = $verleihVorgaenge;
    }

    public static function eroeffnen(string $id, array $buecher, array $studenten, array $verleihVorgaenge): self
    {
        return new self($id, $buecher, $studenten, $verleihVorgaenge);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBuecher(): array
    {
        return $this->buecher;
    }

    public function getStudenten(): array
    {
        return $this->studenten;
    }

    public function getVerleihVorgaenge(): array
    {
        return $this->verleihVorgaenge;
    }

    public function inventarisiereBuch(string $buchId, string $titel, string $autor, string $isbn, DateTimeImmutable $kaufDatum): void
    {
        $this->buecher[$buchId] = Buch::inInventarAufnehmen($buchId, $titel, $autor, $isbn, $kaufDatum);
    }

    public function buchAusleihen(string $buchId, string $studentId, DateTimeImmutable $rueckgabeTermin): void
    {
        $buch = $this->getBuchOrRaiseException($buchId);

        $student = $this->getStudentOrRaiseException($studentId);

        if ($this->istBuchAusgeliehen($buch->getId())) {
            throw new DomainException('Buch bereits ausgeliehen');
        }

        if ($this->maximaleVerleihVorgaengeFuerStudentErreicht($student->getId())) {
            throw new DomainException(
                sprintf('Student hat bereits %s Bücher ausgeliehen', self::MAXIMALE_VERLEIH_VORGAENGE_STUDENT)
            );
        }

        if ($student->istGesperrt()) {
            throw new DomainException('Student ist für den Verleih gesperrt');
        }

        $ausgabeDatum = new DateTimeImmutable();

        $verleihVorgang = VerleihVorgang::beginnen(
            uniqid('', true),
            $buch->getId(),
            $studentId,
            $ausgabeDatum,
            $rueckgabeTermin
        );

        $this->verleihVorgaenge[] = $verleihVorgang;
    }

    private function getBuchOrRaiseException(string $buchId): Buch
    {
        if (!isset($this->buecher[$buchId])) {
            throw new DomainException('Buch nicht im System');
        }

        return $this->buecher[$buchId];
    }

    private function getStudentOrRaiseException(string $studentId): Student
    {
        if (!isset($this->studenten[$studentId])) {
            throw new DomainException('Student ist nicht im System');
        }

        return $this->studenten[$studentId];
    }

    public function istBuchAusgeliehen(string $buchId): bool
    {
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            // @Todo: Zugriff via getter ? Design falsch ?
            if ($verleihVorgang->getBuchId() === $buchId && $verleihVorgang->istOffen()) {
                return true;
            }
        }

        return false;
    }

    private function maximaleVerleihVorgaengeFuerStudentErreicht(string $studentId): bool
    {
        $offeneVerleihVorgaenge = [];

        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            // @Todo: Zugriff via getter ? Design falsch ?
            if ($verleihVorgang->getStudentId() === $studentId && $verleihVorgang->isOffen()) {
                $offeneVerleihVorgaenge[] = $verleihVorgang;
            }
        }

        return count($offeneVerleihVorgaenge) === self::MAXIMALE_VERLEIH_VORGAENGE_STUDENT;
    }

    public function buchZurueckgeben(string $buchId, string $studentId): void
    {
        $verleihVorgang = $this->getVerleihVorgangFuerBuchOrRaiseException($buchId);

        if ($verleihVorgang->getStudentId() !== $studentId) {
            throw new DomainException('Buch nicht vom selben Student zurueckgegeben');
        }

        $verleihVorgang->abschliessen();

        if ($this->maximaleVerleihVorgaengeFuerBuchErreicht($buchId)) {
            $buch = $this->getBuchOrRaiseException($buchId);

            $this->buchFuerVerkaufFreigeben($buch);
        }
    }

    private function getVerleihVorgangFuerBuchOrRaiseException(string $buchId): VerleihVorgang
    {
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($buchId === $verleihVorgang->getBuchId() && $verleihVorgang->istOffen()) {
                return $verleihVorgang;
            }
        }

        throw new DomainException('Kein Verleihvorgang für Buch gefunden');
    }

    private function maximaleVerleihVorgaengeFuerBuchErreicht(string $buchId): bool
    {
        $verleihVorgaenge = [];

        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($buchId === $verleihVorgang->getBuchId()) {
                $verleihVorgaenge[] = $verleihVorgang;
            }
        }

        return count($verleihVorgaenge) >= self::MAXIMALE_VERLEIH_VORGAENGE_BUCH;
    }

    private function buchFuerVerkaufFreigeben(Buch $buch): void
    {
        unset($this->buecher[$buch->getId()]);

        $this->addDomainEvent(new BuchZumVerkaufFreigegeben($buch));
    }

    public function toArray(): array
    {
        return [
            'buecher' => $this->objectsToArray($this->buecher),
            'studenten' => $this->objectsToArray($this->studenten),
            'verleihVorgaenge' => $this->objectsToArray($this->verleihVorgaenge),
        ];
    }

    private function objectsToArray(array $objects): array
    {
        return array_map(
            static function ($object) {
                return $object->toArray();
            },
            $objects
        );
    }
}
