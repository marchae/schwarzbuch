<?php

declare(strict_types = 1);

namespace App\Verleih\Event;

use App\Common\DomainEvent;
use Symfony\Component\EventDispatcher\Event;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class BuchAusgeliehen extends DomainEvent
{
}
