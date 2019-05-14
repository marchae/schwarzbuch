<?php

declare(strict_types = 1);

namespace App\Event\Verleih;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchZumVerkaufFreigegeben
{
    private $id;
    private $freigegebenAm;

    public function __construct(string $id, \DateTimeInterface $freigegebenAm)
    {
        $this->id = $id;
        $this->freigegebenAm = $freigegebenAm;
    }
}
