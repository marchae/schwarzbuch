<?php

declare(strict_types = 1);

namespace App\Entity\Vertrieb;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Buch
{
    private $id;
    private $titel;
    private $preis;
    private $verkauf;

    private function __construct(string $id, string $titel, int $preis)
    {
        $this->id = $id;
        $this->titel = $titel;
        $this->preis = $preis;
    }

    public static function zumVerkaufAnbieten(string $id, string $titel, int $preis): self
    {
        return new self($id, $titel, $preis);
    }

    public function verkaufen(string $nutzerId, int $preis): void
    {
        $this->verkauf = Verkauf::anNutzer($this->id(), $nutzerId, $preis);
    }

    public function verkauft(): bool
    {
        return $this->verkauf instanceof Verkauf;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function titel(): string
    {
        return $this->titel;
    }
}
