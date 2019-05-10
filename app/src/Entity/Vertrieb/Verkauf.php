<?php

declare(strict_types = 1);

namespace App\Entity\Vertrieb;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Verkauf
{
    private $buchId;
    private $nutzerId;
    private $preis;
    private $verkauftAm;

    private function __construct(string $buchId, string $nutzerId, int $preis)
    {
        $this->buchId = $buchId;
        $this->nutzerId = $nutzerId;
        $this->preis = $preis;
        $this->verkauftAm = new \DateTimeImmutable();
    }

    public static function anNutzer(string $buchId, string $nutzerId, int $preis): self
    {
        return new self($buchId, $nutzerId, $preis);
    }
}
