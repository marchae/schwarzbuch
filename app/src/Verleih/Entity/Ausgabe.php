<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class Ausgabe
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
    private $ausgabeAm;

    private function __construct(string $buchId, string $studentId)
    {
        $this->buchId = $buchId;
        $this->studentId = $studentId;
        $this->ausgabeAm = new \DateTimeImmutable();
    }

    public static function vonBuchAnStudent(string $buchId, string $studentId): self
    {
        return new self($buchId, $studentId);
    }
}
