<?php

declare(strict_types = 1);

namespace App\Einkauf\Entity;

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
        $this->kaufDatum = new \DateTimeImmutable();
    }

    public static function kaufeBuch(string $id, string $isbn, string $titel, string $autor, int $preis): self
    {
        return new self($id, $isbn, $titel, $autor, $preis);
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
