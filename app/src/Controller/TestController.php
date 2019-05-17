<?php

namespace App\Controller;

use App\Einkauf\Repository\BuchRepository as EinkaufBuchRepository;
use App\Einkauf\Repository\BuchRepository;
use App\SharedKernel\EventStreamRepository;
use App\Einkauf\Entity\Buch as BuchImEinkauf;
use App\Verkauf\Entity\Buch as BuchImVerkauf;
use App\Verleih\Entity\Buch as BuchImVerleih;
use App\Verleih\Entity\Name;
use App\Verleih\Entity\Student;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    private $history = [];

    private $einkaufBuchRepository;
    private $eventStreamRepository;
    private $verkaufBuchRepository;

    public function __construct(EinkaufBuchRepository $einkaufBuchRepository, EventStreamRepository $eventStreamRepository)
    {
        $this->einkaufBuchRepository = $einkaufBuchRepository;
        $this->eventStreamRepository = $eventStreamRepository;
    }

    public function test(Request $request, BuchRepository $buchRepository): Response
    {
        $student1 = Student::registrieren('1', new Name('Marcel', 'Hergerdt'));
        $student2 = Student::registrieren('2', new Name('Max', 'Mustermann'));
        $student3 = Student::registrieren('3', new Name('Erika', 'Musterfrau'));

        $buch = $buchRepository->finde('123');
        if ($buch === null) {
            $buchImEinkauf = BuchImEinkauf::kaufeBuch(
                '123',
                '978-0321125217',
                'Domain Driven Design',
                'Eric J. Evans',
                5289
            );

            $this->appendHistory('Einkauf', 'Buch sollte gekauft (inventarisiert) sein', $buchImEinkauf);

            $this->einkaufBuchRepository->speichern($buchImEinkauf);
        }

        /** @var BuchImVerkauf $buchImVerkauf */
        $buchImVerkauf = $this->eventStreamRepository->finde(BuchImVerkauf::class, '123');
        $this->appendHistory('Verkauf', 'Buch sollte inventarisiert sein', $buchImVerkauf);

        /** @var BuchImVerleih $buchImVerleih */
        $buchImVerleih = $this->eventStreamRepository->finde(BuchImVerleih::class, '123');

        $this->appendHistory('Verleih', 'Buch sollte inventarisiert sein', $buchImVerleih);

        ///*
        $this->leiheBuchAusUndGibEsZurueck($buchImVerleih, $student1, $this->getDate());
        $this->appendHistory('Verkauf', 'Buch sollte vorgemerkt sein', $buchImVerkauf);
        $this->leiheBuchAusUndGibEsZurueck($buchImVerleih, $student2, $this->getDate());
        $this->appendHistory('Verkauf', 'Buch sollte vorgemerkt sein', $buchImVerkauf);
        $this->leiheBuchAusUndGibEsZurueck($buchImVerleih, $student3, $this->getDate());
        $this->eventStreamRepository->speichern($buchImVerleih);
        $this->appendHistory('Verkauf', 'Buch sollte freigegeben sein', $buchImVerkauf);
        //*/

        $this->appendHistory('Verkauf', 'Buch sollte freigegeben sein', $buchImVerkauf);

        //$buchImVerkauf->kaufen();

        //$this->appendHistory('Verkauf', 'Buch sollte verkauft sein', $buchImVerkauf);

        if ($request->query->has('html')) {
            return $this->render('debug.html.twig', ['history' => $this->history]);
        } else {
            return new JsonResponse($this->history);
        }
    }

    private function appendHistory(string $context, string $action, $obj): void
    {
        if ($obj === null) {
            return;
        }

        $this->history[] = [
            'context' => $context,
            'action' => $action,
            'aggregateRoot' => [
                'class' => get_class($obj),
                'object' => $obj->toArray(),
            ],
        ];
    }

    private function leiheBuchAusUndGibEsZurueck(BuchImVerleih $buch, Student $student, DateTimeImmutable $rueckgabeTermin): void
    {
        $buch->ausleihen($student, $rueckgabeTermin);
        $this->appendHistory('Verleih', 'Buch sollte ausgeliehen sein', $buch);
        $buch->zurueckgeben();
        //$this->eventStreamRepository->speichern($buch);
        $this->appendHistory('Verleih', 'Buch sollte zurueckgegeben sein', $buch);
    }

    private function getDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromMutable(new DateTime('tomorrow'));
    }
}
