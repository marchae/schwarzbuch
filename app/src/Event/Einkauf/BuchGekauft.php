<?php

declare(strict_types = 1);

namespace App\Event\Einkauf;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchGekauft extends Event
{
    public const NAME = 'DomainEvent.BuchGekauft';

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
     * @var \DateTimeInterface
     */
    private $kaufDatum;

    public function __construct(string $id, string $titel, int $preis, \DateTimeInterface $kaufDatum)
    {
        $this->id = $id;
        $this->titel = $titel;
        $this->preis = $preis;
        $this->kaufDatum = $kaufDatum;
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

    public function kaufDatum(): \DateTimeInterface
    {
        return $this->kaufDatum;
    }
}
