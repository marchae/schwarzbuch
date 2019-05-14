<?php

declare(strict_types = 1);

namespace App\Repository\Einkauf;

use App\Entity\Verleih\Buch;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchRepository
{
    private $buecher = [];

    public function findeById(string $id): ?Buch
    {
        if (!isset($this->buecher[$id])) {
            return null;
        }

        return $this->buecher[$id];
    }

    public function speichern(Buch $buch): void
    {
        $this->buecher[$buch->id()] = $buch;
    }
}
