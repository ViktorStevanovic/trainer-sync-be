<?php

namespace App\Services\Client;

use App\Entity\Client\Client;
use App\Entity\User\User;
use App\Error\ErrorCodeEnum;
use App\Form\Client\ClientType;
use App\Services\Utils\Helper\DoctrineHelper;
use Exception;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class ClientCreateManager
{
    public function __construct(
        private readonly DoctrineHelper $doctrineHelper,
        private readonly FormFactoryInterface $formFactory
    ) {}

    /**
     * @param User $user
     * 
     * @return void
     */
    public function createClientUser(User $user, Request $request): void
    {
        $client = new Client()
            ->setUser($user);

        $form = $this->formFactory->create(ClientType::class, $client);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw new Exception(message: ErrorCodeEnum::ERROR_DEFAULT_ERROR_001);
        }

        $this->doctrineHelper->persist($client);
    }
}
