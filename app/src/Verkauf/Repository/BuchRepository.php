<?php

namespace App\Verkauf\Repository;

use App\Verkauf\Entity\Buch;

final class BuchRepository
{
    private $buecher = [];

    public function speichern(Buch $buch): void
    {
        $this->buecher[$buch->getId()] = $buch;
    }

    public function finde(string $buchId): ?Buch
    {
        return $this->buecher[$buchId] ?? null;
    }
}
