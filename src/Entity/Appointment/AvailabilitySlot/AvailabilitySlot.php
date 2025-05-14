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
#[ORM\Index(name: 'idx_active', fields: ['active'])]
#[ORM\Index(name: 'idx_booked', fields: ['booked'])]
#[ORM\Index(name: 'idx_date', fields: ['date'])]
#[ORM\Index(name: 'idx_start_time', fields: ['startTime'])]
#[ORM\Index(name: 'idx_end_time', fields: ['endTime'])]
#[ORM\Index(name: 'idx_date_start_end_time', fields: ['date', 'startTime', 'endTime'])]
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
    #[Serializer(['availabilitySlot'])]
    private ?DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer(['availabilitySlot'])]
    private ?DateTime $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer(['availabilitySlot'])]
    private ?DateTime $endTime = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    #[Serializer(['availabilitySlot'])]
    private ?bool $active = true;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Serializer(['availabilitySlot'])]
    private ?bool $booked = false;

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

    /**
     * Get the value of booked
     */
    public function isBooked(): ?bool
    {
        return $this->booked;
    }

    /**
     * Set the value of booked
     */
    public function setBooked(?bool $booked): self
    {
        $this->booked = $booked;

        return $this;
    }
}
