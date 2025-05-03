<?php

namespace App\Entity\Appointment\AvailabilitySlot;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Trainer\Trainer;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups as Serializer;

#[Entity()]
#[Table(name: 'availability_slots')]
class AvailabilitySlot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer(['availabilitySlot'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'availabilitySlots')]
    #[Serializer(['availabilitySlot'])]
    private ?Trainer $trainer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?DateTime $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?DateTime $endTime = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    #[Serializer(['scheduleTemplate'])]
    private ?bool $active = true;

    #[ORM\OneToOne(targetEntity: Appointment::class, mappedBy: 'availabilitySlot')]
    private ?Appointment $appointment = null;

    # ===============================
    # ===== Getters & Setters
    # ===============================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getTrainer(): ?Trainer
    {
        return $this->trainer;
    }

    public function setTrainer(?Trainer $trainer): static
    {
        $this->trainer = $trainer;

        return $this;
    }

    public function getAppointment(): ?Appointment
    {
        return $this->appointment;
    }

    public function setAppointment(?Appointment $appointment): static
    {
        // unset the owning side of the relation if necessary
        if ($appointment === null && $this->appointment !== null) {
            $this->appointment->setAvailabilitySlot(null);
        }

        // set the owning side of the relation if necessary
        if ($appointment !== null && $appointment->getAvailabilitySlot() !== $this) {
            $appointment->setAvailabilitySlot($this);
        }

        $this->appointment = $appointment;

        return $this;
    }
}
