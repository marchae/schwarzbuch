<?php

namespace App\Verleih\Entity;

final class Name
{
    private $vorname;
    private $nachname;

    public function __construct(string $vorname, string $nachname)
    {
        $this->vorname = $vorname;
        $this->nachname = $nachname;
    }

    public function getVorname(): string
    {
        return $this->vorname;
    }

    public function getNachname(): string
    {
        return $this->nachname;
    }

    public function __toString()
    {
        return $this->vorname . ' ' . $this->getNachname();
    }
}
