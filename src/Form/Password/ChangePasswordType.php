<?php

namespace App\Form\Password;

use App\Error\ErrorCodeEnum;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

/** * Form da utilizzare per far cambiare la password all'utente loggato */
class ChangePasswordType extends ResetPasswordType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm(builder: $builder, options: $options);
        $builder->add('oldPassword', PasswordType::class, [
            'constraints' => [new UserPassword(message: ErrorCodeEnum::ERROR_PASSWORD_001)],
        ]);
    }
}
