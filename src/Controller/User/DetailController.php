<?php

namespace App\Controller\User;

use App\Controller\Controller;
use App\Entity\User\User;
use App\Serializer\User\UserGroupsHelper;
use App\Services\User\UserLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DetailController extends Controller
{
    public function __construct(
        private readonly UserLister $userLister
    ) {}

    #[Route(path: '/user/{user}', requirements: ['user' => '\d+'], methods: ['GET'])]
    public function detailUser(Request $request, User $user): JsonResponse
    {
        return $this->renderSerializedData($user, UserGroupsHelper::simpleUser());
    }
}
