<?php

namespace App\EventListener\Auth;

use App\Entity\User\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsEventListener(
    event: 'lexik_jwt_authentication.on_authentication_success',
    method: 'onAuthenticationSuccess',
)]
class JWTAuthenticationSuccessHandler implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'lexik_jwt_authentication.on_authentication_success' => 'onAuthenticationSuccess',
        ];
    }

    public function __construct(private SerializerInterface $serializer) {}


    public function onAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();

        if (!$user) {
            return;
        }

        $data['user'] = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'surname' => $user->getSurname(),
            'userType' => $user->getUserType()->getCode(),
            'active' => $user->isActive(),
        ];

        $event->setData($data);
    }
}
