<?php

declare(strict_types = 1);

namespace App\Einkauf\Repository;

use App\Einkauf\Entity\Buch;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchRepository
{
    private $buecher = [];

    public function speichern(Buch $buch): void
    {
        $this->buecher[$buch->id()] = $buch;
    }
}
