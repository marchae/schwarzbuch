<?php

declare(strict_types = 1);

namespace App\Entity\Einkauf;

use App\Event\Einkauf\BuchGekauft;

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
    /**
     * @var array
     */
    private $domainEvents = [];

    private function __construct(string $id, string $titel, int $preis)
    {
        $this->id = $id;
        $this->titel = $titel;
        $this->preis = $preis;
        $this->kaufDatum = new \DateTimeImmutable();

    }

    public static function kaufeBuch(string $id, string $titel, int $preis): self
    {
        $buch = new self($id, $titel, $preis);

        $buch->raise(new BuchGekauft($buch->id, $buch->titel, $buch->preis, $buch->kaufDatum));

        return $buch;
    }

    public function popDomainEvents(): array
    {
        $domainEvents = $this->domainEvents;

        $this->domainEvents = [];

        return $domainEvents;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function titel(): string
    {
        return $this->titel;
    }

    public function preis(): int
    {
        return $this->preis;
    }

    public function kaufDatum(): \DateTimeImmutable
    {
        return $this->kaufDatum;
    }

    private function raise($event)
    {
        $this->domainEvents[] = $event;
    }

}
