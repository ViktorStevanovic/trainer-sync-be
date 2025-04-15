<?php

namespace App\Controller\User;

use App\Controller\Controller;
use App\Entity\User\User;
use App\Services\Password\PasswordManager;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfirmationController extends Controller
{
    public function __construct(
        private PasswordManager $passwordManager
    ) {}

    #[Route(path: '/confirm-email', methods: ['GET'])]
    public function confirmUserEmail(Request $request): JsonResponse
    {
        $token = $request->query->get('tk');

        /** @var User $user */
        $user = $this->getRepository(User::class)->findOneBy(['confirmationToken' => $token, 'active' => true]);

        if (!is_null($user)) {
            $user
                ->setConfirmationToken(null)
                ->setConfirmedEmail(true)
                ->setConfirmedEmailAt(new DateTime());
        }
        $this->save($user);

        // Genero il token di reset password
        $this->passwordManager->generateResetPasswordToken(user: $user);

        return $this->renderEmptyResponse();
    }
}
