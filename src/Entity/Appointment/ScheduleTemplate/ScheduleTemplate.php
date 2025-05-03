<?php

namespace App\Entity\Appointment\ScheduleTemplate;

use App\Entity\Trainer\Trainer;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use JMS\Serializer\Annotation\Groups as Serializer;

#[Entity()]
#[Table(name: 'schedule_templates')]
class ScheduleTemplate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Serializer(['scheduleTemplate'])]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Trainer::class, inversedBy: 'scheduleTemplates')]
    #[Serializer(['scheduleTemplate'])]
    private ?Trainer $trainer = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?int $weekDay = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?DateTime $startTime = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?DateTime $endTime = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    #[Serializer(['scheduleTemplate'])]
    private ?int $blockTime = null;

    # ===============================
    # ===== Getters & Setters
    # ===============================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekDay(): ?int
    {
        return $this->weekDay;
    }

    public function setWeekDay(int $weekDay): static
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    public function getStartTime(): ?DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(DateTime $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getEndTime(): ?DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(DateTime $endTime): static
    {
        $this->endTime = $endTime;

        return $this;
    }

    public function getBlockTime(): ?int
    {
        return $this->blockTime;
    }

    public function setBlockTime(int $blockTime): static
    {
        $this->blockTime = $blockTime;

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
}
