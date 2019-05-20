<?php

namespace App\Einkauf\Entity;

use App\Einkauf\Event\BuchGekauft;
use App\SharedKernel\EventSourced;
use App\SharedKernel\IEventSourced;
use DateTimeImmutable;

final class Buch implements IEventSourced
{
    use EventSourced;

    private $id;
    private $isbn;
    private $titel;
    private $autor;
    private $preis;
    private $kaufDatum;

    private function __construct()
    {
    }

    public static function kaufeBuch(string $id, string $isbn, string $titel, string $autor, int $preis): Buch
    {
        $kaufDatum = new DateTimeImmutable();

        $buch = new self();
        $buch->raise(new BuchGekauft($id, $isbn, $titel, $autor, $preis, $kaufDatum));

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

    private function applyBuchGekauft(BuchGekauft $event): void
    {
        $this->id = $event->getBuchId();
        $this->isbn = $event->getIsbn();
        $this->titel = $event->getTitel();
        $this->autor = $event->getAutor();
        $this->preis = $event->getPreis();
        $this->kaufDatum = $event->getKaufDatum();
    }
}
