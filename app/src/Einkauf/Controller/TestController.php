<?php

declare(strict_types = 1);

namespace App\Einkauf\Controller;

use App\Einkauf\Entity\Buch;
use App\Einkauf\Repository\BuchRepository as BuchEinkaufRepository;
use App\Verleih\Repository\BuchRepository as BuchVerleihRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class TestController
{
    public function test(BuchEinkaufRepository $buchEinkaufRepository, BuchVerleihRepository $buchVerleihRepository): Response
    {
        $buch = Buch::kaufeBuch('123', 'jdfjhe3', 'mit lidl zum erfolg', 'der killerwal', 455);

        $buchEinkaufRepository->speichern($buch);

        return new Response($buchVerleihRepository->finde('123')->titel());
    }
}
