<?php

namespace App\Serializer\Client;

use App\Serializer\Trainer\TrainerGroupsHelper;
use App\Serializer\User\UserGroupsHelper;

class ClientGroupsHelper
{
    public static function minimalClient(): array
    {
        return array_merge(
            UserGroupsHelper::minimalUser(),
            ['minimalClient']
        );
    }

    public static function client(): array
    {
        return array_merge(
            TrainerGroupsHelper::minimalTrainer(),
            UserGroupsHelper::minimalUser(),
            ['client']
        );
    }
}
