<?php

namespace App\Verleih\Entity;

final class Student
{
    private $id;
    private $name;
    private $gesperrt;

    private function __construct(string $id, Name $name, bool $gesperrt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->gesperrt = $gesperrt;
    }

    public static function registrieren(string $id, Name $name): self
    {
        return new self($id, $name, false);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function istGesperrt(): bool
    {
        return $this->gesperrt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => (string) $this->name,
            'gesperrt' => $this->gesperrt,
        ];
    }
}
