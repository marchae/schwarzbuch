<?php

declare(strict_types = 1);

namespace App\Entity\Einkauf;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Buch
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
     * @var int
     */
    private $preis;
    /**
     * @var \DateTimeImmutable
     */
    private $kaufDatum;

    private function __construct(string $id, string $titel, int $preis)
    {
        $this->id = $id;
        $this->titel = $titel;
        $this->preis = $preis;
        $this->kaufDatum = new \DateTimeImmutable();
    }

    public static function kaufeBuch(string $id, string $titel, int $preis): self
    {
        return new self($id, $titel, $preis);
    }
}
