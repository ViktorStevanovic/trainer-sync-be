<?php

namespace App\Serializer\Trainer;

use App\Serializer\Client\ClientGroupsHelper;
use App\Serializer\User\UserGroupsHelper;

class TrainerGroupsHelper
{
    public static function minimalTrainer(): array
    {
        return array_merge(
            UserGroupsHelper::minimalUser(),
            ['minimalTrainer']
        );
    }

    public static function trainer(): array
    {
        return array_merge(
            ClientGroupsHelper::minimalClient(),
            UserGroupsHelper::minimalUser(),
            ['trainer']
        );
    }
}
