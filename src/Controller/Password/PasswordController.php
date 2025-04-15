<?php

namespace App\Controller\Password;

use App\Controller\Controller;
use App\Entity\User\User;
use App\Error\ErrorCodeEnum;
use App\Form\Password\ChangePasswordType;
use App\Form\Password\ResetPasswordType;
use App\Services\Password\PasswordManager;
use App\Services\User\UserPasswordManager;
use DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends Controller
{
    public function __construct(
        private readonly PasswordManager $passwordManager
    ) {}

    /**
     * Path tramite cui l'utente resetta la password tramite token temporaneo
     */
    #[Route(path: '/change-password', methods: 'POST')]
    public function anonymousUserChangePassword(Request $request): JsonResponse
    {
        # controllo la validità del token
        $isTokenValid = $request->request->has('tk')
            && !is_null($request->request->get('tk'));
        // && $this->passwordManager->checkTokenValidity($request->request->get('tk'));

        # se il token non è valido, restituisco errore
        if (!$isTokenValid) {
            return $this->renderSerializedErrorMessage(message: ErrorCodeEnum::ERROR_PASSWORD_006, statusCode: Response::HTTP_FORBIDDEN);
        }

        # cerco l'utente con il token in input
        /** @var User $user */
        $user = $this->getRepository(User::class)->findOneBy([
            'resetPasswordToken' => $request->request->get('tk'),
            'active' => true,
            'confirmedEmail' => true
        ]);

        # rimuovo il token dai parametri d'input così da non averlo alla validazione del form
        $request->request->remove('tk');
        return $this->changePassword(user: $user, request: $request);
    }

    /**
     * Path tramite cui l'utente loggato resetta la propria password
     */
    #[Route(path: '/user/change-password', methods: 'POST')]
    // #[IsGranted(
    //     attribute: CanChangePasswordVoter::CAN_CHANGE_PASSWORD,
    //     message: ErrorCodeEnum::ERROR_PASSWORD_005
    // )]
    public function loggedUserChangePassword(Request $request): JsonResponse
    {
        return $this->changePassword(user: $this->getUser(), request: $request, isLoggedUser: true);
    }

    /**
     * Metodo per il cambio della password di $user
     *
     * @param User $user
     * @param Request $request
     * @param bool $isLoggedUser flag che indica se il ripristino sta avvenendo per l'utente loggato
     * @return JsonResponse
     */
    private function changePassword(User $user, Request $request, bool $isLoggedUser = false): JsonResponse
    {
        $form = $isLoggedUser
            ? $this->createForm(ChangePasswordType::class, null, ['user' => $user])
            : $this->createForm(ResetPasswordType::class, null, ['user' => $user]);

        $form->submit($request->request->all());

        if (!$form->isValid()) {
            return $this->renderSerializedFormErrors($form);
        }

        # procedo al cambio password
        $this->passwordManager->changePassword(user: $user, newPassword: $form->get('newPassword')->get('password')->getData());

        # se utente loggato, restituisco lui stesso, dato che il FE se lo aspetta;
        # il token essendo stato invalidato porterà al logout dell'utente;
        return $this->renderEmptyResponse();
    }
}
