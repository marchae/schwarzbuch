<?php

namespace App\Verleih\Event;

use App\SharedKernel\DomainEvent;

final class BuchZumVerkaufFreigegeben extends DomainEvent
{
    private $buchId;

    public function __construct(string $buchId)
    {
        $this->buchId = $buchId;
    }

    public static function fromPayload(array $payload): DomainEvent
    {
        return new self($payload['buchId']);
    }

    public function getBuchId(): string
    {
        return $this->buchId;
    }

    public function getPayload(): array
    {
        return ['buchId' => $this->buchId];
    }
}
