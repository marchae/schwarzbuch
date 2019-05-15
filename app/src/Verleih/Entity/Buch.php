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
     * @var array
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

    public function leiheBuchAus(string $buchId, string $studentId, \DateTimeImmutable $rueckgabeTermin): void
    {
        // validieren ob möglich

        $ausgabeDatum = new \DateTimeImmutable();
        $this->verleihHistorie[] = VerleihVorgang::beginnen(uniqid(), $buchId, $studentId, $ausgabeDatum, $rueckgabeTermin);
    }
}
