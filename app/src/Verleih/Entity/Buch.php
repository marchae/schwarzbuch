<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

use App\Infrastructure\AggregateRoot;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Buch extends AggregateRoot
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $titel;
    /**
     * @var string
     */
    private $isbn;
    /**
     * @var \DateTimeInterface
     */
    private $kaufDatum;
    /**
     * @var bool
     */
    private $istVerliehen = false;
    private $verleihVerlauf = [];

    public function __construct(string $id, string $titel, string $isbn, \DateTimeInterface $kaufDatum)
    {
        $this->id = $id;
        $this->titel = $titel;
        $this->isbn = $isbn;
        $this->kaufDatum = $kaufDatum;
    }

    public static function nehmeBuchInInventarAuf(string $id, string $titel, string $isbn, \DateTimeInterface $kaufDatum): self
    {
        return new self($id, $titel, $isbn, $kaufDatum);
    }

    public function ausleihen(string $verleihId): void
    {
        if ($this->istVerliehen()) {
            throw new \DomainException('Buch ist bereits verliehen');
        }

        if ($this->istAusleihLimitErreicht()) {
            throw new \DomainException('Buch steht dem Verleih nicht mehr zur Verfügung');
        }

        $this->istVerliehen = true;
        $this->verleihVerlauf[] = $verleihId;
    }

    public function gibBuchZurueck(): void
    {
        if (!$this->istVerliehen()) {
            throw new \DomainException('Ups, Buch ist gar nicht verliehen');
        }

        if ($this->istAusleihLimitErreicht()) {
            $this->raise(new BuchZumVerkaufFreigegeben($this->id()));
        }
    }

    public function istVerleihbar(): bool
    {
        return !$this->istVerliehen() && !$this->istAusleihLimitErreicht();
    }

    private function istAusleihLimitErreicht(): bool
    {
        return count($this->verleihVerlauf) >= 3;
    }

    private function istVerliehen(): bool
    {
        return $this->istVerliehen;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function titel(): string
    {
        return $this->titel;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function kaufDatum(): \DateTimeInterface
    {
        return $this->kaufDatum;
    }
}
