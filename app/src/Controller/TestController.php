<?php

namespace App\Controller;

use App\Einkauf\Entity\Buch as BuchImEinkauf;
use App\Einkauf\Repository\BuchRepository as EinkaufBuchRepository;
use App\Verkauf\Repository\BuchRepository as VerkaufBuchRepository;
use App\Verleih\Entity\Verleih;
use App\Verleih\Repository\VerleihRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    private $history = [];

    /**
     * @var Verleih
     */
    private $verleih;

    private $buchImVerkauf;

    private $einkaufBuchRepository;
    private $verleihRepository;
    private $verkaufBuchRepository;

    public function __construct(
        EinkaufBuchRepository $einkaufBuchRepository,
        VerleihRepository $verleihRepository,
        VerkaufBuchRepository $verkaufBuchRepository
    ) {
        $this->einkaufBuchRepository = $einkaufBuchRepository;
        $this->verleihRepository = $verleihRepository;
        $this->verkaufBuchRepository = $verkaufBuchRepository;
    }

    public function test(): Response
    {
        $buchImEinkauf = BuchImEinkauf::kaufeBuch(
            '123',
            '978-0321125217',
            'Domain Driven Design',
            'Eric J. Evans',
            5289
        );

        $this->appendHistory('Einkauf', 'Buch sollte gekauft (inventarisiert) sein', $buchImEinkauf);

        $this->einkaufBuchRepository->speichern($buchImEinkauf);

        $this->buchImVerkauf = $this->verkaufBuchRepository->finde('123');
        $this->appendHistory('Verkauf', 'Buch sollte inventarisiert sein', $this->buchImVerkauf);

        $this->verleih = $this->verleihRepository->finde();
        $this->appendHistory('Verleih', 'Buch sollte inventarisiert sein', $this->verleih);

        $this->leiheBuchAusUndGibEsZurueck('123', '1', $this->getDate());
        $this->appendHistory('Verkauf', 'Buch sollte vorgemerkt sein', $this->buchImVerkauf);
        $this->leiheBuchAusUndGibEsZurueck('123', '2', $this->getDate());
        $this->appendHistory('Verkauf', 'Buch sollte vorgemerkt sein', $this->buchImVerkauf);
        $this->leiheBuchAusUndGibEsZurueck('123', '3', $this->getDate());
        $this->appendHistory('Verkauf', 'Buch sollte freigegeben sein', $this->buchImVerkauf);

        $this->buchImVerkauf->kaufen();

        $this->appendHistory('Verkauf', 'Buch sollte verkauft sein', $this->buchImVerkauf);

        return new JsonResponse($this->history);
    }

    private function appendHistory(string $context, string $action, $obj): void
    {
        $this->history[] = [
            'context' => $context,
            'action' => $action,
            'aggregateRoot' => [
                'class' => get_class($obj),
                'object' => $obj->toArray(),
            ],
        ];
    }

    private function leiheBuchAusUndGibEsZurueck(string $buchId, string $studentId, DateTimeImmutable $rueckgabeTermin): void
    {
        $this->verleih->buchAusleihen($buchId, $studentId, $rueckgabeTermin);
        $this->appendHistory('Verleih', 'Buch sollte ausgeliehen sein', $this->verleih);
        $this->verleih->buchZurueckgeben($buchId, $studentId);
        $this->verleihRepository->speichern($this->verleih);
        $this->appendHistory('Verleih', 'Buch sollte zurueckgegeben sein', $this->verleih);
    }

    private function getDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable(new DateTime('tomorrow'));
    }
}
