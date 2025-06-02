<?php

namespace App\EventListener\Appointment;

use App\Entity\Appointment\Appointment\Appointment;
use App\Entity\Client\Client;
use App\Entity\Trainer\Trainer;
use App\Entity\User\User;
use App\Enum\UserType\UserTypeEnum;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

class AppointmentSerializationSubscriber implements EventSubscriberInterface
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'method' => 'onPreSerialize',
                'class' => Appointment::class, // Only for Appointment entities
            ],
        ];
    }

    public function onPreSerialize(ObjectEvent $event): void
    {
        $appointment = $event->getObject();
        dump($appointment);
        // Safety check, although we already restricted with 'class' in getSubscribedEvents
        if (!$appointment instanceof Appointment) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        /** @var User $user */
        $user = $token->getUser();
        if (!$user) {
            return;
        }
        dump($user);

        // If logged user is a Client, attach the serializedTrainer
        if ($user->getUserType()->getCode() === UserTypeEnum::CLIENT) {
            $appointment->setSerializedTrainer($appointment->getTrainer());
            dump($appointment);
        }

        // If logged user is a Trainer, attach the serializedClient
        if ($user->getUserType()->getCode() === UserTypeEnum::TRAINER) {
            $appointment->setSerializedClient($appointment->getClient());
        }

        // If logged user is a Trainer, attach the serializedClient
        if ($user->getUserType()->getCode() === UserTypeEnum::ADMIN) {
            $appointment->setSerializedClient($appointment->getClient());
            $appointment->setSerializedTrainer($appointment->getTrainer());
        }
    }
}
