<?php

namespace App\Verleih\Repository;

use App\SharedKernel\DispatchEvents;
use App\Verleih\Entity\Buch;
use App\Verleih\Entity\Name;
use App\Verleih\Entity\Student;
use App\Verleih\Entity\Verleih;
use App\Verleih\Entity\VerleihVorgang;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class VerleihRepository
{
    use DispatchEvents;

    /**
     * @var array|Buch[]
     */
    private $buecher = [];
    /**
     * @var array|Student[]
     */
    private $studenten = [];
    /**
     * @var array|VerleihVorgang[]
     */
    private $verleihVorgaenge = [];

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        // Pretend we have some students
        $this->studenten = [
            '1' => Student::registrieren('1', new Name('Marcel', 'Hergerdt')),
            '2' => Student::registrieren('2', new Name('Max', 'Mustermann')),
            '3' => Student::registrieren('3', new Name('Erika', 'Musterfrau')),
        ];

        $this->setEventDispatcher($eventDispatcher);
    }

    public function finde(): Verleih
    {
        return Verleih::eroeffnen('1', $this->buecher, $this->studenten, $this->verleihVorgaenge);
    }

    public function speichern(Verleih $verleih): void
    {
        $this->buecher = $verleih->getBuecher();
        $this->studenten = $verleih->getStudenten();
        $this->verleihVorgaenge = $verleih->getVerleihVorgaenge();

        $this->dispatchEvents($verleih->popDomainEvents());
    }
}
