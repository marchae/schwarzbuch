<?php

declare(strict_types = 1);

namespace App\Verleih\Repository;

use App\Common\EventStreamRepository;
use App\Verleih\Entity\Buch;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchRepository extends EventStreamRepository
{
    protected function getAggregateRootClassName(): string
    {
        return Buch::class;
    }
}
