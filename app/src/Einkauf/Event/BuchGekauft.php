<?php

declare(strict_types = 1);

namespace App\Einkauf\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchGekauft extends Event
{
    /**
     * @var string
     */
    private $buchId;
    /**
     * @var string
     */
    private $isbn;
    /**
     * @var string
     */
    private $titel;
    /**
     * @var \DateTimeInterface
     */
    private $kaufDatum;

    public function __construct(string $buchId, string $isbn, string $titel, \DateTimeInterface $kaufDatum)
    {
        $this->buchId = $buchId;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->kaufDatum = $kaufDatum;
    }

    public function buchId(): string
    {
        return $this->buchId;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function titel(): string
    {
        return $this->titel;
    }

    public function kaufDatum(): \DateTimeInterface
    {
        return $this->kaufDatum;
    }
}
