<?php

declare(strict_types = 1);

namespace App\Einkauf\Entity;

use App\Einkauf\Event\BuchGekauft;
use App\Common\AggregateRoot;

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
    private $isbn;
    /**
     * @var string
     */
    private $titel;
    /**
     * @var string
     */
    private $autor;
    /**
     * @var int
     */
    private $preis;
    /**
     * @var \DateTimeImmutable
     */
    private $kaufDatum;

    private function __construct(string $id, string $isbn, string $titel, string $autor, int $preis)
    {
        $this->id = $id;
        $this->isbn = $isbn;
        $this->titel = $titel;
        $this->autor = $autor;
        $this->preis = $preis;
        $this->kaufDatum = (new \DateTimeImmutable())->format('Y-m-d');
    }

    public static function kaufeBuch(string $id, string $isbn, string $titel, string $autor, int $preis): self
    {
        $buch = new self($id, $isbn, $titel, $autor, $preis);

        $buch->raise(
            new BuchGekauft(
                ['buchId' => $id, 'isbn' => $isbn, 'titel' => $titel, 'kaufDatum' => $buch->kaufDatum, 'preis' => $preis]
            )
        );

        return $buch;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function isbn(): string
    {
        return $this->isbn;
    }

    public function titel(): string
    {
        return $this->titel;
    }

    public function autor(): string
    {
        return $this->autor;
    }

    public function preis(): int
    {
        return $this->preis;
    }

    public function kaufDatum(): \DateTimeImmutable
    {
        return $this->kaufDatum;
    }
}
