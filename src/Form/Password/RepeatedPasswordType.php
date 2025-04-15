<?php

namespace App\Form\Password;

use App\Entity\User\User;
use App\Error\ErrorCodeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class RepeatedPasswordType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('user');
        $resolver->setAllowedTypes('user', User::class);
        $resolver->setDefault('constraints', function (Options $options) {
            return [
                // new PasswordNotInBlacklist(options: $options['user']),
                // new PasswordNotRecentlyUsed(options: $options['user']),
                new Regex(
                    pattern: "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[$@$!%*?&_#])[A-Za-z\\d$@$!%*?&_#]{8,}/",
                    message: ErrorCodeEnum::ERROR_PASSWORD_002
                ),
            ];
        });
        $resolver->setDefaults([
            'type' => PasswordType::class,
            'first_name' => 'password',
            'second_name' => 'repeatPassword',
            'error_bubbling' => true,
        ]);
    }

    public function getParent(): ?string
    {
        return RepeatedType::class;
    }
}
