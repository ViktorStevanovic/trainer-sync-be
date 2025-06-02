<?php

namespace App\Entity\Client;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation as Serializer;

#[Entity()]
#[Table(name: 'clients')]
class Client
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer\Groups(['client'])]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[Serializer\Groups(['client'])]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'clients')]
    #[Serializer\Groups(['client'])]
    private ?Trainer $trainer = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Serializer\Groups(['client'])]
    private ?int $age = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    #[Serializer\Groups(['client'])]
    private ?int $height = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Serializer\Groups(['client'])]
    private ?float $weight = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Serializer\Groups(['client'])]
    private ?float $fatMass = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Serializer\Groups(['client'])]
    private ?float $freeFatMass = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Serializer\Groups(['client'])]
    private ?float $totalBodyWater = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    #[Serializer\Groups(['client'])]
    private ?bool $active = true;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'client')]
    private Collection $appointments;

    public function __construct()
    {
        $this->appointments = new ArrayCollection();
    }

    # ===============================
    # ===== Getters & Setters
    # ===============================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

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

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(?int $weight): static
    {
        $this->weight = $weight;

        return $this;
    }

    public function getFatMass(): ?float
    {
        return $this->fatMass;
    }

    public function setFatMass(?float $fatMass): static
    {
        $this->fatMass = $fatMass;

        return $this;
    }

    public function getFreeFatMass(): ?float
    {
        return $this->freeFatMass;
    }

    public function setFreeFatMass(?float $freeFatMass): static
    {
        $this->freeFatMass = $freeFatMass;

        return $this;
    }

    public function getTotalBodyWater(): ?float
    {
        return $this->totalBodyWater;
    }

    public function setTotalBodyWater(?float $totalBodyWater): static
    {
        $this->totalBodyWater = $totalBodyWater;

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
            $appointment->setClient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getClient() === $this) {
                $appointment->setClient(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of active
     */
    public function isActive(): ?bool
    {
        return $this->active;
    }

    /**
     * Set the value of active
     */
    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
