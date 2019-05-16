<?php

namespace App\Verleih\Entity;

use DateTimeImmutable;

final class Buch
{
    private $id;
    private $isbn;
    private $titel;
    private $autor;
    private $kaufDatum;

    private function __construct(string $id, string $titel, string $autor, string $isbn, DateTimeImmutable $kaufDatum)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->autor = $autor;
        $this->kaufDatum = $kaufDatum;
    }

    public static function inInventarAufnehmen(string $id, string $titel, string $autor, string $isbn, DateTimeImmutable $kaufDatum): Buch
    {
        return new self($id, $titel, $autor, $isbn, $kaufDatum);
    }

    public function getId(): string
    {
        return $this->id;
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
        ];
    }
}
