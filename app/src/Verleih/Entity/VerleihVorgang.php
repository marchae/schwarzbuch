<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class VerleihVorgang
{
    /**
     * @var string
     */
    private $id;
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
    private $ausgabeDatum;
    /**
     * @var \DateTimeImmutable
     */
    private $rueckgabeTermin;
    /**
     * @var bool
     */
    private $zurueckgegeben = false;

    private function __construct(string $id, string $buchId, string $studentId, \DateTimeImmutable $ausgabeDatum, \DateTimeImmutable $rueckgabeTermin)
    {
        $this->id = $id;
        $this->buchId = $buchId;
        $this->studentId = $studentId;
        $this->ausgabeDatum = $ausgabeDatum;
        $this->rueckgabeTermin = $rueckgabeTermin;
    }

    public static function beginnen(string $id, string $buchId, string $studentId, \DateTimeImmutable $ausgabeDatum, \DateTimeImmutable $rueckgabeTermin): self
    {
        return new self($id, $buchId, $studentId, $ausgabeDatum, $rueckgabeTermin);
    }

    public function offen(): bool
    {
        return !$this->zurueckgegeben;
    }
}
