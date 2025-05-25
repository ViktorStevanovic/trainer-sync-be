<?php

namespace App\Command\Appointment\AvailabilitySlot;

use App\Services\Utils\Helper\DoctrineHelper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:delete-old-slots',
    description: 'Deletes old availability slots.'
)]
class DeleteOldAvailabilitySlotsCommand extends Command
{
    use LockableTrait;

    public function __construct(
        private readonly DoctrineHelper $doctrineHelper
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->lock()) {
            return self::SUCCESS;
        }

        // se gli slot non sono prenotati cancello completamente dal db
        $this->doctrineHelper->executeQuery(
            sql: 'DELETE FROM availability_slots
                    WHERE booked = false
                    AND date = DATE_SUB(CURDATE(), INTERVAL 1 DAY);'
        );

        // se gli slot sono prenotati li disattivo cosi da tenermi lo storico
        $this->doctrineHelper->executeQuery(
            sql: 'UPDATE availability_slots
                    SET active = false
                    WHERE booked = true
                    AND date = DATE_SUB(CURDATE(), INTERVAL 1 DAY);'
        );

        return self::SUCCESS;
    }
}
