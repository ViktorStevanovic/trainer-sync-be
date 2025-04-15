<?php

namespace App\Services\User;

use App\Entity\User\User;
use App\Error\ErrorCodeEnum;
use App\Form\User\UserManageType;
use App\Services\Utils\Helper\DoctrineHelper;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Uid\Uuid;

class CreateUserManager
{
    public function __construct(
        private FormFactoryInterface $formFactory,
        private DoctrineHelper $doctrineHelper,
    ) {}

    public function createUser(Request $request): void
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $form = $this->formFactory->create(UserManageType::class, $user);
        $form->submit($data);

        if (!$form->isValid()) {
            throw new Exception(ErrorCodeEnum::ERROR_DEFAULT_ERROR_001);
        }

        $user->setConfirmationToken(Uuid::v4()->toBase32());

        $this->doctrineHelper->save($user);
    }
}
