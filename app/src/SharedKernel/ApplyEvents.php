<?php

namespace App\SharedKernel;

use RuntimeException;

trait ApplyEvents
{
    private function apply(DomainEvent $event): void
    {
        $method = 'apply' . $event->getShortClassName();

        if (!method_exists($this, $method)) {
            throw new RuntimeException(sprintf('Missing method "%s"', $method));
        }

        $this->{$method}($event);
    }
}
