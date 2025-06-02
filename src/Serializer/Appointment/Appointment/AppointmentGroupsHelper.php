<?php

namespace App\Serializer\Appointment\Appointment;

use App\Entity\Appointment\AvailabilitySlot\AvailabilitySlot;
use App\Serializer\Appointment\AvailabilitySlot\AvailabilitySlotGroupsHelper;

class AppointmentGroupsHelper
{
    public static function appointment(): array
    {
        return array_merge(
            AvailabilitySlotGroupsHelper::availabilitySlot(),
            ['appointment']
        );
    }
}
