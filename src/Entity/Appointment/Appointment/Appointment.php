<?php

namespace App\Entity\Appointment\Appointment;

use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use App\Enum\Appointment\Appointment\AppointmentStatusEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups as Serializer;

#[Entity()]
#[Table(name: 'appointments')]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer(['appointment'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'appointment')]
    #[Serializer(['appointment'])]
    private ?Trainer $trainer = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'appointment')]
    #[Serializer(['appointment'])]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: AvailabilitySlot::class, inversedBy: 'appointments')]
    #[Serializer(['appointment'])]
    private ?AvailabilitySlot $availabilitySlot = null;

    #[ORM\Column(type: Types::STRING, nullable: false, options: ['default' => AppointmentStatusEnum::SCHEDULED])]
    #[Serializer(['appointment'])]
    private ?string $status = AppointmentStatusEnum::SCHEDULED;

    # ===============================
    # ===== Getters & Setters
    # ===============================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function getAvailabilitySlot(): ?AvailabilitySlot
    {
        return $this->availabilitySlot;
    }

    public function setAvailabilitySlot(?AvailabilitySlot $availabilitySlot): static
    {
        $this->availabilitySlot = $availabilitySlot;

        return $this;
    }
}
