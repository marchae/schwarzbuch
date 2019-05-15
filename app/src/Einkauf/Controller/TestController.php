<?php

declare(strict_types = 1);

namespace App\Einkauf\Controller;

use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class TestController
{
    public function test(): Response
    {
        return new Response('it works');
    }
}
