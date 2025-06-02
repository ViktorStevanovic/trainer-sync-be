<?php

namespace App\Model\Form\Appointment\AvailabilityOverride;

use App\Entity\Trainer\Trainer;
use DateTime;

class AvailabilityOverrideFilter
{
    private ?Trainer $trainer = null;

    private ?DateTime $date = null;

    private ?DateTime $startTime = null;

    private ?DateTime $endTime = null;

    private ?bool $fullDayOverride = null;

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
     * Get the value of fullDayOverride
     */
    public function isFullDayOverride(): ?bool
    {
        return $this->fullDayOverride;
    }

    /**
     * Set the value of fullDayOverride
     */
    public function setFullDayOverride(?bool $fullDayOverride): self
    {
        $this->fullDayOverride = $fullDayOverride;

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
