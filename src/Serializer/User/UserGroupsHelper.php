<?php

namespace App\Serializer\User;

use App\Serializer\UserType\UserTypeGroupsHelper;

class UserGroupsHelper
{
    public static function minimalUser(): array
    {
        return array_merge(
            UserTypeGroupsHelper::userType(),
            ['minimalUser']
        );
    }

    public static function simpleUser(): array
    {
        return array_merge(
            UserTypeGroupsHelper::userType(),
            ['simpleUser']
        );
    }

    public static function user(): array
    {
        return array_merge(
            UserTypeGroupsHelper::userType(),
            ['user']
        );
    }
}
