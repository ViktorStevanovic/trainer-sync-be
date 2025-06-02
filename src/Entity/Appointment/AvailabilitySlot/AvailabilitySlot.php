<?php

namespace App\Entity\Appointment\AvailabilitySlot;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Trainer\Trainer;
use App\Enum\Appointment\Appointment\AppointmentStatusEnum;
use App\Repository\Appointment\AvailabilitySlot\AvailabilitySlotRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation as Serializer;

#[Entity(repositoryClass: AvailabilitySlotRepository::class)]
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
    #[Serializer\Groups(['availabilitySlot'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'availabilitySlots')]
    private ?Trainer $trainer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    #[Serializer\Groups(['availabilitySlot'])]
    #[Serializer\Type("DateTime<'Y-m-d'>")]
    private ?DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer\Groups(['availabilitySlot'])]
    #[Serializer\Type("DateTime<'H:i:s'>")]
    private ?DateTime $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer\Groups(['availabilitySlot'])]
    #[Serializer\Type("DateTime<'H:i:s'>")]
    private ?DateTime $endTime = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private ?bool $active = true;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private ?bool $booked = false;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'availabilitySlot')]
    private Collection $appointments;

    # ===============================
    # ===== Costruttore
    # ===============================

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    # ===============================
    # ===== Altri metodi
    # ===============================

    public function getScheduledAppointment(): ?Appointment
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('status', AppointmentStatusEnum::SCHEDULED))
            ->setMaxResults(1);

        $matchingAppointments = $this->appointments->matching($criteria);

        return $matchingAppointments->isEmpty() ? null : $matchingAppointments->first();
    }

    # ===============================
    # ===== Getters & Setters
    # ===============================

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

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(\DateTimeInterface $endTime): static
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

    public function isBooked(): ?bool
    {
        return $this->booked;
    }

    public function setBooked(bool $booked): static
    {
        $this->booked = $booked;

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

    /**
     * @return Collection<int, Appointment>
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): static
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments->add($appointment);
            $appointment->setAvailabilitySlot($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getAvailabilitySlot() === $this) {
                $appointment->setAvailabilitySlot(null);
            }
        }

        return $this;
    }
}
