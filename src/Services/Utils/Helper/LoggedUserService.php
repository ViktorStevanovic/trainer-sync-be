<?php

namespace App\Services\Utils\Helper;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

readonly class LoggedUserService
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getLoggedUser(): ?UserInterface
    {
        return $this->security->getUser();
    }
}
