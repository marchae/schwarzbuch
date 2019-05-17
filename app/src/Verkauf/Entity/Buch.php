<?php

namespace App\Verkauf\Entity;

use App\SharedKernel\EventSourced;
use App\Verkauf\Event\BuchInInventarAufgenommen;
use App\Verkauf\Event\BuchZumVerkaufFreigegeben;
use DomainException;

final class Buch extends EventSourced
{
    private const STATUS_VORGEMERKT = 'vorgemerkt';
    private const STATUS_FREIGEGEBEN = 'freigegeben';
    private const STATUS_VERKAUFT = 'verkauft';

    private $id;
    private $isbn;
    private $titel;
    private $autor;
    private $verkaufsPreis;
    private $status;

    private function __construct()
    {
    }

    public static function nehmeBuchInInventarAuf(string $buchId, string $isbn, string $titel, string $autor, int $einkaufsPreis): Buch
    {
        $verkaufsPreis = $einkaufsPreis * 0.9;

        $buch = new self();
        $buch->raise(new BuchInInventarAufgenommen($buchId, $isbn, $titel, $autor, $verkaufsPreis));

        return $buch;
    }

    public static function instanciate(): EventSourced
    {
        return new self();
    }

    public function getTitel(): string
    {
        return $this->titel;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function getVerkaufsPreis(): int
    {
        return $this->verkaufsPreis;
    }

    public function isFreigegeben(): bool
    {
        return $this->status === self::STATUS_FREIGEGEBEN;
    }

    public function zumVerkaufFreigeben(): void
    {
        if ($this->status !== self::STATUS_VORGEMERKT) {
            throw new DomainException('Buch ist nicht vorgemerkt');
        }

        $this->raise(new BuchZumVerkaufFreigegeben($this->getId()));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'titel' => $this->titel,
            'autor' => $this->autor,
            'verkaufsPreis' => sprintf('%s €', number_format($this->verkaufsPreis / 100, 2, ',', '.')),
            'status' => $this->status,
        ];
    }

    protected function applyBuchInInventarAufgenommen(BuchInInventarAufgenommen $event): void
    {
        $this->id = $event->getBuchId();
        $this->isbn = $event->getIsbn();
        $this->titel = $event->getTitel();
        $this->autor = $event->getAutor();
        $this->verkaufsPreis = $event->getVerkaufsPreis();
        $this->status = self::STATUS_VORGEMERKT;
    }

    protected function applyBuchZumVerkaufFreigegeben(BuchZumVerkaufFreigegeben $event): void
    {
        $this->status = self::STATUS_FREIGEGEBEN;
    }
}
