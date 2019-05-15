<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Buch
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

    public function __construct(string $id, string $titel, string $isbn, \DateTimeInterface $kaufDatum)
    {
        $this->id = $id;
        $this->titel = $titel;
        $this->isbn = $isbn;
        $this->kaufDatum = $kaufDatum;
    }

    public static function nehmeBuchInInventarAuf(string $id, string $titel, string $isbn, \DateTimeInterface $kaufDatum): self
    {
        return new self($id, $titel, $isbn, $kaufDatum);
    }

    public function leiheBuchAus(string $studentId, \DateTimeImmutable $rueckgabeTermin): void
    {
        // ist der student gesperrt? diese prüfung gehört woanders hin!!!

        if ($this->istVerliehen()) {
            throw new \DomainException('Buch ist bereits verliehen');
        }

        $ausgabeDatum = new \DateTimeImmutable();
        $this->verleihHistorie[] = VerleihVorgang::beginnen(uniqid(), $this->id, $studentId, $ausgabeDatum, $rueckgabeTermin);
    }

    public function gibBuchZurueck(): void
    {
        if (!$this->istVerliehen()) {
            throw new \DomainException('Ups, Buch ist gar nicht verliehen');
        }

        foreach ($this->verleihHistorie as $verleihVorgang) {
            if ($verleihVorgang->offen()) {
                $verleihVorgang->abschliessen();
            }
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
}
