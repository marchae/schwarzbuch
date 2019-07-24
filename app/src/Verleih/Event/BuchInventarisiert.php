<?php

namespace App\Verleih\Event;

use App\SharedKernel\DomainEvent;
use DateTimeImmutable;

final class BuchInventarisiert extends DomainEvent
{
    private $buchId;
    private $isbn;
    private $titel;
    private $autor;
    private $kaufDatum;

    public function __construct(string $buchId, string $isbn, string $titel, string $autor, DateTimeImmutable $kaufDatum)
    {
        $this->buchId = $buchId;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->autor = $autor;
        $this->kaufDatum = $kaufDatum;
    }

    public static function fromPayload(array $payload): DomainEvent
    {
        return new self(
            $payload['buchId'],
            $payload['isbn'],
            $payload['titel'],
            $payload['autor'],
            DateTimeImmutable::createFromFormat('d.m.Y', $payload['kaufDatum'])
        );
    }

    public function getPayload(): array
    {
        return [
            'buchId' => $this->buchId,
            'isbn' => $this->isbn,
            'titel' => $this->titel,
            'autor' => $this->autor,
            'kaufDatum' => $this->kaufDatum->format('d.m.Y'),
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

    public function getKaufDatum(): DateTimeImmutable
    {
        return $this->kaufDatum;
    }
}
