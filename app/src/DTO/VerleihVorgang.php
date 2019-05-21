<?php

namespace App\DTO;

use Carbon\Carbon;
use DateTimeImmutable;

final class VerleihVorgang
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var Student
     */
    private $student;
    /**
     * @var DateTimeImmutable
     */
    private $ausleihDatum;
    /**
     * @var DateTimeImmutable
     */
    private $rueckgabeTermin;
    /**
     * @var ?DateTimeImmutable
     */
    private $rueckgabeDatum;

    public function __construct(string $id, Student $student, DateTimeImmutable $ausleihDatum, DateTimeImmutable $rueckgabeTermin)
    {
        $this->id = $id;
        $this->student = $student;
        $this->ausleihDatum = $ausleihDatum;
        $this->rueckgabeTermin = $rueckgabeTermin;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getStudent(): Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): VerleihVorgang
    {
        $this->student = $student;

        return $this;
    }

    public function getAusleihDatum(): DateTimeImmutable
    {
        return $this->ausleihDatum;
    }

    public function setAusleihDatum(DateTimeImmutable $ausleihDatum): VerleihVorgang
    {
        $this->ausleihDatum = $ausleihDatum;

        return $this;
    }

    public function getRueckgabeTermin(): DateTimeImmutable
    {
        return $this->rueckgabeTermin;
    }

    public function setRueckgabeTermin(DateTimeImmutable $rueckgabeTermin): VerleihVorgang
    {
        $this->rueckgabeTermin = $rueckgabeTermin;

        return $this;
    }

    public function getRueckgabeDatum(): ?DateTimeImmutable
    {
        return $this->rueckgabeDatum;
    }

    public function setRueckgabeDatum(DateTimeImmutable $rueckgabeDatum): VerleihVorgang
    {
        $this->rueckgabeDatum = $rueckgabeDatum;

        return $this;
    }

    public function istAbgeschlossen(): bool
    {
        return $this->rueckgabeDatum !== null;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'student' => $this->student->toArray(),
            'ausleihDatum' => $this->ausleihDatum->format('d.m.Y'),
            'rueckgabeTermin' => $this->rueckgabeTermin->format('d.m.Y'),
            'rueckgabeDatum' => $this->rueckgabeDatum ? $this->rueckgabeDatum->format('d.m.Y') : '',
            'tage' => $this->getTage(),
            'tageUeberzogen' => $this->getTageUeberzogen(),
        ];
    }

    public function getTage(): int
    {
        return Carbon::instance($this->rueckgabeDatum ?? Carbon::today())->diffInDays($this->ausleihDatum);
    }

    public function getTageUeberzogen(): int
    {
        $today = Carbon::today();

        if (Carbon::instance($this->rueckgabeTermin)->isBefore($this->rueckgabeDatum ?? $today)) {
            return 0;
        }

        return Carbon::instance($this->rueckgabeDatum ?? $today)->diffInDays();
    }
}
