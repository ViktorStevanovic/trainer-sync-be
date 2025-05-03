<?php

namespace App\Model\Form\Appointment\ScheduleTemplate;

use App\Entity\Trainer\Trainer;

class ScheduleTemplateFilter
{
    private ?Trainer $trainer = null;

    private ?array $weekDays = [];

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
     * Get the value of weekDays
     */
    public function getWeekDays(): ?array
    {
        return $this->weekDays;
    }

    /**
     * Set the value of weekDays
     */
    public function setWeekDays(?array $weekDays): self
    {
        $this->weekDays = $weekDays;

        return $this;
    }
}
