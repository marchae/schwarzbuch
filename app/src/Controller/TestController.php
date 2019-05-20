<?php

namespace App\Controller;

use App\DTO\Buch as BuchDTO;
use App\DTO\Student as StudentDTO;
use App\DTO\VerleihVorgang;
use App\Einkauf\Entity\Buch as BuchImEinkauf;
use App\Einkauf\Event\BuchGekauft;
use App\SharedKernel\EventStreamRepository;
use App\Verkauf\Entity\Buch as BuchImVerkauf;
use App\Verkauf\Event\BuchInInventarAufgenommen;
use App\Verleih\Entity\Buch as BuchImVerleih;
use App\Verleih\Entity\Name;
use App\Verleih\Entity\Student;
use App\Verleih\Event\BuchAusgeliehen;
use App\Verleih\Event\BuchZumVerkaufFreigegeben;
use App\Verleih\Event\BuchZurueckgegeben;
use Carbon\Carbon;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    /**
     * @var BuchDTO[]
     */
    private $buecher = [];
    /**
     * @var StudentDTO[]
     */
    private $studenten = [];
    private $eventLog = [];

    private $eventStreamRepository;

    public function __construct(EventStreamRepository $eventStreamRepository)
    {
        $this->eventStreamRepository = $eventStreamRepository;
    }

    public function test(Request $request): Response
    {
        $student1 = Student::registrieren('1', new Name('Marcel', 'Hergerdt'));
        $student2 = Student::registrieren('2', new Name('Max', 'Mustermann'));
        $student3 = Student::registrieren('3', new Name('Erika', 'Musterfrau'));

        $this->studenten = [
            $student1->getId() => $this->studentToDto($student1),
            $student2->getId() => $this->studentToDto($student2),
            $student3->getId() => $this->studentToDto($student3),
        ];

        $buch1ImEinkauf = $this->eventStreamRepository->finde(BuchImEinkauf::class, '123');
        if ($buch1ImEinkauf === null) {
            $buch1ImEinkauf = BuchImEinkauf::kaufeBuch(
                '123',
                '978-0321125217',
                'Domain Driven Design',
                'Eric J. Evans',
                5289
            );

            $this->eventStreamRepository->speichern($buch1ImEinkauf);
        }

        $buch2ImEinkauf = $this->eventStreamRepository->finde(BuchImEinkauf::class, '987');
        if ($buch2ImEinkauf === null) {
            $buch2ImEinkauf = BuchImEinkauf::kaufeBuch(
                '987',
                '978-3955715724',
                'Gewaltfreie Kommunikation: Eine Sprache des Lebens',
                'Marshall B. Rosenberg',
                2400
            );

            $this->eventStreamRepository->speichern($buch2ImEinkauf);
        }

        /** @var BuchImVerleih $buch1ImVerleih */
        $buch1ImVerleih = $this->eventStreamRepository->finde(BuchImVerleih::class, '123');
        /** @var BuchImVerleih $buch2ImVerleih */
        $buch2ImVerleih = $this->eventStreamRepository->finde(BuchImVerleih::class, '987');

        /** @var BuchImVerkauf $buchImVerkauf */
        $buch1ImVerkauf = $this->eventStreamRepository->finde(BuchImVerkauf::class, '123');
        /** @var BuchImVerkauf $buchImVerkauf */
        $buch2ImVerkauf = $this->eventStreamRepository->finde(BuchImVerkauf::class, '987');

        //$buch1ImVerleih->ausleihen($student1, DateTimeImmutable::createFromMutable(Carbon::tomorrow()));
        //$buch1ImVerleih->zurueckgeben();

        $this->eventStreamRepository->speichern($buch1ImVerleih);
        $this->eventStreamRepository->speichern($buch2ImVerleih);

        //$buchImVerkauf->kaufen();

        $this->loadEvents();

        if ($request->query->has('html')) {
            return $this->render('debug.html.twig', ['eventLog' => $this->eventLog, 'buecher' => $this->buecher]);
        } else {
            return new JsonResponse($this->eventLog);
        }
    }

    private function studentToDto(Student $student): StudentDTO
    {
        return new StudentDTO($student->getId(), $student->getName()->getVorname(), $student->getName()->getNachname());
    }

    private function loadEvents(): void
    {
        $events = $this->eventStreamRepository->getAll();

        foreach ($events as $event) {
            $this->eventLog[] = $event;

            switch (true) {
                case $event instanceof BuchGekauft:
                    $this->buecher[$event->getBuchId()] = new BuchDTO(
                        $event->getBuchId(),
                        $event->getIsbn(),
                        $event->getTitel(),
                        $event->getAutor(),
                        $event->getPreis(),
                        $event->getKaufDatum()
                    );
                    break;

                case $event instanceof BuchInInventarAufgenommen:
                    $this->buecher[$event->getBuchId()]
                        ->setVerkaufsPreis($event->getVerkaufsPreis());
                    break;

                case $event instanceof BuchAusgeliehen:
                    $this->buecher[$event->getBuchId()]
                        ->addVerleihVorgang(
                            new VerleihVorgang(
                                $event->getVerleihVorgangId(),
                                $this->studenten[$event->getStudentId()],
                                $event->getAusleihDatum(),
                                $event->getRueckgabeTermin()
                            )
                        );
                    break;

                case $event instanceof BuchZurueckgegeben:
                    $this->buecher[$event->getBuchId()]
                        ->getVerleihVorgang($event->getVerleihVorgangId())
                        ->setRueckgabeDatum($event->getRueckgabeDatum());
                    break;

                case $event instanceof BuchZumVerkaufFreigegeben:
                    $this->buecher[$event->getBuchId()]->setZumVerkaufFreigegeben(true);
                    break;
            }
        }
    }
}
