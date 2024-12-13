<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?\DateTimeInterface $timeSlot = null;

    #[ORM\Column(length: 255)]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?string $eventName = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[Groups(['reservation:read', 'reservation:write'])]
    private ?User $Relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getTimeSlot(): ?\DateTimeInterface
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(\DateTimeInterface $timeSlot): static
    {
        $this->timeSlot = $timeSlot;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getRelation(): ?User
    {
        return $this->Relation;
    }

    public function setRelation(?User $Relation): static
    {
        $this->Relation = $Relation;

        return $this;
    }
}
