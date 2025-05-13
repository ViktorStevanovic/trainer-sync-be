<?php

namespace App\Services\Utils\Validators;

use DateTime;

class TimeRangeValidator
{
    /**
     * Check if two time ranges overlap.
     *
     * @param DateTime $startA
     * @param DateTime $endA
     * @param DateTime $startB
     * @param DateTime $endB
     * @return bool
     */
    public function isOverlapping(
        DateTime $startA,
        DateTime $endA,
        DateTime $startB,
        DateTime $endB
    ): bool {
        return $startA < $endB && $endA > $startB;
    }
}
