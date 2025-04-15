<?php

namespace App\Enum\UserType;

final class UserTypeEnum
{
    public const string ADMIN = "admin";

    public const string PERSONAL_TRAINER = "personal-trainer";

    public const string CLIENT = "client";

    public static function getValidUserTypes(): array
    {
        return [
            self::ADMIN,
            self::PERSONAL_TRAINER,
            self::CLIENT,
        ];
    }
}
