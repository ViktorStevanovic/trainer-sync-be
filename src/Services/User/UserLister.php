<?php

namespace App\Services\User;

use App\Entity\User\User;
use App\Model\Form\UserFilter;
use App\Repository\UserRepository;
use App\Services\Utils\Helper\DoctrineHelper;
use App\Services\Utils\Helper\LoggedUserService;

class UserLister
{
    public function __construct(
        private DoctrineHelper $doctrineHelper,
        private LoggedUserService $loggedUserService
    ) {}

    /**
     * @param User|null $user
     * 
     * @return User[]
     */
    public function getFilteredUsers(?UserFilter $filter = null, ?User $user = null): array
    {
        /** @var User $user */
        $user = is_null($user) ? $this->loggedUserService->getLoggedUser() : $user;

        /** @var UserRepository $repo */
        $repo = $this->doctrineHelper->getRepository(User::class);

        $qb = $repo->createBaseQbVisibleToUser(user: $user);
        if (!is_null($filter)) {

            $userType = $filter->getUserType();
            if (!is_null($userType)) {
                $qb
                    ->andWhere('u.userType = :userType')
                    ->setParameter('userType', $userType);
            }

            // $active = $filter->getStatus();
            // if (!is_null($active)) {
            //     $qb
            //         ->andWhere('u.active = :active')
            //         ->setParameter('active', $active);
            // }
        }

        return $qb->getQuery()->getResult();
    }
}
