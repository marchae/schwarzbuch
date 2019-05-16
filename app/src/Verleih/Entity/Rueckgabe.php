<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class Rueckgabe
{
    /**
     * @var string
     */
    private $buchId;
    /**
     * @var string
     */
    private $studentId;
    /**
     * @var \DateTimeImmutable
     */
    private $rueckgabeAm;

    private function __construct(string $buchId, string $studentId)
    {
        $this->buchId = $buchId;
        $this->studentId = $studentId;
        $this->rueckgabeAm = new \DateTimeImmutable();
    }

    public static function vonBuchDurchStudent(string $buchId, string $studentId): self
    {
        return new self($buchId, $studentId);
    }
}
