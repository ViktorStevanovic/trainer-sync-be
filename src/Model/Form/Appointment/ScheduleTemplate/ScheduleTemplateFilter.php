<?php

namespace App\Model\Form\Appointment\ScheduleTemplate;

use App\Entity\Trainer\Trainer;

class ScheduleTemplateFilter
{
    private ?array $weekDays = [];

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
