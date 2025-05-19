<?php

namespace App\Entity\Appointment\AvailabilityOverride;

use App\Entity\Trainer\Trainer;
use App\Repository\Appointment\AvailabilityOverride\AvailabilityOverrideRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation as Serializer;

#[Entity(repositoryClass: AvailabilityOverrideRepository::class)]
#[Table(name: 'availability_overrides')]
#[ORM\Index(name: 'idx_active', fields: ['active'])]
#[ORM\Index(name: 'idx_full_day_override', fields: ['fullDayOverride'])]
#[ORM\Index(name: 'idx_date', fields: ['date'])]
#[ORM\Index(name: 'idx_start_time', fields: ['startTime'])]
#[ORM\Index(name: 'idx_end_time', fields: ['endTime'])]
#[ORM\Index(name: 'idx_date_start_end_time', fields: ['date', 'startTime', 'endTime'])]
class AvailabilityOverride
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer\Groups(['availabilityOverride'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'availabilityOverrides')]
    #[Serializer\Groups(['availabilityOverride'])]
    private ?Trainer $trainer = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    #[Serializer\Groups(['availabilityOverride'])]
    #[Serializer\Type("DateTime<'Y-m-d'>")]
    private ?DateTime $date = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['availabilityOverride'])]
    #[Serializer\Type("DateTime<'H:i:s'>")]
    private ?DateTime $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Serializer\Groups(['availabilityOverride'])]
    #[Serializer\Type("DateTime<'H:i:s'>")]
    private ?DateTime $endTime = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Serializer\Groups(['availabilityOverride'])]
    private ?bool $fullDayOverride = false;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    #[Serializer\Groups(['availabilityOverride'])]
    private ?bool $active = true;

    # ===============================
    # ===== Getters & Setters
    # ===============================

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

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
