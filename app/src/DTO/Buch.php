<?php

namespace App\DTO;

use DateTimeImmutable;

final class Buch
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $isbn;
    /**
     * @var string
     */
    private $titel;
    /**
     * @var string
     */
    private $autor;
    /**
     * @var int
     */
    private $kaufPreis;
    /**
     * @var DateTimeImmutable
     */
    private $kaufDatum;
    /**
     * @var int
     */
    private $verkaufsPreis;
    /**
     * @var bool
     */
    private $zumVerkaufFreigegeben;
    /**
     * @var VerleihVorgang[]
     */
    private $verleihVorgaenge;

    public function __construct(string $id, string $isbn, string $titel, string $autor, int $kaufPreis, DateTimeImmutable $kaufDatum)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->autor = $autor;
        $this->kaufPreis = $kaufPreis;
        $this->kaufDatum = $kaufDatum;
        $this->verkaufsPreis = 0;
        $this->zumVerkaufFreigegeben = false;
        $this->verleihVorgaenge = [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Buch
    {
        $this->id = $id;

        return $this;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): Buch
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    public function setTitel(string $titel): Buch
    {
        $this->titel = $titel;

        return $this;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function setAutor(string $autor): Buch
    {
        $this->autor = $autor;

        return $this;
    }

    public function getKaufPreis(): int
    {
        return $this->kaufPreis;
    }

    public function setKaufPreis(int $kaufPreis): Buch
    {
        $this->kaufPreis = $kaufPreis;

        return $this;
    }

    public function getKaufDatum(): DateTimeImmutable
    {
        return $this->kaufDatum;
    }

    public function setKaufDatum(DateTimeImmutable $kaufDatum): Buch
    {
        $this->kaufDatum = $kaufDatum;

        return $this;
    }

    public function getVerkaufsPreis(): int
    {
        return $this->verkaufsPreis;
    }

    public function setVerkaufsPreis(int $verkaufsPreis): Buch
    {
        $this->verkaufsPreis = $verkaufsPreis;

        return $this;
    }

    public function getZumVerkaufFreigegeben(): bool
    {
        return $this->zumVerkaufFreigegeben;
    }

    public function setZumVerkaufFreigegeben(bool $zumVerkaufFreigegeben): Buch
    {
        $this->zumVerkaufFreigegeben = $zumVerkaufFreigegeben;

        return $this;
    }

    public function getVerleihVorgaenge(): array
    {
        return $this->verleihVorgaenge;
    }

    /**
     * @param VerleihVorgang[] $verleihVorgaenge
     *
     * @return Buch
     */
    public function setVerleihVorgaenge(array $verleihVorgaenge): Buch
    {
        $this->verleihVorgaenge = $verleihVorgaenge;

        return $this;
    }

    public function addVerleihVorgang(VerleihVorgang $verleihVorgang): void
    {
        $this->verleihVorgaenge[] = $verleihVorgang;
    }

    public function getVerleihVorgang(string $id): ?VerleihVorgang
    {
        foreach ($this->verleihVorgaenge as $verleihVorgang) {
            if ($verleihVorgang->getId() === $id) {
                return $verleihVorgang;
            }
        }

        return null;
    }
}
