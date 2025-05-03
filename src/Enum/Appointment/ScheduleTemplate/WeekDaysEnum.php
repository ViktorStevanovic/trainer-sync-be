<?php

namespace App\Enum\Appointment\ScheduleTemplate;

final class WeekDaysEnum
{
    public const int MONDAY    = 1;
    public const int TUESDAY   = 2;
    public const int WEDNESDAY = 3;
    public const int THURSDAY  = 4;
    public const int FRIDAY    = 5;
    public const int SATURDAY  = 6;
    public const int SUNDAY    = 7;

    /**
     * Returns an array of all day values.
     */
    public static function getValues(): array
    {
        return [
            self::MONDAY,
            self::TUESDAY,
            self::WEDNESDAY,
            self::THURSDAY,
            self::FRIDAY,
            self::SATURDAY,
            self::SUNDAY,
        ];
    }

    /**
     * Returns a key-value array for form choices or labels.
     */
    public static function getChoices(): array
    {
        return [
            'Monday'    => self::MONDAY,
            'Tuesday'   => self::TUESDAY,
            'Wednesday' => self::WEDNESDAY,
            'Thursday'  => self::THURSDAY,
            'Friday'    => self::FRIDAY,
            'Saturday'  => self::SATURDAY,
            'Sunday'    => self::SUNDAY,
        ];
    }
}
