<?php

declare(strict_types = 1);

namespace App\Common;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class DomainEvent extends Event
{
    protected $payload = [];

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public static function fromPayload(array $payload): self
    {
        return new self($payload);
    }
}
