<?php

namespace App\Verleih\Event;

use App\SharedKernel\DomainEvent;
use DateTimeImmutable;

final class BuchZurueckgegeben extends DomainEvent
{
    private $verleihVorgangId;
    private $buchId;
    private $rueckgabeDatum;

    public function __construct(string $verleihVorgangId, string $buchId, DateTimeImmutable $rueckgabeDatum)
    {
        $this->verleihVorgangId = $verleihVorgangId;
        $this->buchId = $buchId;
        $this->rueckgabeDatum = $rueckgabeDatum;
    }

    public static function fromPayload(array $payload): DomainEvent
    {
        return new self(
            $payload['verleihVorgangId'],
            $payload['buchId'],
            DateTimeImmutable::createFromFormat('d.m.Y', $payload['rueckgabeDatum'])
        );
    }

    public function getPayload(): array
    {
        return [
            'verleihVorgangId' => $this->verleihVorgangId,
            'buchId' => $this->buchId,
            'rueckgabeDatum' => $this->rueckgabeDatum->format('d.m.Y'),
        ];
    }

    public function getVerleihVorgangId(): string
    {
        return $this->verleihVorgangId;
    }

    public function getBuchId(): string
    {
        return $this->buchId;
    }

    public function getRueckgabeDatum(): DateTimeImmutable
    {
        return $this->rueckgabeDatum;
    }
}
