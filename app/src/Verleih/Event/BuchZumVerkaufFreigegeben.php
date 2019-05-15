<?php

declare(strict_types = 1);

namespace App\Verleih\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchZumVerkaufFreigegeben
{
    /**
     * @var string
     */
    private $buchId;

    public function __construct(string $buchId)
    {
        $this->buchId = $buchId;
    }

    public function buchId(): string
    {
        return $this->buchId;
    }
}
