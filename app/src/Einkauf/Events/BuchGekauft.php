<?php

namespace App\Einkauf\Events;

use App\Einkauf\Entity\Buch;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\Event;

final class BuchGekauft extends Event
{
    private $id;
    private $isbn;
    private $titel;
    private $autor;
    private $kaufDatum;
    private $preis;

    public function __construct(Buch $buch)
    {
        $this->id = $buch->getId();
        $this->isbn = $buch->getIsbn();
        $this->titel = $buch->getTitel();
        $this->autor = $buch->getAutor();
        $this->kaufDatum = $buch->getKaufDatum();
        $this->preis = $buch->getPreis();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    public function getAutor(): string
    {
        return $this->autor;
    }

    public function getKaufDatum(): DateTimeImmutable
    {
        return $this->kaufDatum;
    }

    public function getPreis(): int
    {
        return $this->preis;
    }
}
