<?php

declare(strict_types = 1);

namespace App\Verleih\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchZurueckgegeben extends Event
{
    public static function vonStudent(string $buchId, string $studentId): self
    {
        return new self();
    }
}
