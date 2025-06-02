<?php

namespace App\Model\Form\Appointment\Appointment;

use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use DateTime;

class AppointmentFilter
{
    private ?Trainer $trainer = null;

    private ?Client $client = null;

    private ?DateTime $date = null;

    private ?string $status = null;

    /**
     * Get the value of trainer
     */
    public function getTrainer(): ?Trainer
    {
        return $this->trainer;
    }

    /**
     * Set the value of trainer
     */
    public function setTrainer(?Trainer $trainer): self
    {
        $this->trainer = $trainer;

        return $this;
    }

    /**
     * Get the value of client
     */
    public function getClient(): ?Client
    {
        return $this->client;
    }

    /**
     * Set the value of client
     */
    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the value of date
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * Set the value of date
     */
    public function setDate(?DateTime $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
