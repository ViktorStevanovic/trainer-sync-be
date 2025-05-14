<?php

namespace App\Command\Appointment\AvailabilitySlot;

use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Entity\Trainer\Trainer;
use App\Model\Form\Appointment\AvailabilityOverride\AvailabilityOverrideFilter;
use App\Services\Appointment\AvailabilityOverride\AvailabilityOverrideLister;
use App\Services\Appointment\AvailabilitySlot\AvailabilitySlotFactory;
use App\Services\Appointment\ScheduleTemplate\ScheduleTemplateLister;
use App\Services\Trainer\TrainerLister;
use App\Services\Utils\Helper\DoctrineHelper;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:generate-availability-slots',
    description: 'Generates availability slots for the next 21 days based on schedule templates.'
)]
class GenerateAvailabilitySlotsCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly AvailabilitySlotFactory $availabilitySlotFactory
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            return self::SUCCESS;
        }

        $this->availabilitySlotFactory->generateAvailabilitySlot();

        $output->writeln('<info>Availability slots generated for the next 21 days.</info>');

        return self::SUCCESS;
    }
}
