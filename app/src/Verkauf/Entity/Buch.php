<?php

namespace App\Verkauf\Entity;

final class Buch
{
    private const STATUS_VORGEMERKT = 'vorgemerkt';
    private const STATUS_FREIGEGEBEN = 'freigegeben';
    private const STATUS_VERKAUFT = 'verkauft';

    private $id;
    private $titel;
    private $isbn;
    private $verkaufsPreis;
    private $status;

    public function __construct(string $buchId, string $titel, string $isbn, int $verkaufsPreis)
    {
        $this->id = $buchId;
        $this->titel = $titel;
        $this->isbn = $isbn;
        $this->verkaufsPreis = $verkaufsPreis;
        $this->status = self::STATUS_VORGEMERKT;
    }

    public static function nehmeBuchInInventarAuf(string $buchId, string $titel, string $isbn, int $einkaufsPreis): Buch
    {
        $verkaufsPreis = $einkaufsPreis * 0.9;

        return new self($buchId, $titel, $isbn, $verkaufsPreis);
    }

    public function getId(): string
    {
        return $this->id;
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
        $this->status = self::STATUS_FREIGEGEBEN;
    }

    public function kaufen(): void
    {
        $this->status = self::STATUS_VERKAUFT;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'isbn' => $this->isbn,
            'titel' => $this->titel,
            'verkaufsPreis' => sprintf('%s €', number_format($this->verkaufsPreis / 100, 2, ',', '.')),
            'status' => $this->status,
        ];
    }
}
