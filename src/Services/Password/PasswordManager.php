<?php

namespace App\Services\Password;

use App\Entity\User\User;
use App\Services\Utils\Helper\DoctrineHelper;
use DateTime;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\Uuid;

class PasswordManager
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private FormFactoryInterface $formFactory,
        private DoctrineHelper $doctrineHelper,
    ) {}

    public function generateResetPasswordToken(User $user): void
    {
        $passwordToken = Uuid::v4()->toBase32();

        $user
            ->setResetPasswordToken($passwordToken)
            ->setResetPasswordRequestedAt(new DateTime());

        $this->doctrineHelper->save($user);
    }

    /**
     * Procedo al cambio password dell'utente
     *
     * @param User $user
     * @param string $newPassword
     * @return void
     */
    public function changePassword(User $user, string $newPassword): void
    {
        # codifica della nuova password
        $encodedPsw = $this->passwordHasher->hashPassword($user, $newPassword);
        $user
            ->setResetPasswordToken(null) # tolgo il token temporaneo di reset (a prescindere)
            ->setLastToken(null) # modifico il token per sloggare l'utente (anche se il reset avviene da non loggato)
            ->setPasswordChangedAt(new DateTime())
            ->setPassword($encodedPsw);

        $this->doctrineHelper->save($user);

        # invio l'email per informare dell'avvenuto cambio password
        // $this->mailer->send(ChangedPasswordMail::class, ["user" => $user]);
    }
}
