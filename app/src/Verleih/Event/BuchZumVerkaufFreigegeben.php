<?php

namespace App\Verleih\Event;

use App\Verleih\Entity\Buch;
use Symfony\Component\EventDispatcher\Event;

final class BuchZumVerkaufFreigegeben extends Event
{
    private $buchId;

    public function __construct(Buch $buch)
    {
        $this->buchId = $buch->getId();
    }

    public function getBuchId(): string
    {
        return $this->buchId;
    }
}
