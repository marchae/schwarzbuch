<?php

namespace App\DTO;

final class Student
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $vorname;
    /**
     * @var string
     */
    private $nachname;

    public function __construct(string $id, string $vorname, string $nachname)
    {
        $this->id = $id;
        $this->vorname = $vorname;
        $this->nachname = $nachname;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): Student
    {
        $this->id = $id;

        return $this;
    }

    public function getVorname(): string
    {
        return $this->vorname;
    }

    public function setVorname(string $vorname): Student
    {
        $this->vorname = $vorname;

        return $this;
    }

    public function getNachname(): string
    {
        return $this->nachname;
    }

    public function setNachname(string $nachname): Student
    {
        $this->nachname = $nachname;

        return $this;
    }

    public function getName(): string
    {
        return $this->vorname . ' ' . $this->nachname;
    }
}
