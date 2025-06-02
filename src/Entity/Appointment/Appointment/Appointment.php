<?php

namespace App\Entity\Appointment\Appointment;

use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Enum\Appointment\Appointment\AppointmentStatusEnum;
use App\Repository\Appointment\Appointment\AppointmentRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\Table;
use Gedmo\Mapping\Annotation\Blameable;
use Gedmo\Mapping\Annotation\Timestampable;
use JMS\Serializer\Annotation as Serializer;

#[Entity(repositoryClass: AppointmentRepository::class)]
#[Table(name: 'appointments')]
class Appointment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer\Groups(['appointment'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'appointment')]
    private ?Trainer $trainer = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'appointment')]
    private ?Client $client = null;

    #[ORM\ManyToOne(targetEntity: AvailabilitySlot::class, inversedBy: 'appointments')]
    #[Serializer\Groups(['appointment'])]
    private ?AvailabilitySlot $availabilitySlot = null;

    #[ORM\Column(type: Types::STRING, nullable: false, options: ['default' => AppointmentStatusEnum::SCHEDULED])]
    #[Serializer\Groups(['appointment'])]
    private ?string $status = AppointmentStatusEnum::SCHEDULED;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: true)]
    #[Blameable(on: 'create')]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Timestampable(on: 'create')]
    private ?DateTime $createdAt = null;

    # ===============================
    # ===== Proprietà non mappate
    # ===============================

    private ?Trainer $serializedTrainer = null;
    private ?Client $serializedClient = null;


    # ===============================
    # ===== Virtual properties
    # ===============================

    #[Serializer\VirtualProperty]
    #[Serializer\SerializedName("trainer")]
    #[Serializer\Groups(["appointment"])]
    public function virtualSerializedTrainer(): ?Trainer
    {
        return $this->serializedTrainer;
    }

    #[Serializer\VirtualProperty]
    #[Serializer\SerializedName("client")]
    #[Serializer\Groups(["appointment"])]
    public function virtualSerializedClient(): ?Client
    {
        return $this->serializedClient;
    }

    # ===============================
    # =====  Other methods
    # ===============================

    public function isScheduled(): bool
    {
        return $this->status  === AppointmentStatusEnum::SCHEDULED;
    }

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

    /**
     * Get the value of serializedTrainer
     */
    public function getSerializedTrainer(): ?Trainer
    {
        return $this->serializedTrainer;
    }

    /**
     * Set the value of serializedTrainer
     */
    public function setSerializedTrainer(?Trainer $serializedTrainer): self
    {
        $this->serializedTrainer = $serializedTrainer;

        return $this;
    }

    /**
     * Get the value of serializedClient
     */
    public function getSerializedClient(): ?Client
    {
        return $this->serializedClient;
    }

    /**
     * Set the value of serializedClient
     */
    public function setSerializedClient(?Client $serializedClient): self
    {
        $this->serializedClient = $serializedClient;

        return $this;
    }

    /**
     * Get the value of createdBy
     */
    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    /**
     * Set the value of createdBy
     */
    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(?DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
