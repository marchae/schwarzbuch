<?php

declare(strict_types = 1);

namespace App\Controller;

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
        /**/
        $buch = Buch::kaufeBuch('marcel1', 'kjogrej438ujfg43', 'mit marcel zum erfolg', 'der schlaue', 5657484);
        $buchEinkaufRepository->speichern($buch);

        /** @var \App\Verleih\Entity\Buch $buchImVerleih */
        $buchImVerleih = $buchVerleihRepository->finde('marcel1');

        //$buchImVerleih->ausleihen('234234', (new \DateTimeImmutable('tomorrow'))->format('Y-m-d'));
        //$buchImVerleih->zurueckgeben();
        $buchVerleihRepository->speichern($buchImVerleih);

        return new Response($buchImVerleih->istVerliehen() ? 'ist verliehen' : 'ist ausleihbar');
    }
}
