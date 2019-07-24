<?php

namespace App\Verleih\Entity;

use DateTimeImmutable;

final class VerleihVorgang
{
    private $id;
    private $buchId;
    private $studentId;
    private $ausgabeDatum;
    private $rueckgabeDatum;
    private $abgeschlossen;

    private function __construct(string $id, string $buchId, string $studentId, DateTimeImmutable $ausgabeDatum, DateTimeImmutable $rueckgabeDatum)
    {
        $this->id = $id;
        $this->buchId = $buchId;
        $this->studentId = $studentId;
        $this->ausgabeDatum = $ausgabeDatum;
        $this->rueckgabeDatum = $rueckgabeDatum;
        $this->abgeschlossen = false;
    }

    public static function beginnen(string $id, string $buchId, string $studentId, DateTimeImmutable $ausgabeDatum, DateTimeImmutable $rueckgabeDatum): VerleihVorgang
    {
        return new self($id, $buchId, $studentId, $ausgabeDatum, $rueckgabeDatum);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBuchId(): string
    {
        return $this->buchId;
    }

    public function getStudentId(): string
    {
        return $this->studentId;
    }

    public function getAusgabeDatum(): DateTimeImmutable
    {
        return $this->ausgabeDatum;
    }

    public function getRueckgabeDatum(): DateTimeImmutable
    {
        return $this->rueckgabeDatum;
    }

    public function isAbgeschlossen(): bool
    {
        return $this->abgeschlossen;
    }

    public function istOffen(): bool
    {
        return !$this->abgeschlossen;
    }

    public function abschliessen(): void
    {
        $this->abgeschlossen = true;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->studentId,
            'buchId' => $this->buchId,
            'studentId' => $this->studentId,
            'abgeschlossen' => $this->abgeschlossen,
            'ausgabedatum' => $this->ausgabeDatum->format('d.m.Y'),
            'rueckgabedatum' => $this->rueckgabeDatum->format('d.m.Y'),
        ];
    }
}
