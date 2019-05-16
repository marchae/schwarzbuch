<?php

declare(strict_types = 1);

namespace App\Verleih\Entity;

use App\Infrastructure\AggregateRoot;
use App\Verleih\Event\BuchAusgegeben;
use App\Verleih\Event\BuchZurueckgegeben;

/**
 * @author Marcus Häußler <marcus.haeussler@lidl.com>
 */
final class Verleih extends AggregateRoot
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var Ausgabe
     */
    private $ausgabe;
    /**
     * @var Rueckgabe
     */
    private $rueckgabe;
    /**
     * @var \DateTimeInterface
     */
    private $voraussichtlichBis;

    private function __construct(Ausgabe $ausgabe, \DateTimeInterface $voraussichtlichBis)
    {
        $this->id = uniqid();
        $this->ausgabe = $ausgabe;
        $this->voraussichtlichBis = $voraussichtlichBis;
    }

    public static function leiheBuchAus(Buch $buch, string $studentId, \DateTimeInterface $voraussichtlichBis): self
    {
        if (!$buch->istVerleihbar()) {
            throw new \DomainException('Buch kann nicht ausgeliehen werden.');
        }

        // Validierung ob Student Bücher ausleihen darf wäre anhand des Student-AggregateRoot möglich

        $verleih = new self(Ausgabe::vonBuchAnStudent($buch->id(), $studentId), $voraussichtlichBis);

        // WARNING: jetzt nicht direkt das Buch als "ausgeliehen markieren", da wir sonst die Grenzen dieses Aggregates überschreiten.
        // don't $buch->ausleihen(...); it would not be persisted
        $verleih->raise(BuchAusgegeben::anStudent($verleih->id(), $buch->id(), $studentId, $voraussichtlichBis));

        return $verleih;
    }

    public function gibBuchZurueck(string $buchId, string $studentId): void
    {
        $this->rueckgabe = Rueckgabe::vonBuchDurchStudent($buchId, $studentId);

        $this->raise(BuchZurueckgegeben::vonStudent($buchId, $studentId));
    }

    public function istBeendet(): bool
    {
        return $this->rueckgabe instanceof Rueckgabe;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function ausgabe(): Ausgabe
    {
        return $this->ausgabe;
    }

    public function rueckgabe(): Rueckgabe
    {
        return $this->rueckgabe;
    }

    public function voraussichtlichBis(): \DateTimeInterface
    {
        return $this->voraussichtlichBis;
    }
}
