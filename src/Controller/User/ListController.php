<?php

namespace App\Controller\User;

use App\Controller\Controller;
use App\Form\User\UserFilterType;
use App\Model\Form\UserFilter;
use App\Serializer\User\UserGroupsHelper;
use App\Services\User\UserLister;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends Controller
{
    public function __construct(
        private readonly UserLister $userLister
    ) {}

    #[Route(path: '/user', methods: ['GET'])]
    public function listUsers(Request $request): JsonResponse
    {
        $filter = new UserFilter();
        $form = $this->createForm(UserFilterType::class, $filter);

        $form->submit($request->query->all());
        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        $users = $this->userLister->getFilteredUsers(filter: $filter);
        return $this->renderSerializedData($users, UserGroupsHelper::minimalUser());
    }
}
