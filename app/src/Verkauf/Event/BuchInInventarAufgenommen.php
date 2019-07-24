<?php

namespace App\Verkauf\Event;

use App\SharedKernel\DomainEvent;

final class BuchInInventarAufgenommen extends DomainEvent
{
    private $buchId;
    private $isbn;
    private $titel;
    private $autor;
    private $verkaufsPreis;

    public function __construct(string $buchId, string $isbn, string $titel, string $autor, int $verkaufsPreis)
    {
        $this->buchId = $buchId;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->autor = $autor;
        $this->verkaufsPreis = $verkaufsPreis;
    }

    public static function fromPayload(array $payload): DomainEvent
    {
        return new self(
            $payload['buchId'],
            $payload['isbn'],
            $payload['titel'],
            $payload['autor'],
            $payload['verkaufsPreis']
        );
    }

    public function getPayload(): array
    {
        return [
            'buchId' => $this->buchId,
            'isbn' => $this->isbn,
            'titel' => $this->titel,
            'autor' => $this->autor,
            'verkaufsPreis' => $this->verkaufsPreis,
        ];
    }

    public function getBuchId(): string
    {
        return $this->buchId;
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

    public function getVerkaufsPreis(): int
    {
        return $this->verkaufsPreis;
    }
}
