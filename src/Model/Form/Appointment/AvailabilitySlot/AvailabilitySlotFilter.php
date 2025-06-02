<?php

namespace App\Model\Form\Appointment\AvailabilitySlot;

use App\Entity\Trainer\Trainer;
use DateTime;

class AvailabilitySlotFilter
{
    private ?Trainer $trainer = null;

    private ?DateTime $date = null;

    private ?DateTime $startTime = null;

    private ?DateTime $endTime = null;

    private ?bool $status = null;

    private ?bool $booked = null;

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
     * Get the value of startTime
     */
    public function getStartTime(): ?DateTime
    {
        return $this->startTime;
    }

    /**
     * Set the value of startTime
     */
    public function setStartTime(?DateTime $startTime): self
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get the value of endTime
     */
    public function getEndTime(): ?DateTime
    {
        return $this->endTime;
    }

    /**
     * Set the value of endTime
     */
    public function setEndTime(?DateTime $endTime): self
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get the value of status
     */
    public function getStatus(): ?bool
    {
        return $this->status;
    }

    /**
     * Set the value of status
     */
    public function setStatus(?bool $status): self
    {
        $this->status = $status;

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
}
