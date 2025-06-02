<?php

namespace App\Serializer\Appointment\Appointment;

use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Serializer\Appointment\AvailabilitySlot\AvailabilitySlotGroupsHelper;
use App\Serializer\Client\ClientGroupsHelper;
use App\Serializer\Trainer\TrainerGroupsHelper;

class AppointmentGroupsHelper
{
    public static function appointment(): array
    {
        return array_merge(
            TrainerGroupsHelper::minimalTrainer(),
            ClientGroupsHelper::minimalClient(),
            AvailabilitySlotGroupsHelper::availabilitySlot(),
            ['appointment']
        );
    }
}
