<?php

namespace App\Controller\User;

use App\Controller\Controller;
use App\Entity\User\User;
use App\Form\User\UserManageType;
use App\Services\User\CreateUserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CreateEditController extends Controller
{
    public function __construct(
        private readonly CreateUserManager $createUserManager
    ) {}

    #[Route(path: '/user', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $this->createUserManager->createUser(request: $request);
        // Una volta creato l'utente, bisogna mandare una mail di conferma account

        return $this->renderEmptyResponse();
    }

    #[Route(path: '/user/{user}', requirements: ['user' => '\d+'], methods: ['PUT'])]
    public function editUser(Request $request, User $user): JsonResponse
    {
        $this->manageUser(request: $request, user: $user);
        return $this->renderEmptyResponse();
    }

    /**
     * @param Request $request
     * @param User $user
     * 
     * @return JsonResponse
     */
    public function manageUser(Request $request, User $user): JsonResponse
    {
        $this->beginTransaction();

        try {
            $data = json_decode($request->getContent(), true);
            $form = $this->createForm(UserManageType::class, $user);
            $form->submit($data);

            if ($form->isSubmitted() && !$form->isValid()) {
                return $this->renderSerializedFormErrors($form);
            }

            $this->save($user);
            $this->commit();
        } catch (\Throwable $th) {
            $this->rollback();
            throw $th;
        }
        return $this->renderEmptyResponse();
    }
}
