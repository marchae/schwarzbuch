<?php

declare(strict_types = 1);

namespace App\Einkauf\Controller;

use App\Einkauf\Entity\Buch;
use App\Einkauf\Repository\BuchRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class TestController
{
    public function test(BuchRepository $buchRepository): Response
    {
        $buch = Buch::kaufeBuch('123', 'jdfjhe3', 'mit lidl zum erfolg', 'der killerwal', 455);

        $buchRepository->speichern($buch);

        return new Response($buchRepository->finde('123')->titel());
    }
}
