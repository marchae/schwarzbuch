<?php

namespace App\Einkauf\Entity;

use App\Einkauf\Events\BuchGekauft;
use App\SharedKernel\RaiseDomainEvents;
use DateTimeImmutable;

final class Buch
{
    use RaiseDomainEvents;

    private $id;
    private $isbn;
    private $titel;
    private $autor;
    private $preis;
    private $kaufDatum;

    private function __construct(string $id, string $isbn, string $titel, string $autor, int $preis)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->autor = $autor;
        $this->preis = $preis;
        $this->kaufDatum = new DateTimeImmutable();
    }

    public static function kaufeBuch(string $id, string $isbn, string $titel, string $autor, int $preis): Buch
    {
        $buch = new self($id, $isbn, $titel, $autor, $preis);

        $buch->domainEvents[] = new BuchGekauft($buch);

        return $buch;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getPreis(): int
    {
        return $this->preis;
    }

    public function getKaufDatum(): DateTimeImmutable
    {
        return $this->kaufDatum;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'titel' => $this->titel,
            'autor' => $this->autor,
            'kaufDatum' => $this->kaufDatum->format('d.m.Y'),
            'preis' => sprintf('%s €', number_format($this->preis / 100, 2, ',', '.')),
        ];
    }
}
