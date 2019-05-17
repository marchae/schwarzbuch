<?php

namespace App\Verleih\Event;

use App\SharedKernel\DomainEvent;
use DateTimeImmutable;

final class BuchAusgeliehen extends DomainEvent
{
    private $buchId;
    private $studentId;
    private $ausleihDatum;
    private $rueckgabeTermin;

    public function __construct(string $buchId, string $studentId, DateTimeImmutable $ausleihDatum, DateTimeImmutable $rueckgabeTermin)
    {
        $this->buchId = $buchId;
        $this->studentId = $studentId;
        $this->ausleihDatum = $ausleihDatum;
        $this->rueckgabeTermin = $rueckgabeTermin;
    }

    public static function fromPayload(array $payload): DomainEvent
    {
        return new self(
            $payload['buchId'],
            $payload['studentId'],
            DateTimeImmutable::createFromFormat('d.m.Y', $payload['ausleihDatum']),
            DateTimeImmutable::createFromFormat('d.m.Y', $payload['rueckgabeTermin'])
        );
    }

    public function getPayload(): array
    {
        return [
            'buchId' => $this->buchId,
            'studentId' => $this->studentId,
            'ausleihDatum' => $this->ausleihDatum->format('d.m.Y'),
            'rueckgabeTermin' => $this->rueckgabeTermin->format('d.m.Y'),
        ];
    }

    public function getBuchId(): string
    {
        return $this->buchId;
    }

    public function getStudentId(): string
    {
        return $this->studentId;
    }

    public function getAusleihDatum(): DateTimeImmutable
    {
        return $this->ausleihDatum;
    }

    public function getRueckgabeTermin(): DateTimeImmutable
    {
        return $this->rueckgabeTermin;
    }
}
