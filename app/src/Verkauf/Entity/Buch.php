<?php

declare(strict_types = 1);

namespace App\Verkauf\Entity;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class Buch
{
    private const STATUS_VORGEMERKT = 0;
    private const STATUS_FREIGEGEBEN = 1;
    private const STATUS_VERKAUFT = 2;

    /**
     * @var string
     */
    private $buchId;
    /**
     * @var string
     */
    private $titel;
    /**
     * @var string
     */
    private $isbn;
    /**
     * @var int
     */
    private $verkaufsPreis;
    /**
     * @var bool
     */
    private $status = self::STATUS_VORGEMERKT;

    private function __construct(string $buchId, string $titel, string $isbn, int $verkaufsPreis)
    {
        $this->buchId = $buchId;
        $this->titel = $titel;
        $this->isbn = $isbn;
        $this->verkaufsPreis = $verkaufsPreis;
    }

    public static function nehmeBuchInInventarAuf(string $buchId, string $titel, string $isbn, int $einkaufsPreis)
    {
        $verkaufsPreis = (int) round($einkaufsPreis * 0.9);

        return new self($buchId, $titel, $isbn, $verkaufsPreis);
    }

    public function zumVerkaufFreigeben(): void
    {
        $this->status = self::STATUS_FREIGEGEBEN;
    }

    public function buchId(): string
    {
        return $this->buchId;
    }

    public function titel(): string
    {
        return $this->titel;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function verkaufsPreis(): int
    {
        return $this->verkaufsPreis;
    }

    public function istFreigegeben(): bool
    {
        return $this->status === self::STATUS_FREIGEGEBEN;
    }
}
