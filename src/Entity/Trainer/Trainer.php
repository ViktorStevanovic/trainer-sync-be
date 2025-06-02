<?php

namespace App\Entity\Trainer;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Client\Client;
use App\Entity\User\User;
use App\Repository\Trainer\TrainerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation as Serializer;

#[Entity(repositoryClass: TrainerRepository::class)]
#[Table(name: 'trainers')]
class Trainer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer\Groups(['minimalTrainer', 'trainer'])]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: User::class)]
    #[Serializer\Groups(['minimalTrainer', 'trainer'])]
    private ?User $user = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Serializer\Groups(['trainer'])]
    private ?float $hourlyRate = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ["default" => true])]
    #[Serializer\Groups(['trainer'])]
    private ?bool $active = true;

    #[ORM\OneToMany(targetEntity: Client::class, mappedBy: 'trainer')]
    #[Serializer\Groups(['trainer'])]
    private Collection $clients;

    #[ORM\OneToMany(targetEntity: Appointment::class, mappedBy: 'trainer')]
    private Collection $appointments;

    # ===============================
    # ===== Costruttore
    # ===============================

    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->appointments = new ArrayCollection();
    }

    # ===============================
    # ===== Getters & Setters
    # ===============================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHourlyRate(): ?float
    {
        return $this->hourlyRate;
    }

    public function setHourlyRate(?float $hourlyRate): static
    {
        $this->hourlyRate = $hourlyRate;

        return $this;
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

    /**
     * @return Collection<int, Client>
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): static
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setTrainer($this);
        }

        return $this;
    }

    public function removeClient(Client $client): static
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getTrainer() === $this) {
                $client->setTrainer(null);
            }
        }

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
            $appointment->setTrainer($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): static
    {
        if ($this->appointments->removeElement($appointment)) {
            // set the owning side to null (unless already changed)
            if ($appointment->getTrainer() === $this) {
                $appointment->setTrainer(null);
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
