<?php

declare(strict_types = 1);

namespace App\Verleih\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchAusgegeben extends Event
{
    /**
     * @var string
     */
    private $verleihId;
    /**
     * @var string
     */
    private $buchId;
    /**
     * @var string
     */
    private $studentId;
    /**
     * @var \DateTimeImmutable
     */
    private $voraussichtlichBis;

    private function __construct(string $verleihId, string $buchId, string $studentId, \DateTimeInterface $voraussichtlichBis)
    {
        $this->verleihId = $verleihId;
        $this->buchId = $buchId;
        $this->studentId = $studentId;
        $this->voraussichtlichBis = $voraussichtlichBis;
    }

    public static function anStudent(string $verleihId, string $buchId, string $studentId, \DateTimeInterface $voraussichtlichBis): self
    {
        return new self($verleihId, $buchId, $studentId, $voraussichtlichBis);
    }

    public function verleihId(): string
    {
        return $this->verleihId;
    }

    public function buchId(): string
    {
        return $this->buchId;
    }

    public function studentId(): string
    {
        return $this->studentId;
    }

    public function voraussichtlichBis(): \DateTimeImmutable
    {
        return $this->voraussichtlichBis;
    }
}
