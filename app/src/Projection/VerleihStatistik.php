<?php

declare(strict_types = 1);

namespace App\Projection;

use App\Common\DomainEvent;
use App\Verleih\Event\BuchAusgeliehen;
use App\Verleih\Event\BuchZurueckgegeben;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class VerleihStatistik
{
    private $buchAusleihQuote = [];
    private $studentAusleihQuote = [];
    private $rueckgabeQuote = [];

    public function apply(DomainEvent $event): void
    {
        switch (true) {
            case $event instanceof BuchAusgeliehen:
                $this->buchAusleihQuote[$event->payload()['buchId']] += 1;
                $this->studentAusleihQuote[$event->payload()['studentId']] += 1;
                $this->rueckgabeQuote[$event->payload()['verleihVorgangId']] = 0;
                break;

            case $event instanceof BuchZurueckgegeben:
                $this->rueckgabeQuote[$event->payload()['verleihVorgangId']] = 1;
                break;
        }
    }

    public function buchAusleihQuote(): array
    {
        return $this->buchAusleihQuote;
    }

    public function studentAusleihQuote(): array
    {
        return $this->studentAusleihQuote;
    }

    public function rueckgabeQoute(): array
    {
        // @todo some calculation needed
        return [];
    }

}
