<?php

declare(strict_types = 1);

namespace App\Entity\Verleih;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Ausleihe
{
    private $userId;
    private $buchId;
    private $von;
    private $bis;
    private $abgeschlossen = false;
    /**
     * @var \DateTimeInterface
     */
    private $abgeschlossenAm;

    private function __construct(string $userId, string $buchId, \DateTimeInterface $von, \DateTimeInterface $bis)
    {
        $this->userId = $userId;
        $this->buchId = $buchId;
        $this->von = $von;
        $this->bis = $bis;
    }

    public static function fuerNutzer(string $userId, string $buchId, \DateTimeInterface $bis): self
    {
        return new self($userId, $buchId, new \DateTimeImmutable(), $bis);
    }

    public function abschliessen(): void
    {
        $this->abgeschlossen = true;
        $this->abgeschlossenAm = new \DateTimeImmutable();
    }

    public function abgeschlossen(): bool
    {
        return $this->abgeschlossen;
    }

    public function buchId(): string
    {
        return $this->buchId;
    }
}
