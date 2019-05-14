<?php

declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Einkauf\Buch;
use App\Repository\Einkauf\BuchRepository as BuchEinkaufRepository;
use App\Repository\Verleih\BuchRepository as BuchVerleihRepository;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
class TestController
{
    public function test(BuchEinkaufRepository $buchEinkaufRepository, BuchVerleihRepository $buchVerleihRepository): Response
    {
        $buchImEinkauf = Buch::kaufeBuch('123', 'some title', 4545);

        $buchEinkaufRepository->speichern($buchImEinkauf);

        $buchImVerleih = $buchVerleihRepository->findeById('123');

        return new Response($buchImVerleih->titel());
    }
}
