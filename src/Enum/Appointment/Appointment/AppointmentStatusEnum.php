<?php

namespace App\Enum\Appointment\Appointment;

final class AppointmentStatusEnum
{
    public const string SCHEDULED = 'scheduled';

    public const string CANCELED = 'canceled';

    public const string COMPLETED = 'completed';


    /**
     * @return array
     */
    public static function getValidStatuses(): array
    {
        return [
            self::SCHEDULED,
            self::CANCELED,
            self::COMPLETED,
        ];
    }
}
